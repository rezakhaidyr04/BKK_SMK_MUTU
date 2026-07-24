<?php

namespace App\Services;

use App\Models\User;
use App\Models\Job;
use App\Models\Application;
use App\Models\Event;
use App\Models\Bookmark;
use App\Models\Message;
use App\Models\Company;
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
            'student', 'alumni' => $this->getStudentAlumniStats($user),
            'company' => $this->getCompanyStats($user),
            'teacher' => $this->getTeacherStats(),
            default => $this->getStudentAlumniStats($user),
        };
    }

    /**
     * Get admin dashboard statistics
     */
    private function getAdminStats(): array
    {
        return [
            'total_students' => $this->getCachedCount('total_students', fn() => User::where('role', 'student')->count()),
            'total_alumni' => $this->getCachedCount('total_alumni', fn() => User::where('role', 'alumni')->count()),
            'total_companies' => $this->getCachedCount('total_companies', fn() => User::where('role', 'company')->count()),
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
     * Get company dashboard statistics
     */
    private function getCompanyStats(User $user): array
    {
        $company = $user->company;
        if (!$company) {
            return $this->getDefaultCompanyStats();
        }

        $companyId = $company->id;

        return [
            'active_jobs' => $this->getCachedCount("company_{$companyId}_active_jobs", fn() => 
                Job::where('company_id', $companyId)->where('status', 'active')->count()
            ),
            'total_applicants' => $this->getCachedCount("company_{$companyId}_total_applicants", fn() => 
                Application::whereIn('job_id', Job::where('company_id', $companyId)->pluck('id'))->count()
            ),
            'new_applicants' => $this->getCachedCount("company_{$companyId}_new_applicants", fn() => 
                Application::whereIn('job_id', Job::where('company_id', $companyId)->pluck('id'))
                    ->where('status', 'submitted')
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count()
            ),
            'interviews_scheduled' => $this->getCachedCount("company_{$companyId}_interviews", fn() => 
                Application::whereIn('job_id', Job::where('company_id', $companyId)->pluck('id'))
                    ->where('status', 'interviewed')
                    ->count()
            ),
        ];
    }

    /**
     * Get teacher dashboard statistics
     */
    private function getTeacherStats(): array
    {
        return [
            'total_students' => $this->getCachedCount('total_students', fn() => User::where('role', 'student')->count()),
            'total_alumni' => $this->getCachedCount('total_alumni', fn() => User::where('role', 'alumni')->count()),
            'active_jobs' => $this->getCachedCount('total_jobs', fn() => Job::where('status', 'active')->count()),
            'placed_alumni' => $this->getCachedCount('placed_alumni', fn() => 
                Application::where('status', 'accepted')->count()
            ),
        ];
    }

    /**
     * Get default company stats when company not found
     */
    private function getDefaultCompanyStats(): array
    {
        return [
            'active_jobs' => 0,
            'total_applicants' => 0,
            'new_applicants' => 0,
            'interviews_scheduled' => 0,
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
