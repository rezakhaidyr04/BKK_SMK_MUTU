<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Bookmark;
use App\Models\Application;
use App\Http\Requests\ApplicationRequest;
use App\Notifications\ApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company')
            ->where("status", "active")
            ->where("deadline", ">=", now());

        // Search
        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("title", "like", "%{$search}%")
                    ->orWhere("position", "like", "%{$search}%")
                    ->orWhere("description", "like", "%{$search}%")
                    ->orWhere("location", "like", "%{$search}%")
                    ->orWhere("company_name", "like", "%{$search}%");
            });
        }

        // Filter by job type
        if ($request->filled("job_type")) {
            $query->where("job_type", $request->job_type);
        }

        // Filter by location
        if ($request->filled("location")) {
            $query->where("location", "like", "%{$request->location}%");
        }

        // Filter by salary range
        if ($request->filled("salary_min")) {
            $query->where("salary_min", ">=", $request->salary_min);
        }

        // Sort
        $sortBy = $request->get("sort", "latest");
        switch ($sortBy) {
            case "salary_high":
                $query->orderByDesc("salary_max");
                break;
            case "salary_low":
                $query->orderBy("salary_min");
                break;
            case "deadline":
                $query->orderBy("deadline");
                break;
            default:
                $query->latest();
        }

        $jobs = $query->paginate(12);

        // Get filter options
        $jobTypes = Job::select("job_type")
            ->distinct()
            ->pluck("job_type");

        // Use predefined locations from config
        $locations = collect(config('locations.locations', []));

        $activeJobsCount = Job::where("status", "active")
            ->where("deadline", ">=", now())
            ->count();
        $locationsCount = Job::where("status", "active")
            ->where("deadline", ">=", now())
            ->distinct("location")
            ->count("location");
        $companiesCount = Job::where("status", "active")
            ->where("deadline", ">=", now())
            ->distinct("company_name")
            ->count("company_name");

        return view("jobs.index", compact("jobs", "jobTypes", "locations", "activeJobsCount", "locationsCount", "companiesCount"));
    }

    public function show(Job $job)
    {
        // P0 C-04: guest hanya boleh melihat job active + deadline belum lewat.
        // Konsisten dengan index() dan Api\JobController@show().
        if ($job->status !== 'active') {
            abort(404);
        }

        if ($job->deadline && $job->deadline->lt(now()->startOfDay())) {
            abort(404);
        }

        // P0 C-04: JANGAN load seluruh applications di halaman publik.
        // Hanya hitung + cek existence milik user login.
        $job->loadCount(['applications']);
        $applicationsCount = $job->applications_count ?? 0;

        // Check if user has already applied
        $hasApplied = false;
        $isBookmarked = false;

        if (Auth::check()) {
            $hasApplied = Application::where("job_id", $job->id)
                ->where("user_id", Auth::id())
                ->exists();

            $isBookmarked = Bookmark::where("job_id", $job->id)
                ->where("user_id", Auth::id())
                ->exists();
        }

        $savedCount = Bookmark::where("job_id", $job->id)->count();

        // Stat owner (Ditinjau/Diterima) hanya dihitung untuk pemilik lowongan,
        // agar tidak membocorkan pipeline rekrutmen ke publik.
        $reviewedCount = null;
        $acceptedCount = null;
        $ownerApplicationsCount = null;
        $isOwner = Auth::check()
            && Auth::user()->isCompany()
            && $job->company_id
            && Auth::user()->company?->id === $job->company_id;

        if ($isOwner) {
            $ownerApplicationsCount = $applicationsCount;
            $reviewedCount = Application::where('job_id', $job->id)
                ->where('status', 'under_review')
                ->count();
            $acceptedCount = Application::where('job_id', $job->id)
                ->where('status', 'accepted')
                ->count();
        }

        // Similar jobs
        $similarJobs = Job::where("id", "!=", $job->id)
            ->where("status", "active")
            ->where(function ($query) use ($job) {
                $query
                    ->where("job_type", $job->job_type)
                    ->orWhere("location", $job->location);
            })
            ->take(4)
            ->get();

        return view(
            "jobs.show",
            compact("job", "hasApplied", "isBookmarked", "savedCount", "similarJobs", "applicationsCount", "reviewedCount", "acceptedCount", "ownerApplicationsCount", "isOwner"),
        );
    }

    public function apply(ApplicationRequest $request, Job $job)
    {
        abort_unless(Auth::user()?->role === "umum", 403);

        // P0 C-03: server-side guard — draft/closed/rejected/expired tidak boleh dilamar.
        if ($job->status !== 'active') {
            return back()->with('error', 'Lowongan sudah ditutup atau tidak aktif.');
        }

        if ($job->deadline && $job->deadline->lt(now()->startOfDay())) {
            return back()->with('error', 'Lowongan sudah ditutup atau masa berlaku telah berakhir.');
        }

        // Check if already applied
        $existingApplication = Application::where("job_id", $job->id)
            ->where("user_id", Auth::id())
            ->first();

        if ($existingApplication) {
            return back()->with("error", "Anda sudah melamar lowongan ini.");
        }

        $attachment = $request->file("attachment");
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;
        $attachmentSize = null;

        if ($attachment) {
            $attachmentPath = $attachment->store("applications", "private");
            $attachmentName = basename($attachment->getClientOriginalName());
            // P1-H05: server-side MIME detection, jangan percaya client
            $attachmentMime = $attachment->getMimeType() ?: $attachment->getClientMimeType();
            $attachmentSize = $attachment->getSize();
        }

        $application = Application::create([
            "job_id" => $job->id,
            "user_id" => Auth::id(),
            "cover_letter" => str_replace(['\\r\\n', '\\n', '\\r'], "\n", $request->cover_letter ?? ''),
            "attachment_path" => $attachmentPath,
            "attachment_name" => $attachmentName,
            "attachment_mime" => $attachmentMime,
            "attachment_size" => $attachmentSize,
            "status" => "submitted",
        ]);

        // Beri tahu perusahaan pemilik lowongan.
        if ($job->company?->user) {
            $job->company->user->notify(new ApplicationReceived($application));
        }

        return redirect()
            ->route("jobs.show", $job)
            ->with("success", "Lamaran Anda berhasil dikirim!");
    }

    public function bookmark(Job $job)
    {
        $bookmark = Bookmark::where("job_id", $job->id)
            ->where("user_id", Auth::id())
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                "bookmarked" => false,
                "message" => "Lowongan tersimpan dihapus",
            ]);
        } else {
            Bookmark::create([
                "job_id" => $job->id,
                "user_id" => Auth::id(),
            ]);
            return response()->json([
                "bookmarked" => true,
                "message" => "Lowongan berhasil disimpan",
            ]);
        }
    }
}
