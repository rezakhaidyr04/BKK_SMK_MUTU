<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'issuer' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'file' => 'required|file|mimes:pdf|mimetypes:application/pdf|max:5120',
        ], [
            'file.mimes' => 'Sertifikat wajib berformat PDF.',
            'file.mimetypes' => 'Sertifikat wajib berformat PDF.',
        ]);

        $filePath = $request->file('file')->store('certificates', 'private');

        Certificate::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'issuer' => $request->issuer,
            'issue_date' => $request->issue_date,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'Sertifikat berhasil diunggah.');
    }

    public function destroy(Certificate $certificate)
    {
        $this->authorize('view', $certificate);

        if (Storage::disk('private')->exists($certificate->file_path)) {
            Storage::disk('private')->delete($certificate->file_path);
        }

        $certificate->delete();

        return back()->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function download(Certificate $certificate, \Illuminate\Http\Request $request)
    {
        $this->authorize('view', $certificate);

        abort_unless(Storage::disk('private')->exists($certificate->file_path), 404);

        if ($request->query('preview')) {
            $mime = Storage::disk('private')->mimeType($certificate->file_path);

            return Storage::disk('private')->response(
                $certificate->file_path,
                basename($certificate->file_path),
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . addslashes(basename($certificate->file_path)) . '"',
                ]
            );
        }

        return Storage::disk('private')->download($certificate->file_path);
    }
}
