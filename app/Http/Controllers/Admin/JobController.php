<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminJobStoreRequest;
use App\Http\Requests\AdminJobUpdateRequest;
use App\Models\Job;
use App\Support\IndonesiaRegions;
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

        $loc = IndonesiaRegions::normalizeJobLocation($validated);
        $validated['province'] = $loc['province'];
        $validated['city'] = $loc['city'];
        $validated['district'] = $loc['district'];
        $validated['location'] = $loc['location'];

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

        $loc = IndonesiaRegions::normalizeJobLocation($validated);
        $validated['province'] = $loc['province'];
        $validated['city'] = $loc['city'];
        $validated['district'] = $loc['district'];
        $validated['location'] = $loc['location'];

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
        // Skala 10.000+ pencari kerja: JANGAN load semua user + kirim sinkron
        // di request HTTP (hang berjam-jam + limit Gmail). Pecah jadi
        // chunk-job antrean (±50 email/job) agar request langsung kembali
        // dan pengiriman berjalan bertahap di queue worker.
        $total = \App\Models\User::where('role', 'umum')->count();

        if ($total === 0) {
            return redirect()->back()->with('error', 'Belum ada pencari kerja untuk di-broadcast.');
        }

        // H6: idempoten server-side — jika batch job ini masih antre di
        // queued_jobs, jangan dispatch lagi (double-click / retry aman).
        // Pengecekan via unserialize payload (presisi jobId), BUKAN LIKE
        // string mentah: payload tersimpan sebagai JSON sehingga kutipnya
        // ter-escape dan LIKE `"jobId";i:N;` tidak pernah cocok.
        if ($this->broadcastAlreadyScheduled($job)) {
            return redirect()->back()->with(
                'success',
                'Broadcast lowongan ini sudah dijadwalkan sebelumnya dan sedang diproses worker. Tunggu hingga selesai sebelum broadcast ulang.'
            );
        }

        $minId = (int) \App\Models\User::where('role', 'umum')->min('id');
        $maxId = (int) \App\Models\User::where('role', 'umum')->max('id');
        $perJob = 50;
        $dispatched = 0;

        for ($start = $minId; $start <= $maxId; $start += $perJob) {
            \App\Jobs\SendJobBroadcastChunk::dispatch($job->id, $start, min($start + $perJob - 1, $maxId));
            $dispatched++;
        }

        return redirect()->back()->with(
            'success',
            'Broadcast dijadwalkan ke ' . number_format($total, 0, ',', '.') . ' pencari kerja via antrean (' . $dispatched . ' batch). Pastikan queue worker berjalan: php artisan queue:work.'
        );
    }

    /**
     * H6: true bila masih ada chunk broadcast job ini yang antre di
     * queued_jobs (database driver). Gagal-baca = false (fail-open:
     * dispatch tetap jalan, duplikat dicegah throttle + retry_after).
     */
    protected function broadcastAlreadyScheduled(Job $job): bool
    {
        try {
            $payloads = \Illuminate\Support\Facades\DB::table('queued_jobs')
                ->where('payload', 'like', '%SendJobBroadcastChunk%')
                ->pluck('payload');

            foreach ($payloads as $payload) {
                $data = json_decode((string) $payload, true);
                $command = $data['data']['command'] ?? null;
                if (! is_string($command)) {
                    continue;
                }
                $instance = @unserialize($command);
                if ($instance instanceof \App\Jobs\SendJobBroadcastChunk
                    && $instance->jobId === (int) $job->id) {
                    return true;
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Cek idempotensi broadcast gagal: ' . $e->getMessage());
        }

        return false;
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



