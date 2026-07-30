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
        return true; // Anyone can view jobs
    }

    public function create(User $user)
    {
        return $user->role === 'admin'; // Only admin can create jobs
    }

    public function update(User $user, Job $job)
    {
        return $user->role === 'admin'; // Only admin can update jobs
    }

    public function delete(User $user, Job $job)
    {
        return $user->role === 'admin'; // Only admin can delete jobs
    }
}
