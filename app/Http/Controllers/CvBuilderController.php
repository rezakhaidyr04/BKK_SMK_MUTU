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
        
        $cvFiles = CvFile::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('cv.builder', compact('user', 'cvFiles'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'template' => 'required|in:modern,classic,professional',
            'include_photo' => 'boolean',
            'include_skills' => 'boolean',
            'include_certificates' => 'boolean',
        ]);

        $user = Auth::user();
        $user->load(['student', 'skills', 'cvFiles', 'certificates']);

        $data = [
            'user' => $user,
            'include_photo' => (bool) $request->input('include_photo', false),
            'include_skills' => (bool) $request->input('include_skills', true),
            'include_certificates' => (bool) $request->input('include_certificates', false),
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
            return back()->with('error', 'CV file not found.');
        }

        return Storage::disk('public')->download($cvFile->file_path);
    }
}
