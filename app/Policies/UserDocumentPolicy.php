<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserDocument;

class UserDocumentPolicy
{
    public function view(User $user, UserDocument $document): bool
    {
        return $user->role === 'admin' || $user->id === $document->user_id;
    }
}
