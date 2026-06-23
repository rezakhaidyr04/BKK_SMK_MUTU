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
        // applicant themselves or company owner
        if ($user->id === $application->user_id) return true;
        if ($user->company && $user->company->id === $application->job->company_id) return true;
        return false;
    }

    public function update(User $user, Application $application)
    {
        // only company owning the job can change status
        return $user->company && $user->company->id === $application->job->company_id;
    }
}
