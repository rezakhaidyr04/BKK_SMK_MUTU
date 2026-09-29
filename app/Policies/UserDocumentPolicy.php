<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;
use App\Models\UserDocument;

class UserDocumentPolicy
{
    public function view(User $user, UserDocument $document): bool
    {
        if ($user->role === 'admin' || $user->id === $document->user_id) {
            return true;
        }

        // Perusahaan pemilik lowongan yang dilamar user tersebut boleh
        // melihat/mengunduh dokumen pendukung pelamarnya.
        if ($user->role === 'company' && $user->company) {
            return Application::where('user_id', $document->user_id)
                ->whereHas('job', fn ($q) => $q->where('company_id', $user->company->id))
                ->exists();
        }

        return false;
    }
}
