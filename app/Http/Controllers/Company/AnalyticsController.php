<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;
        
        // Time-based analytics
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        $lastQuarter = $now->copy()->subQuarter();
        
        // Job performance metrics
        $jobs = Job::where('company_id', $company->id)
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->get();
        
        // Application trends
        $applicationTrends = Application::whereHas('job', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', $lastQuarter)
        ->groupBy('date')
        ->orderBy('date')
            ->get();
        
        // Status distribution
        $statusDistribution = Application::whereHas('job', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
        ->selectRaw('status, COUNT(*) as count')
        ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Time to hire (average days from application to accepted)
        $timeToHire = Application::whereHas('job', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })
        ->where('status', 'accepted')
        ->whereNotNull('updated_at')
        ->get()
            ->map(function($app) {
                return $app->created_at->diffInDays($app->updated_at);
            })
            ->avg();
        
        // Conversion funnel
        $totalViews = $jobs->sum('views') ?? 0;
        $totalApplications = $jobs->sum('applications_count');
        $totalInterviews = Application::whereHas('job', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })->where('status', 'interviewed')->count();
        $totalAccepted = Application::whereHas('job', function($query) use ($company) {
            $query->where('company_id', $company->id);
        })->where('status', 'accepted')->count();
        
        $conversionRate = $totalViews > 0 ? round(($totalApplications / $totalViews) * 100, 2) : 0;
        $interviewRate = $totalApplications > 0 ? round(($totalInterviews / $totalApplications) * 100, 2) : 0;
        $acceptanceRate = $totalInterviews > 0 ? round(($totalAccepted / $totalInterviews) * 100, 2) : 0;
        
        return view('company.analytics', compact(
            'company',
            'jobs',
            'applicationTrends',
            'statusDistribution',
            'timeToHire',
            'totalViews',
            'totalApplications',
            'totalInterviews',
            'totalAccepted',
            'conversionRate',
            'interviewRate',
            'acceptanceRate'
        ));
    }
}
