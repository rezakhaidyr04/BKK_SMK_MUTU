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
        $user->load(['student', 'skills', 'certificates']);

        $previewData = $this->buildPreviewData($user);
        
        $cvFiles = CvFile::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cv.builder', compact('user', 'cvFiles', 'previewData'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'template' => 'required|in:modern,classic,professional',
            'include_photo' => 'boolean',
            'include_skills' => 'boolean',
            'include_certificates' => 'boolean',
            'custom_headline' => 'nullable|string|max:120',
            'custom_summary' => 'nullable|string|max:1200',
            'custom_experience' => 'nullable|string|max:2000',
            'custom_achievement' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $user->load(['student', 'skills', 'cvFiles', 'certificates']);

        $data = [
            'user' => $user,
            'include_photo' => (bool) $request->input('include_photo', false),
            'include_skills' => (bool) $request->input('include_skills', true),
            'include_certificates' => (bool) $request->input('include_certificates', false),
            'custom_headline' => trim((string) $request->input('custom_headline', '')),
            'custom_summary' => trim((string) $request->input('custom_summary', '')),
            'custom_experience' => trim((string) $request->input('custom_experience', '')),
            'custom_achievement' => trim((string) $request->input('custom_achievement', '')),
        ];

        $fileName = 'cv/generated-cv-' . Auth::id() . '-' . time() . '.pdf';

        // Render PDF from blade template. Expect package barryvdh/laravel-dompdf installed.
        $pdf = PDF::loadView('cv.templates.' . $request->input('template'), $data)
            ->setPaper('a4', 'portrait');

        Storage::disk('public')->put($fileName, $pdf->output());

        $cvFile = CvFile::create([
            'user_id' => Auth::id(),
            'file_path' => $fileName,
            'is_ats_friendly' => $request->input('template') === 'classic' ? true : false,
        ]);

        return back()->with('success', 'CV berhasil dibuat dan disimpan.')->with('cvFileId', $cvFile->id);
    }

    public function download(CvFile $cvFile)
    {
        // Authorization check
        if ($cvFile->user_id !== Auth::id()) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($cvFile->file_path)) {
            return back()->with('error', 'File CV tidak ditemukan.');
        }

        return Storage::disk('public')->download($cvFile->file_path);
    }

    private function buildPreviewData($user): array
    {
        $skills = $user->skills->pluck('name')->filter()->values()->all();
        $certificates = $user->certificates->pluck('name')->filter()->values()->all();

        return [
            'name' => $user->name,
            'headline' => $user->student?->major ?: 'Pencari kerja siap berkembang',
            'summary' => $user->bio ?: 'Ringkasan belum diisi. Gunakan area ini untuk memperkenalkan diri, keahlian utama, dan target karir yang kamu kejar.',
            'phone' => $user->phone ?: '08xxxxxxxxxx',
            'email' => $user->email,
            'address' => $user->student?->address ?: 'Cikampek, Jawa Barat',
            'education' => [
                'school' => 'SMK MUTU Cikampek',
                'major' => $user->student?->major ?: 'Jurusan belum diisi',
                'year' => $user->student?->graduation_year ?: 'Tahun lulus belum diisi',
            ],
            'skills' => !empty($skills) ? $skills : [
                'Komunikasi',
                'Kerja tim',
                'Microsoft Office',
                'Problem solving',
            ],
            'experience' => $user->student?->experience ?: "- Magang atau proyek sekolah\n- Kegiatan organisasi\n- Tugas atau pencapaian relevan",
            'certificates' => !empty($certificates) ? $certificates : [
                'Belum ada sertifikat ditambahkan',
            ],
        ];
    }
}
