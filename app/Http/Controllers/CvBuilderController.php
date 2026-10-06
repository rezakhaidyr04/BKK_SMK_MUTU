<?php

namespace App\Http\Controllers;

use App\Models\CvFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class CvBuilderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load(['skills', 'certificates']);

        $previewData = $this->buildPreviewData($user);

        $cvFiles = CvFile::where('user_id', Auth::id())
            ->latest()
            ->get();

        // M9: status async untuk banner persistent (bukan hanya session flash).
        $cvGenerating = (bool) \Illuminate\Support\Facades\Cache::get(
            \App\Services\CvBuilderService::generatingKey(Auth::id())
        );
        $cvFailed = (bool) \Illuminate\Support\Facades\Cache::get(
            \App\Services\CvBuilderService::failedKey(Auth::id())
        );

        return view('cv.builder', compact('user', 'cvFiles', 'previewData', 'cvGenerating', 'cvFailed'));
    }

    public function generate(\App\Http\Requests\CvGenerateRequest $request, \App\Services\CvBuilderService $cvService)
    {
        // M9: async — request hanya dispatch, DomPDF jalan di worker.
        $status = $cvService->generateCv($request->validated());

        if ($status === 'duplicate') {
            return back()->with('info', 'CV Anda sedang diproses. Tunggu sebentar lalu refresh halaman ini.');
        }

        return back()->with('success', 'CV sedang diproses di background. Halaman akan dimuat ulang otomatis.');
    }

    public function download(CvFile $cvFile, \Illuminate\Http\Request $request)
    {
        $this->authorize('view', $cvFile);

        if (!Storage::disk('private')->exists($cvFile->file_path)) {
            return back()->with('error', 'File CV tidak ditemukan.');
        }

        if ($request->query('preview')) {
            $mime = Storage::disk('private')->mimeType($cvFile->file_path);

            return Storage::disk('private')->response(
                $cvFile->file_path,
                basename($cvFile->file_path),
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . addslashes(basename($cvFile->file_path)) . '"',
                ]
            );
        }

        return Storage::disk('private')->download($cvFile->file_path);
    }

    public function destroy(CvFile $cvFile)
    {
        $this->authorize('view', $cvFile);

        if (Storage::disk('private')->exists($cvFile->file_path)) {
            Storage::disk('private')->delete($cvFile->file_path);
        }
        
        $cvFile->delete();

        return back()->with('success', 'CV berhasil dihapus.');
    }

    private function buildPreviewData($user): array
    {
        $skills = $user->skills->pluck('name')->filter()->values()->all();
        // L3: kolom sertifikat adalah `title` (bukan `name`).
        $certificates = $user->certificates->pluck('title')->filter()->values()->all();

        return [
            'name' => $user->name,
            'headline' => $user->preferred_position ?: 'Pencari kerja siap berkembang',
            'summary' => $user->bio ?: 'Ringkasan belum diisi. Gunakan area ini untuk memperkenalkan diri, keahlian utama, dan target karir yang kamu kejar.',
            'phone' => $user->phone ?: '08xxxxxxxxxx',
            'email' => $user->email,
            'address' => $user->address ?: 'Cikampek, Jawa Barat',
            'linkedin_url' => $user->linkedin_url ?? null,
            'portfolio_url' => $user->portfolio_url ?? null,
            'preferred_position' => $user->preferred_position ?: 'Posisi yang diinginkan belum diisi',
            'target_position' => $user->preferred_position ?: '',
            'ats_keywords' => 'Frontend Development, HTML, CSS, JavaScript, Responsive Web Design, UI/UX, Git, Problem Solving, Teamwork, Leadership',
            'achievement' => 'Memimpin kegiatan organisasi dan mengoordinasikan komunikasi publik untuk mendukung kolaborasi serta kelancaran program kerja.',
            'education' => [
                'school' => $user->education_history ? 'Lihat riwayat pendidikan' : 'Sekolah atau pendidikan terakhir belum diisi',
                'major' => 'Bidang studi atau keahlian belum diisi',
                'year' => null,
                'history' => $user->education_history ?: 'Riwayat pendidikan belum diisi',
            ],
            'skills' => !empty($skills) ? $skills : [
                'Komunikasi',
                'Kerja tim',
                'Microsoft Office',
                'Problem solving',
            ],
            'experience' => $user->experience_organization ?: "- Magang atau proyek kerja\n- Kegiatan organisasi\n- Tugas atau pencapaian relevan",
            'certificates' => !empty($certificates) ? $certificates : [
                'Belum ada sertifikat ditambahkan',
            ],
        ];
    }
}
