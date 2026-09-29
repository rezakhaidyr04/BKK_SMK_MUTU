<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    public function view(User $user, Certificate $certificate): bool
    {
        if ($user->role === 'admin' || $user->id === $certificate->user_id) {
            return true;
        }

        // Perusahaan pemilik lowongan yang dilamar user tersebut boleh
        // melihat/mengunduh sertifikat pelamarnya.
        if ($user->role === 'company' && $user->company) {
            return Application::where('user_id', $certificate->user_id)
                ->whereHas('job', fn ($q) => $q->where('company_id', $user->company->id))
                ->exists();
        }

        return false;
    }
}
