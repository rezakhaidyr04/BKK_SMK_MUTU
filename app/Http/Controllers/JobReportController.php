<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobReportRequest;
use App\Models\Job;
use App\Models\JobReport;
use Illuminate\Support\Facades\Log;

class JobReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    /**
     * Laporkan lowongan — khusus pencari kerja, satu laporan per user per job.
     */
    public function store(StoreJobReportRequest $request, Job $job)
    {
        // Hanya lowongan yang masih tampil publik yang bisa dilaporkan.
        if (! $job->isActive()) {
            return back()->with('error', 'Lowongan ini sudah tidak aktif.');
        }

        if (JobReport::where('job_id', $job->id)->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Anda sudah melaporkan lowongan ini. Tim admin sedang menindaklanjuti.');
        }

        try {
            JobReport::create([
                'job_id' => $job->id,
                'user_id' => auth()->id(),
                'reason' => $request->validated('reason'),
                'detail' => $request->validated('detail'),
                'status' => 'menunggu',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Race (dua submit paralel lolos cek) menabrak unique(job_id,user_id).
            if (($e->errorInfo[1] ?? null) === 1062) {
                return back()->with('error', 'Anda sudah melaporkan lowongan ini. Tim admin sedang menindaklanjuti.');
            }

            Log::error('Job report store failed: '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }

        return back()->with('success', 'Laporan terkirim. Terima kasih — admin akan menindaklanjuti.');
    }
}
