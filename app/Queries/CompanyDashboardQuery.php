<?php

namespace App\Queries;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;

class CompanyDashboardQuery
{
    /**
     * P4.1: pindahan verbatim DashboardController::companyDashboard().
     * Filtering company, rumus, dan key array IDENTIK. Company tetap
     * hanya menerima data miliknya (tidak ada perubahan Policy).
     */
    public function get(User $user): array
    {
        $company = $user->company;

        $companyJobsQuery = Job::where('company_id', $company?->id);
        $companyApplicationsQuery = Application::whereHas('job', function ($query) use ($company) {
            $query->where('company_id', $company?->id);
        });

        $verificationPercent = 0;
        if ($company) {
            if ($company->is_verified) {
                $verificationPercent = 100;
            } elseif ($company->verification_status === "rejected") {
                $verificationPercent = 15;
            } elseif ($company->verification_status === "pending") {
                $verificationPercent = 45;
            }
        }

        $totalApplications = $companyApplicationsQuery->count();
        $processedApplications = (clone $companyApplicationsQuery)
            ->whereIn("status", ["interviewed", "accepted", "rejected"])
            ->count();
        $recruitmentProgress = $totalApplications > 0
            ? (int) round(($processedApplications / $totalApplications) * 100)
            : 0;

        $stats = [
            "active_jobs" => (clone $companyJobsQuery)
                ->where("status", "active")
                ->count(),
            "total_applications" => $totalApplications,
            "pending_applications" => (clone $companyApplicationsQuery)->whereIn("status", ["submitted", "under_review"])->count(),
            "accepted_applications" => (clone $companyApplicationsQuery)->where("status", "accepted")->count(),
            "recruitment_progress" => $recruitmentProgress,
            "verification_percent" => $verificationPercent,
            "company_status" => $company && $company->is_verified ? "Terverifikasi" : "Menunggu verifikasi",
            "verification_note" => $company && $company->is_verified
                ? "Akun perusahaan sudah aktif untuk rekrutmen."
                : "Lengkapi verifikasi agar lebih dipercaya kandidat.",
        ];

        $recentApplications = Application::with(["user", "job"])
            ->whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company?->id);
            })
            ->latest()
            ->take(6)
            ->get();

        $recentJobs = Job::withCount('applications')
            ->where('company_id', $company?->id)
            ->latest()
            ->take(5)
            ->get();

        return compact("stats", "recentApplications", "recentJobs", "company");
    }
}
