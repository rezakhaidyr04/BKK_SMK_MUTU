<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\File;

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
            'partnerLogos'
        ));
    }
}
