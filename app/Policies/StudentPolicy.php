<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;

class StudentPolicy
{
    public function view(User $user, Student $student)
    {
        // Teachers can view any student
        if ($user->role === 'teacher' || $user->role === 'admin') {
            return true;
        }
        
        // Students can only view their own profile
        if ($user->role === 'student' && $user->student_id === $student->id) {
            return true;
        }
        
        return false;
    }
}
