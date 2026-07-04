<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Job;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $jobs = Job::with('company.user')
            ->where('status', 'active')
            ->where('deadline', '>=', now())
            ->latest()
            ->take(12)
            ->get();

        $activeJobsCount = Job::where('status', 'active')
            ->where('deadline', '>=', now())
            ->count();

        $studentsCount = User::whereIn('role', ['student', 'alumni'])->count();

        $companiesCount = Company::count();

        return view('welcome', compact(
            'jobs',
            'activeJobsCount',
            'studentsCount',
            'companiesCount'
        ));
    }
}
