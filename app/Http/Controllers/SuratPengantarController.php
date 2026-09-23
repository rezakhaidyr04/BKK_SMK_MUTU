<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratPengantarController extends Controller
{
    public function download(Application $application)
    {
        // Pastikan hanya pemilik lamaran atau admin yang bisa download
        $this->authorize('view', $application);

        // P5.7: pelamar yang akunnya sudah dihapus tidak memiliki relasi user;
        // PDF + filename membutuhkan data user, jadi 404 bukan 500.
        abort_unless($application->user, 404, 'Data pelamar tidak tersedia.');

        // Load relasi yang dibutuhkan
        $application->load(['user', 'job.company']);

        $pdf = Pdf::loadView('pdf.surat-pengantar', compact('application'));

        // Atur ukuran kertas
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Surat_Pengantar_' . str_replace(' ', '_', $application->user->name) . '.pdf';

        return $pdf->download($filename);
    }
}
