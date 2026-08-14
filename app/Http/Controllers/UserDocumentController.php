<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserDocument;
use Illuminate\Support\Facades\Storage;

class UserDocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ]);

        $file = $request->file('file');
        $path = $file->store('user_documents', 'private');

        UserDocument::create([
            'user_id' => auth()->id(),
            'document_type' => $request->document_type,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(UserDocument $document)
    {
        $this->authorize('view', $document);

        if (Storage::disk('private')->exists($document->file_path)) {
            Storage::disk('private')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }

    public function download(UserDocument $document)
    {
        $this->authorize('view', $document);

        abort_unless(Storage::disk('private')->exists($document->file_path), 404);

        return Storage::disk('private')->download(
            $document->file_path,
            $document->original_name ?: basename($document->file_path)
        );
    }
}
