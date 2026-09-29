<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\CvFile;
use App\Models\User;

class CvFilePolicy
{
    public function view(User $user, CvFile $cvFile): bool
    {
        if ($user->role === 'admin' || $user->id === $cvFile->user_id) {
            return true;
        }

        // Perusahaan pemilik lowongan yang dilamar user tersebut boleh
        // melihat/mengunduh CV pelamarnya.
        if ($user->role === 'company' && $user->company) {
            return Application::where('user_id', $cvFile->user_id)
                ->whereHas('job', fn ($q) => $q->where('company_id', $user->company->id))
                ->exists();
        }

        return false;
    }
}
