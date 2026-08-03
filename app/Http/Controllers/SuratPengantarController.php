<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPengantarController extends Controller
{
    public function download(Application $application)
    {
        // Pastikan hanya siswa yang bersangkutan atau admin yang bisa download
        if (auth()->id() !== $application->user_id && !auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Load relasi yang dibutuhkan
        $application->load(['user.student', 'job.company']);

        $pdf = Pdf::loadView('pdf.surat-pengantar', compact('application'));
        
        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Surat_Pengantar_' . str_replace(' ', '_', $application->user->name) . '.pdf';
        
        return $pdf->download($filename);
    }
}
