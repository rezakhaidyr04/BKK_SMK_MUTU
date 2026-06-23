<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PremiumFeatureService
{
    /**
     * Algoritma Job Match (Basic)
     * Mencocokkan skills user dengan requirement (qualifications) di Job.
     */
    public function getJobMatchesForUser($userId)
    {
        $user = User::with('skills')->findOrFail($userId);
        $userSkillNames = $user->skills->pluck('name')->map(function($item) {
            return strtolower($item);
        })->toArray();

        // Get all active jobs
        $jobs = Job::where('status', 'active')->get();

        $matchedJobs = collect();

        foreach ($jobs as $job) {
            $qualifications = strtolower($job->qualifications);
            $matchCount = 0;

            foreach ($userSkillNames as $skill) {
                if (str_contains($qualifications, $skill)) {
                    $matchCount++;
                }
            }

            // Calculate percentage match
            $percentage = count($userSkillNames) > 0 ? ($matchCount / count($userSkillNames)) * 100 : 0;
            
            if ($percentage > 30) { // minimum 30% match
                $job->match_percentage = round($percentage);
                $matchedJobs->push($job);
            }
        }

        return $matchedJobs->sortByDesc('match_percentage')->values();
    }

    /**
     * Dashboard Analytics (Admin)
     */
    public function getAdminAnalytics()
    {
        $totalStudents = User::role('student')->count();
        $totalAlumni = User::role('alumni')->count();
        $totalCompanies = User::role('company')->count();
        $totalJobs = Job::count();
        $totalApplications = Application::count();

        // Monthly applications chart data
        $monthlyApplications = Application::select(DB::raw('COUNT(id) as count'), DB::raw('MONTH(created_at) as month'))
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'totals' => [
                'students' => $totalStudents,
                'alumni' => $totalAlumni,
                'companies' => $totalCompanies,
                'jobs' => $totalJobs,
                'applications' => $totalApplications,
            ],
            'charts' => [
                'monthly_applications' => $monthlyApplications,
            ]
        ];
    }
}
