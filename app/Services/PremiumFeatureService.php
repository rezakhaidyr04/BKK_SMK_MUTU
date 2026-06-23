<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use Illuminate\Support\Facades\DB;

class PremiumFeatureService
{
    // Catatan: Untuk pencocokan lowongan dengan user, gunakan JobMatchingService
    // yang menggunakan algoritma Jaccard similarity dengan 5 faktor berbobot.

    /**
     * Analitik admin untuk laporan ringkasan.
     */
    public function getAdminAnalytics(): array
    {
        $totalStudents    = User::where('role', 'student')->count();
        $totalAlumni      = User::where('role', 'alumni')->count();
        $totalCompanies   = User::where('role', 'company')->count();
        $totalJobs        = Job::count();
        $totalApplications = Application::count();

        $monthlyApplications = Application::select(
                DB::raw('COUNT(id) as count'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'totals' => [
                'students'     => $totalStudents,
                'alumni'       => $totalAlumni,
                'companies'    => $totalCompanies,
                'jobs'         => $totalJobs,
                'applications' => $totalApplications,
            ],
            'charts' => [
                'monthly_applications' => $monthlyApplications,
            ],
        ];
    }
}