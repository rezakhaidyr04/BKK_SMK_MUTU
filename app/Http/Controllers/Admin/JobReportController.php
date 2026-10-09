<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobReport;
use Illuminate\Http\Request;

class JobReportController extends Controller
{
    public function index(Request $request)
    {
        $query = JobReport::with(['job', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = JobReport::menunggu()->count();

        return view('admin.job-reports.index', compact('reports', 'pendingCount'));
    }

    public function show(JobReport $jobReport)
    {
        $jobReport->load(['job.company', 'user']);

        // Laporan lain untuk lowongan yang sama (konteks: 1 vs banyak pelapor).
        $siblingCount = JobReport::where('job_id', $jobReport->job_id)
            ->where('id', '!=', $jobReport->id)
            ->count();

        return view('admin.job-reports.show', ['report' => $jobReport, 'siblingCount' => $siblingCount]);
    }

    /**
     * Terbukti bermasalah: tutup lowongan + tandai laporan ditindak.
     */
    public function close(JobReport $jobReport)
    {
        $jobReport->job?->update(['status' => 'closed']);
        $jobReport->update(['status' => 'ditindak']);

        return redirect()->route('admin.job-reports.index')
            ->with('success', 'Lowongan ditutup dan laporan ditandai ditindak.');
    }

    /**
     * Tidak terbukti: tolak laporan, lowongan tetap tayang.
     */
    public function dismiss(JobReport $jobReport)
    {
        $jobReport->update(['status' => 'ditolak']);

        return back()->with('success', 'Laporan ditolak. Lowongan tetap tayang.');
    }

    public function destroy(JobReport $jobReport)
    {
        $jobReport->delete();

        return redirect()->route('admin.job-reports.index')
            ->with('success', 'Laporan dihapus.');
    }
}
