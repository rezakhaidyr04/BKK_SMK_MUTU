<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function downloadLegalDocument(User $user, Company $company): bool
    {
        return $user->role === 'admin' || $user->id === $company->user_id;
    }

    public function downloadMou(User $user, Company $company): bool
    {
        return $user->role === 'admin';
    }
}
