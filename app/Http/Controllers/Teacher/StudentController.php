<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user');
        
        // Filter by major
        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }
        
        // Filter by status (placed/not placed)
        if ($request->filled('status')) {
            if ($request->status === 'placed') {
                $query->whereHas('user.applications', function ($q) {
                    $q->where('status', 'accepted');
                });
            } elseif ($request->status === 'not_placed') {
                $query->whereDoesntHave('user.applications', function ($q) {
                    $q->where('status', 'accepted');
                });
            }
        }
        
        // Search by name
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        $students = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $majors = ['TKJ', 'RPL', 'MM', 'AKL', 'OTKP'];
        
        return view('dashboard.teacher-students', compact('students', 'majors'));
    }
    
    public function show(Student $student)
    {
        $this->authorize('view', $student);
        
        $applications = Application::where('user_id', $student->user_id)
            ->with(['job.company'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $stats = [
            'total_applications' => $applications->count(),
            'under_review' => $applications->where('status', 'under_review')->count(),
            'interviewed' => $applications->where('status', 'interviewed')->count(),
            'accepted' => $applications->where('status', 'accepted')->count(),
        ];
        
        return view('dashboard.teacher-student-detail', compact('student', 'applications', 'stats'));
    }
}
