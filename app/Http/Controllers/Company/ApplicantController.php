<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;

        if (! $company) {
            abort(404, 'Profil perusahaan tidak ditemukan.');
        }

        // Daftar lowongan milik sendiri untuk filter (id => judul).
        $jobs = \App\Models\Job::where('company_id', $company->id)
            ->orderByDesc('created_at')
            ->get(['id', 'title']);

        // Filter pelamar per lowongan (mis. dari badge angka di Lowongan Saya).
        // Paksa integer + pastikan lowongan milik sendiri (cegah IDOR).
        $selectedJobId = $request->filled('job_id') && $jobs->contains('id', (int) $request->query('job_id'))
            ? (int) $request->query('job_id')
            : null;

        $applications = Application::with(['job', 'user'])
            ->whereHas('job', function ($query) use ($company, $selectedJobId) {
                $query->where('company_id', $company->id);
                if ($selectedJobId) {
                    $query->where('id', $selectedJobId);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('company.applicants.index', compact('applications', 'company', 'jobs'));
    }

    /**
     * Export CSV pelamar (dibuka rapi di Excel Indonesia via BOM).
     * Filter job_id dihormati + IDOR-safe seperti index().
     */
    public function export(Request $request)
    {
        $company = $request->user()->company;

        if (! $company) {
            abort(404, 'Profil perusahaan tidak ditemukan.');
        }

        $jobs = \App\Models\Job::where('company_id', $company->id)
            ->orderByDesc('created_at')
            ->get(['id', 'title']);

        $selectedJobId = $request->filled('job_id') && $jobs->contains('id', (int) $request->query('job_id'))
            ? (int) $request->query('job_id')
            : null;

        $applications = Application::with(['job', 'user'])
            ->whereHas('job', function ($query) use ($company, $selectedJobId) {
                $query->where('company_id', $company->id);
                if ($selectedJobId) {
                    $query->where('id', $selectedJobId);
                }
            })
            ->latest()
            ->take(2000)
            ->get();

        $rows = [];
        $rows[] = ['Nama', 'Email', 'Telepon', 'Lowongan', 'Status', 'Konfirmasi Wawancara', 'Tanggal Melamar', 'Jadwal Interview', 'Tipe', 'Lokasi/Link', 'Pendidikan'];

        foreach ($applications as $app) {
            $rows[] = [
                $app->user?->name ?? '(akun dihapus)',
                $app->user?->email ?? '-',
                $app->user?->phone ?? '-',
                $app->job?->title ?? '(lowongan dihapus)',
                \App\Support\Label::applicationStatus($app->status),
                Application::interviewStatusLabel($app->interview_status),
                optional($app->created_at)->format('d/m/Y H:i'),
                optional($app->interview_date)->format('d/m/Y H:i'),
                $app->interview_type === 'online' ? 'Online' : ($app->interview_type === 'offline' ? 'Offline' : '-'),
                $app->interview_type === 'online' ? ($app->interview_link ?? '-') : ($app->interview_location ?? '-'),
                $app->user?->education_history ?? '-',
            ];
        }

        $csv = "\xEF\xBB\xBF"; // UTF-8 BOM agar rapi dibuka di Excel Indonesia
        foreach ($rows as $row) {
            $escaped = array_map(function ($value) {
                $value = (string) ($value ?? '');
                $value = str_replace('"', '""', $value);
                return '"' . $value . '"';
            }, $row);
            $csv .= implode(',', $escaped) . "\r\n";
        }

        $filename = 'pelamar-' . now()->format('YmdHis') . '.csv';

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application);

        // P5.7: pelamar yang akunnya sudah dihapus (soft-delete) tidak lagi
        // memiliki relasi user; blade mengakses $application->user langsung,
        // jadi perlakukan sebagai resource yang tidak tersedia (404), bukan 500.
        abort_unless($application->user, 404, 'Data pelamar tidak tersedia.');

        $application->load([
            'job.company',
            'user',
            'user.skills',
            'user.cvFiles',
            'user.certificates',
            'user.documents',
        ]);

        return view('company.applicants.show', compact('application'));
    }

    /**
     * WA-masking: ungkap nomor HP pelamar (JSON, tanpa reload).
     * Owner-only (policy view) + hanya saat interviewed/accepted.
     * GET read-only sehingga aman tanpa CSRF (pola notifications/poll).
     */
    public function contact(Application $application)
    {
        $this->authorize('view', $application);

        abort_unless($application->contactRevealable(), 403, 'Kontak tersedia setelah wawancara dijadwalkan.');
        abort_unless($application->user?->phone, 404, 'Pelamar belum mengisi nomor HP.');

        return response()->json([
            'masked' => \App\Support\Mask::phone($application->user->phone),
            'wa_url' => \App\Support\Mask::waLink($application->user->phone),
        ]);
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validated();

        // H3: tolak transisi tak valid (final immutable, tanpa lompatan).
        if (! \App\Models\Application::canTransition($application->status, $validated['status'])) {
            return back()->with('error', 'Transisi status dari "' . $application->status . '" ke "' . $validated['status'] . '" tidak diperbolehkan.');
        }

        $oldStatus = $application->status;
        $application->status = $validated['status'];

        if ($validated['status'] === 'interviewed') {
            $application->interview_date = $validated['interview_date'] ? $validated['interview_date'] . ' ' . $validated['interview_time'] . ':00' : null;
            $application->interview_type = $validated['interview_type'];
            $application->interview_link = $validated['interview_link'] ?? null;
            $application->interview_location = $validated['interview_location'] ?? null;
            $application->interview_notes = $validated['interview_notes'] ?? null;
            // D2: jadwal (baru/reschedule) selalu reset ke menunggu konfirmasi.
            $application->interview_status = 'menunggu';
        } else {
            $application->interview_date = null;
            $application->interview_type = null;
            $application->interview_link = null;
            $application->interview_location = null;
            $application->interview_notes = null;
            // D2: riwayat wawancara selesai; interview_status dipertahankan
            // sebagai penanda (bukan null) agar bisa direkap.
            $application->interview_status = $application->interview_status ? 'selesai' : null;
        }

        $application->save();

        $notifyFailed = false;
        if ($oldStatus !== $application->status) {
            // P5.7: null-safe — lewati notifikasi bila akun pelamar sudah
            // dihapus (relasi user null), status tetap diperbarui.
            // Sinkron + try/catch: kegagalan email tidak boleh
            // menggagalkan update status yang sudah tersimpan.
            try {
                if ($application->status === 'interviewed') {
                    $application->user?->notify(new \App\Notifications\InterviewScheduled($application));
                } else {
                    $application->user?->notify(new \App\Notifications\ApplicationStatusUpdated($application));
                }
            } catch (\Throwable $e) {
                $notifyFailed = true;
                \Illuminate\Support\Facades\Log::warning('Notifikasi status lamaran #' . $application->id . ' gagal dikirim: ' . $e->getMessage());
            }
        }

        $redirect = redirect()->back()->with('success', 'Status lamaran berhasil diperbarui. Notifikasi web + email terkirim ke pelamar.');

        if ($notifyFailed) {
            $redirect->with('warning', 'Status tersimpan, tetapi email notifikasi gagal terkirim (cek konfigurasi email). Notifikasi web tetap masuk.');
        }

        return $redirect;
    }
}
