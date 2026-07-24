<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\Application;
use App\Support\Label;
use App\Models\Event;
use App\Models\Bookmark;
use App\Models\Message;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $user = Auth::user();

        switch ($user->role) {
            case "admin":
                return $this->adminDashboard();
            case "student":
            case "alumni":
                return $this->studentAlumniDashboard();
            case "company":
                return $this->companyDashboard();
            case "teacher":
                return $this->teacherDashboard();
            default:
                // Default to student dashboard for unknown roles
                return $this->studentAlumniDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            "total_students" => User::where("role", "student")->count(),
            "total_alumni" => User::where("role", "alumni")->count(),
            "total_companies" => User::where("role", "company")->count(),
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

        $studentsThisMonth = User::where("role", "student")
            ->where("created_at", ">=", $thisMonth)
            ->count();
        $studentsLastMonth = User::where("role", "student")
            ->whereBetween("created_at", [$lastMonth, $lastMonthEnd])
            ->count();

        $alumniThisMonth = User::where("role", "alumni")
            ->where("created_at", ">=", $thisMonth)
            ->count();
        $alumniLastMonth = User::where("role", "alumni")
            ->whereBetween("created_at", [$lastMonth, $lastMonthEnd])
            ->count();

        $companiesThisMonth = Company::where(
            "created_at",
            ">=",
            $thisMonth,
        )->count();
        $companiesLastMonth = Company::whereBetween("created_at", [
            $lastMonth,
            $lastMonthEnd,
        ])->count();

        $jobsThisMonth = Job::where("created_at", ">=", $thisMonth)->count();
        $jobsLastMonth = Job::whereBetween("created_at", [
            $lastMonth,
            $lastMonthEnd,
        ])->count();

        $growth = [
            "students" =>
                $studentsLastMonth > 0
                    ? round(
                        (($studentsThisMonth - $studentsLastMonth) /
                            $studentsLastMonth) *
                            100,
                        1,
                    )
                    : ($studentsThisMonth > 0
                        ? 100
                        : 0),
            "alumni" =>
                $alumniLastMonth > 0
                    ? round(
                        (($alumniThisMonth - $alumniLastMonth) /
                            $alumniLastMonth) *
                            100,
                        1,
                    )
                    : ($alumniThisMonth > 0
                        ? 100
                        : 0),
            "companies_new" => $companiesThisMonth,
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
            ->whereIn("role", ["student", "alumni", "company"])
            ->groupBy("role")
            ->get();

        // Recent activities
        $recentApplications = Application::with(["user", "job.company"])
            ->latest()
            ->take(10)
            ->get();

        // Top companies
        $topCompanies = DB::table("companies")
            ->join("users", "companies.user_id", "=", "users.id")
            ->join("jobs", "companies.id", "=", "jobs.company_id")
            ->select(
                "companies.*",
                "users.name",
                DB::raw("COUNT(jobs.id) as job_count"),
            )
            ->groupBy("companies.id")
            ->orderByDesc("job_count")
            ->take(5)
            ->get();

        return view(
            "dashboard.admin",
            compact(
                "stats",
                "applicationChart",
                "jobChart",
                "applicationStatusChart",
                "userRoleChart",
                "recentApplications",
                "topCompanies",
                "growth",
            ),
        );
    }

    private function studentAlumniDashboard()
    {
        $user = Auth::user();

        $stats = [
            "active_applications" => Application::where("user_id", $user->id)
                ->whereIn("status", ["submitted", "under_review"])
                ->count(),
            "interview_count" => Application::where("user_id", $user->id)
                ->where("status", "interviewed")
                ->count(),
            "accepted_count" => Application::where("user_id", $user->id)
                ->where("status", "accepted")
                ->count(),
            "bookmarked_jobs" => Bookmark::where("user_id", $user->id)->count(),
            "profile_completion" => $this->calculateProfileCompletion($user),
        ];

        // Job recommendations based on user skills
        $userSkills = $user->skills()->pluck("skills.id")->toArray();

        $recommendedJobs = Job::with(["company.user"])
            ->where("status", "active")
            ->where("deadline", ">=", now())
            ->whereHas("company", function ($q) {
                $q->where("is_verified", true);
            })
            ->whereDoesntHave("applications", function ($query) use ($user) {
                $query->where("user_id", $user->id);
            })
            ->latest()
            ->take(30)
            ->get();

        // Hitung match score untuk setiap job menggunakan JobMatchingService
        $matchingService = new \App\Services\JobMatchingService();
        foreach ($recommendedJobs as $job) {
            $job->match_score = $matchingService->score($job, $user);
        }

        // Urutkan berdasarkan match_score desc dan ambil top 6
        $recommendedJobs = $recommendedJobs
            ->sortByDesc("match_score")
            ->values()
            ->take(6);

        // Recent applications with timeline
        $myApplications = Application::with(["job.company.user"])
            ->where("user_id", $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Upcoming events
        $upcomingEvents = Event::where("start_time", ">=", now())
            ->orderBy("start_time")
            ->take(4)
            ->get();

        // Activity timeline
        $activities = $this->getUserActivityTimeline($user);

        // Unread messages count
        $unreadMessages = Message::whereHas("conversation.users", function (
            $query,
        ) use ($user) {
            $query->where("user_id", $user->id);
        })
            ->where("sender_id", "!=", $user->id)
            ->where("is_read", false)
            ->count();

        $stats["unread_messages"] = $unreadMessages;

        return view(
            "dashboard.student",
            compact(
                "stats",
                "recommendedJobs",
                "myApplications",
                "upcomingEvents",
                "activities",
            ),
        );
    }

    private function companyDashboard()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()
                ->route("company.profile.edit")
                ->with(
                    "error",
                    "Silakan lengkapi profil perusahaan Anda terlebih dahulu.",
                );
        }

        $stats = [
            "active_jobs" => Job::where("company_id", $company->id)
                ->where("status", "active")
                ->count(),
            "total_applicants" => Application::whereHas("job", function (
                $query,
            ) use ($company) {
                $query->where("company_id", $company->id);
            })->count(),
            "new_applicants" => Application::whereHas("job", function (
                $query,
            ) use ($company) {
                $query->where("company_id", $company->id);
            })
                ->where("status", "submitted")
                ->count(),
            "scheduled_interviews" => Application::whereHas("job", function (
                $query,
            ) use ($company) {
                $query->where("company_id", $company->id);
            })
                ->where("status", "interviewed")
                ->count(),
        ];

        // Recent applications
        $recentApplicants = Application::with(["user", "job"])
            ->whereHas("job", function ($query) use ($company) {
                $query->where("company_id", $company->id);
            })
            ->latest()
            ->take(10)
            ->get();

        // Job performance
        $jobPerformance = Job::where("company_id", $company->id)
            ->withCount("applications")
            ->orderByDesc("applications_count")
            ->take(5)
            ->get();

        // Application trends
        $applicationTrends = Application::whereHas("job", function (
            $query,
        ) use ($company) {
            $query->where("company_id", $company->id);
        })
            ->select(
                DB::raw("DATE(created_at) as date"),
                DB::raw("COUNT(*) as count"),
            )
            ->where("created_at", ">=", now()->subDays(30))
            ->groupBy("date")
            ->orderBy("date")
            ->get();

        return view(
            "dashboard.company",
            compact(
                "stats",
                "recentApplicants",
                "jobPerformance",
                "applicationTrends",
                "company",
            ),
        );
    }

    private function teacherDashboard()
    {
        $stats = [
            "total_students" => User::where("role", "student")->count(),
            "total_alumni" => User::where("role", "alumni")->count(),
            "placed_students" => Application::where(
                "status",
                "accepted",
            )->count(),
            "active_jobs" => Job::where("status", "active")->count(),
        ];

        // Student placement status
        $placementData = Application::select(
            "status",
            DB::raw("COUNT(*) as count"),
        )
            ->groupBy("status")
            ->get();

        // Recent placements
        $recentPlacements = Application::with(["user", "job.company.user"])
            ->where("status", "accepted")
            ->latest()
            ->take(10)
            ->get();

        return view(
            "dashboard.teacher",
            compact("stats", "placementData", "recentPlacements"),
        );
    }

    private function calculateProfileCompletion($user)
    {
        $completed = 0;
        $total = 0;

        // --- Bagian 1: Data dasar (masing-masing 1 poin) ---
        $basicFields = ["name", "email", "phone", "avatar", "bio"];
        foreach ($basicFields as $field) {
            $total++;
            if (!empty($user->$field)) {
                $completed++;
            }
        }

        // --- Bagian 2: Data akademik siswa (masing-masing 1 poin) ---
        if (in_array($user->role, ["student", "alumni"]) && $user->student) {
            $studentFields = ["major", "graduation_year", "address"];
            foreach ($studentFields as $field) {
                $total++;
                if (!empty($user->student->$field)) {
                    $completed++;
                }
            }
        }

        // --- Bagian 3: Keahlian (2 poin jika ada minimal 1 skill) ---
        $total += 2;
        if ($user->skills()->count() > 0) {
            $completed += 2;
        }

        // --- Bagian 4: CV (2 poin jika ada CV) ---
        $total += 2;
        if ($user->cvFiles()->exists()) {
            $completed += 2;
        }

        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }

    private function getUserActivityTimeline($user)
    {
        $activities = [];

        // Recent applications
        $applications = Application::where("user_id", $user->id)
            ->latest()
            ->take(5)
            ->get();

        foreach ($applications as $app) {
            $activities[] = [
                "type" => "application",
                "title" => "Melamar ke " . $app->job->title,
                "description" =>
                    "Status: " . Label::applicationStatus($app->status),
                "timestamp" => $app->created_at,
                "icon" => "briefcase",
                "color" => $this->getStatusColor($app->status),
            ];
        }

        // Recent bookmarks
        $bookmarks = Bookmark::where("user_id", $user->id)
            ->latest()
            ->take(3)
            ->get();

        foreach ($bookmarks as $bookmark) {
            $activities[] = [
                "type" => "bookmark",
                "title" => "Menyimpan " . $bookmark->job->title,
                "description" =>
                    $bookmark->job->company->name ?? __("bkk.fallback.company"),
                "timestamp" => $bookmark->created_at,
                "icon" => "bookmark",
                "color" => "blue",
            ];
        }

        // Sort by timestamp
        usort($activities, function ($a, $b) {
            return $b["timestamp"] <=> $a["timestamp"];
        });

        return array_slice($activities, 0, 8);
    }

    private function getStatusColor($status)
    {
        return match ($status) {
            "submitted" => "blue",
            "under_review" => "yellow",
            "interviewed" => "purple",
            "accepted" => "green",
            "rejected" => "red",
            default => "gray",
        };
    }
}
