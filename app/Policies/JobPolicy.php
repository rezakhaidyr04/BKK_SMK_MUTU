<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true;
        }
    }

    public function view(User $user, Job $job)
    {
        return $user->company && $user->company->id === $job->company_id;
    }

    public function create(User $user)
    {
        return $user->role === 'company' && $user->company !== null;
    }

    public function update(User $user, Job $job)
    {
        return $user->company && $user->company->id === $job->company_id;
    }

    public function delete(User $user, Job $job)
    {
        return $user->company && $user->company->id === $job->company_id;
    }
}
