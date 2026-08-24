<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApplicationPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function view(User $user, Application $application)
    {
        // The applicant themselves
        if ($user->id === $application->user_id) return true;

        // Company user viewing applications for their own job
        if ($user->role === 'company' && $user->company) {
            $application->loadMissing('job');
            return $user->company->id === $application->job?->company_id;
        }

        return false;
    }

    public function update(User $user, Application $application)
    {
        // admin can do anything, handled in before()
        if ($user->role === 'company' && $user->company) {
            return $user->company->id === $application->job->company_id;
        }

        return false;
    }

    public function downloadAttachment(User $user, Application $application): bool
    {
        return $this->view($user, $application);
    }
}