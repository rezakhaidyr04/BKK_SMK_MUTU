<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Bookmark;
use App\Models\Application;
use App\Http\Requests\ApplicationRequest;
use App\Notifications\ApplicationReceived;
use App\Services\JobMatchingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(["company.user"])
            ->where("status", "active")
            ->where("deadline", ">=", now())
            ->whereHas("company", function ($q) {
                $q->where("is_verified", true);
            });

        // Search
        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("title", "like", "%{$search}%")
                    ->orWhere("position", "like", "%{$search}%")
                    ->orWhere("description", "like", "%{$search}%")
                    ->orWhere("location", "like", "%{$search}%");
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
        $jobTypes = Job::whereHas(
            "company",
            fn($q) => $q->where("is_verified", true),
        )
            ->select("job_type")
            ->distinct()
            ->pluck("job_type");
        $locations = Job::whereHas(
            "company",
            fn($q) => $q->where("is_verified", true),
        )
            ->select("location")
            ->distinct()
            ->pluck("location");

        return view("jobs.index", compact("jobs", "jobTypes", "locations"));
    }

    public function show(Job $job)
    {
        $job->load(["company.user", "applications"]);

        // Sembunyikan lowongan dari perusahaan yang belum terverifikasi
        if (!optional($job->company)->is_verified) {
            // Admin dan pemilik company tetap bisa lihat
            if (
                !auth()->check() ||
                (auth()->user()->role !== "admin" &&
                    optional(auth()->user()->company)->id !== $job->company_id)
            ) {
                abort(404);
            }
        }

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
        $matchScore = null;

        if (Auth::check() && in_array(Auth::user()->role, ["student", "alumni"], true)) {
            $matchScore = (new JobMatchingService())->score($job, Auth::user());
        }

        // Similar jobs
        $similarJobs = Job::with(["company.user"])
            ->where("id", "!=", $job->id)
            ->where("status", "active")
            ->whereHas("company", fn($q) => $q->where("is_verified", true))
            ->where(function ($query) use ($job) {
                $query
                    ->where("job_type", $job->job_type)
                    ->orWhere("location", $job->location);
            })
            ->take(4)
            ->get();

        return view(
            "jobs.show",
            compact("job", "hasApplied", "isBookmarked", "savedCount", "similarJobs", "matchScore"),
        );
    }

    public function apply(ApplicationRequest $request, Job $job)
    {
        abort_unless(in_array(Auth::user()?->role, ["student", "alumni"], true), 403);

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
            $attachmentPath = $attachment->store("applications", "public");
            $attachmentName = $attachment->getClientOriginalName();
            $attachmentMime = $attachment->getClientMimeType();
            $attachmentSize = $attachment->getSize();
        }

        $application = Application::create([
            "job_id" => $job->id,
            "user_id" => Auth::id(),
            "cover_letter" => $request->cover_letter,
            "attachment_path" => $attachmentPath,
            "attachment_name" => $attachmentName,
            "attachment_mime" => $attachmentMime,
            "attachment_size" => $attachmentSize,
            "status" => "submitted",
        ]);

        $job->loadMissing(["company.user", "company"]);
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
