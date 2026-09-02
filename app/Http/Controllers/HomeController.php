<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\Review;

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

        // Get review statistics
        $averageRating = round(Review::getAverageRating(), 1);
        $totalReviews = Review::getTotalReviews();
        $satisfactionPercentage = Review::getSatisfactionPercentage();
        
        // Get approved reviews (limit to 3 for display)
        $approvedReviews = Review::approved()
            ->orderByRaw('featured DESC')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('welcome', compact(
            'jobs',
            'activeJobsCount',
            'usersCount',
            'companiesCount',
            'successRate',
            'averageRating',
            'totalReviews',
            'satisfactionPercentage',
            'approvedReviews'
        ));
    }
}
