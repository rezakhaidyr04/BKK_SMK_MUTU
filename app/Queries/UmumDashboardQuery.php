<?php

namespace App\Queries;

use App\Models\Application;
use App\Models\Bookmark;
use App\Models\Event;
use App\Models\Job;
use App\Models\Message;
use App\Models\User;
use App\Services\JobMatchingService;
use App\Services\ProfileCompletionService;
use App\Support\Label;

class UmumDashboardQuery
{
    public function __construct(
        protected JobMatchingService $matchingService,
        protected ProfileCompletionService $profileCompletion,
    ) {}

    /**
     * P4.1: pindahan DashboardController::umumDashboard().
     * Query, scoring, ordering, dan key array IDENTIK.
     * N+1 fix (data identik, hanya batching query):
     * - recommendedJobs memakai with('company') karena
     *   JobMatchingService::score() membaca $job->company->industry.
     * - timeline memakai with('job') karena mengakses $app/$bookmark->job.
     */
    public function get(User $user): array
    {
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
            "profile_completion" => $this->profileCompletion->for($user),
        ];

        // Job recommendations based on user skills
        $recommendedJobs = Job::with("company")
            ->where("status", "active")
            ->where("deadline", ">=", now())
            ->whereDoesntHave("applications", function ($query) use ($user) {
                $query->where("user_id", $user->id);
            })
            ->latest()
            ->take(30)
            ->get();

        // Hitung match score untuk setiap job menggunakan JobMatchingService
        foreach ($recommendedJobs as $job) {
            $job->match_score = $this->matchingService->score($job, $user);
        }

        // Urutkan berdasarkan match_score desc dan ambil top 6
        $recommendedJobs = $recommendedJobs
            ->sortByDesc("match_score")
            ->values()
            ->take(6);

        // Recent applications with timeline
        $myApplications = Application::with(["job"])
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

        return compact(
            "stats",
            "recommendedJobs",
            "myApplications",
            "upcomingEvents",
            "activities",
        );
    }

    /**
     * Pindahan verbatim DashboardController::getUserActivityTimeline().
     * Event, ordering, limit, label, dan empty state IDENTIK.
     */
    private function getUserActivityTimeline(User $user): array
    {
        $activities = [];

        // Recent applications
        $applications = Application::with("job")
            ->where("user_id", $user->id)
            ->latest()
            ->take(5)
            ->get();

        foreach ($applications as $app) {
            $activities[] = [
                "type" => "application",
                "title" => "Melamar ke " . ($app->job?->title ?? 'Lowongan yang sudah dihapus'),
                "description" =>
                    "Status: " . Label::applicationStatus($app->status),
                "timestamp" => $app->created_at,
                "icon" => "briefcase",
                "color" => $this->getStatusColor($app->status),
            ];
        }

        // Recent bookmarks
        $bookmarks = Bookmark::with("job")
            ->where("user_id", $user->id)
            ->latest()
            ->take(3)
            ->get();

        foreach ($bookmarks as $bookmark) {
            $activities[] = [
                "type" => "bookmark",
                "title" => "Menyimpan " . ($bookmark->job?->title ?? 'Lowongan yang sudah dihapus'),
                "description" =>
                    $bookmark->job?->company_name ?? 'Perusahaan',
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

    /**
     * Pindahan verbatim DashboardController::getStatusColor().
     */
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
