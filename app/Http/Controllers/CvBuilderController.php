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

        return view('cv.builder', compact('user', 'cvFiles', 'previewData'));
    }

    public function generate(\App\Http\Requests\CvGenerateRequest $request, \App\Services\CvBuilderService $cvService)
    {
        $cvService->generateCv($request->validated());

        return back()->with('success', 'CV sedang diproses di latar belakang. Silakan muat ulang halaman ini dalam beberapa saat untuk melihat hasilnya.');
    }

    public function download(CvFile $cvFile)
    {
        $this->authorize('view', $cvFile);

        if (!Storage::disk('private')->exists($cvFile->file_path)) {
            return back()->with('error', 'File CV tidak ditemukan.');
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
        $certificates = $user->certificates->pluck('name')->filter()->values()->all();

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
