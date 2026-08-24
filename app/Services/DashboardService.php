<?php

namespace App\Services;

use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use App\Models\Event;
use App\Models\Bookmark;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Get dashboard data based on user role
     */
    public function getDashboardData(User $user): array
    {
        return match($user->role) {
            'admin' => $this->getAdminStats(),
            'jobseeker' => $this->getStudentAlumniStats($user),
            'teacher' => $this->getTeacherStats(),
            'company' => [],
            default => [],
        };
    }

    /**
     * Get admin dashboard statistics
     */
    private function getAdminStats(): array
    {
        return [
            'total_students' => $this->getCachedCount('total_students', fn() => User::where('role', 'jobseeker')->count()),
            'total_alumni' => 0,
            'total_jobs' => $this->getCachedCount('total_jobs', fn() => Job::where('status', 'active')->count()),
            'total_applications' => $this->getCachedCount('total_applications', fn() => Application::count()),
            'pending_applications' => $this->getCachedCount('pending_applications', fn() => Application::where('status', 'submitted')->count()),
            'active_events' => $this->getCachedCount('active_events', fn() => Event::where('status', 'active')->count()),
        ];
    }

    /**
     * Get student/alumni dashboard statistics
     */
    private function getStudentAlumniStats(User $user): array
    {
        $userId = $user->id;

        return [
            'active_applications' => $this->getCachedCount("user_{$userId}_active_applications", fn() => 
                Application::where('user_id', $userId)->whereIn('status', ['submitted', 'under_review', 'interviewed'])->count()
            ),
            'interviews' => $this->getCachedCount("user_{$userId}_interviews", fn() => 
                Application::where('user_id', $userId)->where('status', 'interviewed')->count()
            ),
            'accepted' => $this->getCachedCount("user_{$userId}_accepted", fn() => 
                Application::where('user_id', $userId)->where('status', 'accepted')->count()
            ),
            'bookmarked_jobs' => $this->getCachedCount("user_{$userId}_bookmarked", fn() => 
                $user->bookmarks()->count()
            ),
            'unread_messages' => $this->getCachedCount("user_{$userId}_unread_messages", fn() => 
                Message::where('receiver_id', $userId)->where('is_read', false)->count()
            ),
        ];
    }

    /**
     * Get teacher dashboard statistics
     */
    private function getTeacherStats(): array
    {
        return [
            'total_students' => $this->getCachedCount('total_students', fn() => User::where('role', 'jobseeker')->count()),
            'total_alumni' => 0,
            'active_jobs' => $this->getCachedCount('total_jobs', fn() => Job::where('status', 'active')->count()),
            'placed_alumni' => $this->getCachedCount('placed_alumni', fn() => 
                Application::where('status', 'accepted')->count()
            ),
        ];
    }

    /**
     * Get cached count with fallback
     */
    private function getCachedCount(string $key, callable $callback): int
    {
        return Cache::remember($key, now()->addMinutes(30), $callback);
    }
}
