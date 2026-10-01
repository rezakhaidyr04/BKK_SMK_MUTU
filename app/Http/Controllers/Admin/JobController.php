<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminJobStoreRequest;
use App\Http\Requests\AdminJobUpdateRequest;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('job_type', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(AdminJobStoreRequest $request)
    {
        $validated = $request->validated();

        $job = Job::create($validated);

        // Admin membuat lowongan yang langsung aktif → beritahu pencari kerja.
        if (($validated['status'] ?? null) === 'active') {
            $this->notifyJobSeekers($job);
        }

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    public function show(Job $job)
    {
        $job->load('applications.user.documents');

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(AdminJobUpdateRequest $request, Job $job)
    {
        $validated = $request->validated();

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    public function broadcast(Job $job)
    {
        $jobseekers = \App\Models\User::where('role', 'umum')->get();

        // Kirim sinkron langsung via Mailable (bukan antrean database) agar
        // langsung sampai ke email pencari kerja tanpa tergantung worker.
        // Mailable membawa header List-Unsubscribe agar tidak dianggap spam.
        $sent = 0;
        $failed = 0;
        foreach ($jobseekers as $user) {
            try {
                \Illuminate\Support\Facades\Mail::to($user->email)
                    ->send(new \App\Mail\JobBroadcastMail($job, $user));
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                \Illuminate\Support\Facades\Log::warning('Broadcast lowongan #' . $job->id . ' gagal ke ' . $user->email . ': ' . $e->getMessage());
            }
        }

        $message = 'Notifikasi lowongan kerja berhasil di-broadcast ke ' . $sent . ' pencari kerja melalui email.';
        if ($failed > 0) {
            $message .= ' (' . $failed . ' gagal — periksa log/email penerima.)';
        }

        return redirect()->back()->with('success', $message);
    }

    public function approve(Job $job)
    {
        $wasActive = $job->status === 'active';
        $job->update(['status' => 'active']);

        // Otomatis: beritahu pencari kerja di dalam aplikasi saat lowongan
        // baru dipublikasikan (pending/draft -> active). Sinkron + try/catch
        // agar approval tidak pernah gagal gara-gara notifikasi.
        if (! $wasActive) {
            $this->notifyJobSeekers($job);
        }

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan disetujui dan dipublikasikan.');
    }

    public function reject(Job $job)
    {
        $job->update(['status' => 'rejected']);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan ditolak.');
    }

    /**
     * Kirim notifikasi database "lowongan baru" ke semua pencari kerja.
     */
    protected function notifyJobSeekers(Job $job): void
    {
        \App\Services\NewJobNotifier::notifySeekers($job);
    }
}
