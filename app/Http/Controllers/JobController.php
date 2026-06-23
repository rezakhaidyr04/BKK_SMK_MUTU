<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Bookmark;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company.user'])
            ->where('status', 'active')
            ->where('deadline', '>=', now());

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        // Filter by salary range
        if ($request->filled('salary_min')) {
            $query->where('salary_min', '>=', $request->salary_min);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'salary_high':
                $query->orderByDesc('salary_max');
                break;
            case 'salary_low':
                $query->orderBy('salary_min');
                break;
            case 'deadline':
                $query->orderBy('deadline');
                break;
            default:
                $query->latest();
        }

        $jobs = $query->paginate(12);

        // Get filter options
        $jobTypes = Job::select('job_type')->distinct()->pluck('job_type');
        $locations = Job::select('location')->distinct()->pluck('location');

        return view('jobs.index', compact('jobs', 'jobTypes', 'locations'));
    }

    public function show(Job $job)
    {
        $job->load(['company.user', 'applications']);
        
        // Check if user has already applied
        $hasApplied = false;
        $isBookmarked = false;
        
        if (Auth::check()) {
            $hasApplied = Application::where('job_id', $job->id)
                ->where('user_id', Auth::id())
                ->exists();
                
            $isBookmarked = Bookmark::where('job_id', $job->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        // Similar jobs
        $similarJobs = Job::with(['company.user'])
            ->where('id', '!=', $job->id)
            ->where('status', 'active')
            ->where(function($query) use ($job) {
                $query->where('job_type', $job->job_type)
                      ->orWhere('location', $job->location);
            })
            ->take(4)
            ->get();

        return view('jobs.show', compact('job', 'hasApplied', 'isBookmarked', 'similarJobs'));
    }

    public function apply(Request $request, Job $job)
    {
        $request->validate([
            'cover_letter' => 'required|string|min:100',
        ]);

        // Check if already applied
        $existingApplication = Application::where('job_id', $job->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'Anda sudah melamar lowongan ini.');
        }

        // Create application
        Application::create([
            'job_id' => $job->id,
            'user_id' => Auth::id(),
            'cover_letter' => $request->cover_letter,
            'status' => 'submitted',
        ]);

        // TODO: Send notification to company

        return redirect()->route('jobs.show', $job)
            ->with('success', 'Lamaran Anda berhasil dikirim!');
    }

    public function bookmark(Job $job)
    {
        $bookmark = Bookmark::where('job_id', $job->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json(['bookmarked' => false, 'message' => 'Lowongan tersimpan dihapus']);
        } else {
            Bookmark::create([
                'job_id' => $job->id,
                'user_id' => Auth::id(),
            ]);
            return response()->json(['bookmarked' => true, 'message' => 'Lowongan berhasil disimpan']);
        }
    }
}
