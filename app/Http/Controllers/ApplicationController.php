<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['job.company'])
            ->where('user_id', Auth::id())
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by job title / company name / location
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('job', function ($j) use ($q) {
                    $j->where('title', 'like', "%{$q}%")
                        ->orWhere('company_name', 'like', "%{$q}%")
                        ->orWhere('location', 'like', "%{$q}%");
                })->orWhereHas('job.company', function ($c) use ($q) {
                    $c->where('name', 'like', "%{$q}%");
                });
            });
        }

        $applications = $query->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total'        => Application::where('user_id', Auth::id())->count(),
            'submitted'    => Application::where('user_id', Auth::id())->submitted()->count(),
            'under_review' => Application::where('user_id', Auth::id())->underReview()->count(),
            'interviewed'  => Application::where('user_id', Auth::id())->interviewed()->count(),
            'accepted'     => Application::where('user_id', Auth::id())->accepted()->count(),
            'rejected'     => Application::where('user_id', Auth::id())->rejected()->count(),
        ];

        return view('applications.index', compact('applications', 'stats'));
    }

    public function show(Application $application)
    {
        $application->load(['job.company']);
        $this->authorize('view', $application);

        // P5.7: blade mengakses $application->user langsung; bila akun pelamar
        // sudah dihapus (relasi null, mis. dilihat company/admin), 404 bukan 500.
        abort_unless($application->user, 404, 'Data pelamar tidak tersedia.');

        // D2: tempel status konfirmasi ke label wawancara agar langsung terbaca.
        $interviewSuffix = '';
        if ($application->status === 'interviewed') {
            $interviewSuffix = ' · ' . \App\Models\Application::interviewStatusLabel($application->interview_status);
        }

        // Timeline for status tracking
        $timeline = [
            [
                'status'    => 'submitted',
                'label'     => 'Lamaran Dikirim',
                'icon'      => 'document',
                'completed' => true,
                'date'      => $application->created_at,
            ],
            [
                'status'    => 'under_review',
                'label'     => 'Sedang Ditinjau',
                'icon'      => 'eye',
                'completed' => in_array($application->status, ['under_review', 'interviewed', 'accepted']),
                'date'      => null,
            ],
            [
                'status'    => 'interviewed',
                'label'     => 'Wawancara' . ($application->interview_date ? ' — ' . $application->interview_date->format('d M Y, H:i') : '') . $interviewSuffix,
                'icon'      => 'calendar',
                'completed' => in_array($application->status, ['interviewed', 'accepted']),
                'date'      => $application->interview_date,
            ],
            [
                'status'      => 'accepted',
                'label'       => $application->status === 'rejected' ? 'Ditolak' : 'Diterima',
                'icon'        => $application->status === 'rejected' ? 'x' : 'check',
                'completed'   => in_array($application->status, ['accepted', 'rejected']),
                'date'        => null,
                'is_final'    => true,
                'is_rejected' => $application->status === 'rejected',
            ],
        ];

        return view('applications.show', compact('application', 'timeline'));
    }

        /**
     * D2: konfirmasi kehadiran wawancara oleh pelamar pemilik.
     * Hanya saat status=interviewed; boleh ganti pikiran (hadir ↔ berhalangan).
     */
    public function confirmInterview(Request $request, Application $application)
    {
        abort_unless($application->user_id === Auth::id() && Auth::user()->role === 'umum', 403);
        abort_unless($application->status === 'interviewed', 403, 'Hanya wawancara aktif yang bisa dikonfirmasi.');

        $validated = $request->validate([
            'response' => ['required', 'in:hadir,berhalangan'],
        ]);

        $to = $validated['response'] === 'hadir' ? 'dikonfirmasi' : 'ditolak';

        if (! Application::canConfirmInterview($application->interview_status, $to)) {
            return back()->with('error', 'Konfirmasi tidak valid untuk status saat ini.');
        }

        $application->interview_status = $to;
        $application->save();

        // Beri tahu perusahaan pemilik (sinkron + try/catch: notifikasi
        // gagal tidak boleh menggagalkan konfirmasi yang sudah tersimpan).
        try {
            $application->loadMissing('job.company.user');
            $application->job?->company?->user?->notify(new \App\Notifications\InterviewResponded($application));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Notifikasi konfirmasi wawancara #' . $application->id . ' gagal dikirim: ' . $e->getMessage());
        }

        return back()->with('success', $to === 'dikonfirmasi'
            ? 'Kehadiran dikonfirmasi. Sampai jumpa di wawancara!'
            : 'Jawaban tercatat. Perusahaan akan dihubungi untuk penjadwalan ulang bila memungkinkan.');
    }

    public function destroy(Application $application)
    {
        abort_unless($application->user_id === Auth::id(), 403);
        // Can only withdraw if not yet accepted/rejected
        if (in_array($application->status, ['accepted', 'rejected'])) {
            return back()->with('error', 'Lamaran ini tidak dapat ditarik.');
        }

        // H2: file DIPERTAHANKAN (soft-delete hanya menandai deleted_at).
        // Alasan: re-apply memakai restore() + riwayat created_at utuh;
        // hapus permanen hanya saat baris benar-benar forceDelete.
        // File lama diganti/dibersihkan secara aman saat re-apply sukses.
        $application->delete();

        return redirect()->route('applications.index')
            ->with('success', 'Lamaran berhasil ditarik.');
    }

    public function downloadAttachment(Application $application, \Illuminate\Http\Request $request)
    {
        $this->authorize('downloadAttachment', $application);

        abort_unless($application->attachment_path, 404);

        abort_unless(Storage::disk('private')->exists($application->attachment_path), 404);

        $filename = $application->attachment_name ?: basename($application->attachment_path);

        // ?preview=1 → tampilkan inline (PDF/gambar bisa preview di browser),
        // default tetap download agar file tersimpan dengan nama asli.
        if ($request->query('preview')) {
            $mime = $application->attachment_mime
                ?: Storage::disk('private')->mimeType($application->attachment_path);

            return Storage::disk('private')->response(
                $application->attachment_path,
                $filename,
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . \App\Support\Mask::filename($filename) . '"',
                ]
            );
        }

        return Storage::disk('private')->download(
            $application->attachment_path,
            $filename
        );
    }

    public function downloadCoverLetter(Application $application, \Illuminate\Http\Request $request)
    {
        $this->authorize('downloadAttachment', $application);

        abort_unless($application->cover_letter_path, 404);

        abort_unless(Storage::disk('private')->exists($application->cover_letter_path), 404);

        $filename = $application->cover_letter_name ?: basename($application->cover_letter_path);

        if ($request->query('preview')) {
            $mime = $application->cover_letter_mime
                ?: Storage::disk('private')->mimeType($application->cover_letter_path);

            return Storage::disk('private')->response(
                $application->cover_letter_path,
                $filename,
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . \App\Support\Mask::filename($filename) . '"',
                ]
            );
        }

        return Storage::disk('private')->download(
            $application->cover_letter_path,
            $filename
        );
    }

    public function downloadSkck(Application $application, \Illuminate\Http\Request $request)
    {
        $this->authorize('downloadAttachment', $application);

        abort_unless($application->skck_path, 404);

        abort_unless(Storage::disk('private')->exists($application->skck_path), 404);

        $filename = $application->skck_name ?: basename($application->skck_path);

        if ($request->query('preview')) {
            $mime = $application->skck_mime
                ?: Storage::disk('private')->mimeType($application->skck_path);

            return Storage::disk('private')->response(
                $application->skck_path,
                $filename,
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . \App\Support\Mask::filename($filename) . '"',
                ]
            );
        }

        return Storage::disk('private')->download(
            $application->skck_path,
            $filename
        );
    }
}
