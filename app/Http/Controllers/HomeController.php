<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\File;

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

        $studentsCount = User::where('role', 'jobseeker')->count();

        // Calculate stats for the welcome page
        $companiesCount = \App\Models\Company::count();

        $jobseekerCount = User::where('role', 'jobseeker')->count();
        $placedJobseekersCount = User::where('role', 'jobseeker')
            ->whereHas('applications', function($q) {
                $q->where('status', 'accepted');
            })->count();

        $successRate = $jobseekerCount > 0 ? round(($placedJobseekersCount / $jobseekerCount) * 100) : 0;

        // collect static partner logos from public/images/companies
        $partnerLogos = [];
        $dir = public_path('images/companies');
        if (File::exists($dir)) {
            $files = File::files($dir);
            foreach ($files as $f) {
                $partnerLogos[] = 'images/companies/' . $f->getFilename();
            }
        }

        return view('welcome', compact(
            'jobs',
            'activeJobsCount',
            'studentsCount',
            'companiesCount',
            'successRate',
            'partnerLogos'
        ));
    }
}
