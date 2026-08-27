<?php

namespace App\Services;

use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use App\Models\Event;
use App\Models\Message;
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
            'umum' => $this->getUmumStats($user),
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
            'total_umum' => $this->getCachedCount('total_umum', fn() => User::where('role', 'umum')->count()),
            'total_jobs' => $this->getCachedCount('total_jobs', fn() => Job::where('status', 'active')->count()),
            'total_applications' => $this->getCachedCount('total_applications', fn() => Application::count()),
            'pending_applications' => $this->getCachedCount('pending_applications', fn() => Application::where('status', 'submitted')->count()),
            'active_events' => $this->getCachedCount('active_events', fn() => Event::where('start_time', '>=', now())->count()),
        ];
    }

    /**
     * Get umum (pencari kerja) dashboard statistics
     */
    private function getUmumStats(User $user): array
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
                Message::whereHas('conversation.users', fn($q) => $q->where('users.id', $userId))
                    ->where('sender_id', '!=', $userId)
                    ->where('is_read', false)->count()
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
