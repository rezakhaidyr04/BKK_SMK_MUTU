<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Student;
use App\Models\Job;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get placement statistics by major
        $placementByMajor = Student::select('major')
            ->withCount(['user as placed_count' => function ($q) {
                $q->whereHas('applications', function ($app) {
                    $app->where('status', 'accepted');
                });
            }])
            ->withCount('user as total_count')
            ->get()
            ->map(function ($student) {
                return [
                    'major' => $student->major,
                    'total' => $student->total_count,
                    'placed' => $student->placed_count,
                    'percentage' => $student->total_count > 0 
                        ? round(($student->placed_count / $student->total_count) * 100, 1) 
                        : 0,
                ];
            });
        
        // Get monthly placement trends
        $monthlyTrends = Application::where('status', 'accepted')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Get top companies
        $topCompanies = Application::where('status', 'accepted')
            ->join('jobs', 'applications.job_id', '=', 'jobs.id')
            ->join('companies', 'jobs.company_id', '=', 'companies.id')
            ->selectRaw('companies.name, COUNT(*) as count')
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc('count')
            ->limit(10)
            ->get();
        
        // Get application status distribution
        $statusDistribution = Application::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });
        
        $stats = [
            'total_students' => Student::count(),
            'total_placed' => Application::where('status', 'accepted')->count(),
            'placement_rate' => Student::count() > 0 
                ? round((Application::where('status', 'accepted')->count() / Student::count()) * 100, 1) 
                : 0,
            'total_jobs' => Job::count(),
        ];
        
        return view('dashboard.teacher-reports', compact(
            'placementByMajor',
            'monthlyTrends',
            'topCompanies',
            'statusDistribution',
            'stats'
        ));
    }
}
