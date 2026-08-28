<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $jobs = Job::where('status', 'active')
            ->where('deadline', '>=', now())
            ->latest()
            ->take(12)
            ->get();

        $activeJobsCount = Job::where('status', 'active')
            ->where('deadline', '>=', now())
            ->count();

        $usersCount = User::where('role', 'umum')->count();

        // Calculate stats for the welcome page
        $companiesCount = \App\Models\Company::count();

        $umumCount = $usersCount;
        $placedUmumCount = User::where('role', 'umum')
            ->whereHas('applications', function($q) {
                $q->where('status', 'accepted');
            })->count();

        $successRate = $umumCount > 0 ? round(($placedUmumCount / $umumCount) * 100) : 0;

        return view('welcome', compact(
            'jobs',
            'activeJobsCount',
            'usersCount',
            'companiesCount',
            'successRate'
        ));
    }
}
