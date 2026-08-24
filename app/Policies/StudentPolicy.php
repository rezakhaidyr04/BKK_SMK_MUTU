<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function view(User $user, Student $student)
    {
        if ($user->isTeacher() || $user->isAdmin()) {
            return true;
        }

        if ($user->isJobseeker()) {
            return $user->student?->id === $student->id;
        }

        return false;
    }
}
