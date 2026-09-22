<?php

namespace App\Queries;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboardQuery
{
    /**
     * P4.1: pindahan verbatim DashboardController::adminDashboard().
     * Query, rumus growth, dan key array IDENTIK.
     */
    public function get(): array
    {
        $stats = [
            "total_umum" => User::where("role", "umum")->count(),
            "total_jobs" => Job::where("status", "active")->count(),
            "total_applications" => Application::count(),
            "pending_applications" => Application::where(
                "status",
                "submitted",
            )->count(),
            "accepted_applications" => Application::where(
                "status",
                "accepted",
            )->count(),
            "interviews_scheduled" => Application::where(
                "status",
                "interviewed",
            )->count(),
        ];

        // Hitung pertumbuhan bulan ini vs bulan lalu
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        $lastMonthEnd = now()->subMonth()->endOfMonth();

        $umumThisMonth = User::where("role", "umum")
            ->where("created_at", ">=", $thisMonth)
            ->count();
        $umumLastMonth = User::where("role", "umum")
            ->whereBetween("created_at", [$lastMonth, $lastMonthEnd])
            ->count();

        $jobsThisMonth = Job::where("created_at", ">=", $thisMonth)->count();
        $jobsLastMonth = Job::whereBetween("created_at", [
            $lastMonth,
            $lastMonthEnd,
        ])->count();

        $growth = [
            "users" =>
                $umumLastMonth > 0
                    ? round(
                        (($umumThisMonth - $umumLastMonth) /
                            $umumLastMonth) *
                            100,
                        1,
                    )
                    : ($umumThisMonth > 0
                        ? 100
                        : 0),
            "jobs_new" => $jobsThisMonth,
        ];

        // Chart data - Aplikasi per bulan (6 bulan terakhir)
        $applicationChart = Application::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw("COUNT(*) as count"),
        )
            ->where("created_at", ">=", now()->subMonths(6))
            ->groupBy("month")
            ->orderBy("month")
            ->get();

        // Job posting trends
        $jobChart = Job::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw("COUNT(*) as count"),
        )
            ->where("created_at", ">=", now()->subMonths(6))
            ->groupBy("month")
            ->orderBy("month")
            ->get();

        // Application status distribution (Doughnut Chart)
        $applicationStatusChart = Application::select(
            "status",
            DB::raw("COUNT(*) as count"),
        )
            ->groupBy("status")
            ->get();

        // User role distribution (Pie Chart)
        $userRoleChart = User::select(
            "role",
            DB::raw("COUNT(*) as count"),
        )
            ->where("role", "umum")
            ->groupBy("role")
            ->get();

        // Recent activities
        $recentApplications = Application::with(["user", "job"])
            ->latest()
            ->take(10)
            ->get();

        return compact(
            "stats",
            "applicationChart",
            "jobChart",
            "applicationStatusChart",
            "userRoleChart",
            "recentApplications",
            "growth",
        );
    }
}
