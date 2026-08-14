<?php

namespace App\Policies;

use App\Models\CvFile;
use App\Models\User;

class CvFilePolicy
{
    public function view(User $user, CvFile $cvFile): bool
    {
        return $user->role === 'admin' || $user->id === $cvFile->user_id;
    }
}
