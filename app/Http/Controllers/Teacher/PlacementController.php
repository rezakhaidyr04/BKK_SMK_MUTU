<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use Illuminate\Http\Request;

class PlacementController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['user.student', 'job.company'])
            ->where('status', 'accepted');
        
        // Filter by year
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        
        // Filter by major
        if ($request->filled('major')) {
            $query->whereHas('user.student', function ($q) use ($request) {
                $q->where('major', $request->major);
            });
        }
        
        // Filter by company
        if ($request->filled('company')) {
            $query->whereHas('job.company', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->company . '%');
            });
        }
        
        $placements = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Get statistics
        $stats = [
            'total_placed' => Application::where('status', 'accepted')->count(),
            'this_year' => Application::where('status', 'accepted')
                ->whereYear('created_at', date('Y'))->count(),
            'by_major' => Application::where('status', 'accepted')
                ->join('users', 'applications.user_id', '=', 'users.id')
                ->join('students', 'users.id', '=', 'students.user_id')
                ->selectRaw('students.major, COUNT(*) as count')
                ->groupBy('students.major')
                ->pluck('count', 'major')
                ->toArray(),
        ];
        
        $majors = ['TKJ', 'RPL', 'MM', 'AKL', 'OTKP'];
        $years = range(date('Y'), date('Y') - 5);
        
        return view('dashboard.teacher-placements', compact('placements', 'stats', 'majors', 'years'));
    }
}
