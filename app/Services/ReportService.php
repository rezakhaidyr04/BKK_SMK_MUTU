<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Ringkasan statistik laporan — definisi count IDENTIK dengan
     * logika sebelumnya di Admin\ReportController.
     */
    public function summary(): array
    {
        return [
            'total_umum'            => User::where('role', 'umum')->count(),
            'total_jobs'            => Job::count(),
            'active_jobs'           => Job::where('status', 'active')->count(),
            'closed_jobs'           => Job::where('status', 'closed')->count(),
            'total_applications'    => Application::count(),
            'submitted_applications'  => Application::submitted()->count(),
            'accepted_applications'   => Application::accepted()->count(),
            'rejected_applications'   => Application::rejected()->count(),
            'interviewed_applications' => Application::interviewed()->count(),
        ];
    }

    /**
     * Baris metrik [label, nilai] untuk export — urutan & label IDENTIK,
     * nilai diambil dari summary() sehingga konsisten dengan index().
     */
    public function metricRows(): array
    {
        $summary = $this->summary();

        return [
            ['Total Pencari Kerja', $summary['total_umum']],
            ['Total Lowongan',      $summary['total_jobs']],
            ['Lowongan Aktif',      $summary['active_jobs']],
            ['Lowongan Ditutup',    $summary['closed_jobs']],
            ['Total Lamaran',       $summary['total_applications']],
            ['Lamaran Diajukan',    $summary['submitted_applications']],
            ['Lamaran Diterima',    $summary['accepted_applications']],
            ['Lamaran Ditolak',     $summary['rejected_applications']],
            ['Lamaran Diwawancara', $summary['interviewed_applications']],
        ];
    }

    /**
     * Lamaran 6 bulan terakhir untuk chart — hasil IDENTIK dengan loop
     * whereYear()/whereMonth() sebelumnya (6 entri, label 'M Y',
     * urutan kronologis, 0 untuk bulan tanpa data), diambil dalam
     * 1 query + grouping di PHP agar agnostik database.
     *
     * @return \Illuminate\Support\Collection<int, array{label: string, count: int}>
     */
    public function monthlyApplications(): Collection
    {
        $anchors = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));

        $counts = Application::where('created_at', '>=', $anchors->first()->copy()->startOfMonth())
            ->get(['created_at'])
            ->groupBy(fn ($application) => $application->created_at->format('Y-m'))
            ->map->count();

        return $anchors->map(function ($date) use ($counts) {
            return [
                'label' => $date->format('M Y'),
                'count' => $counts->get($date->format('Y-m'), 0),
            ];
        });
    }

    public function recentUsers()
    {
        return User::latest()->take(6)->get();
    }

    public function recentJobs()
    {
        return Job::withCount('applications')->latest()->take(6)->get();
    }
}
