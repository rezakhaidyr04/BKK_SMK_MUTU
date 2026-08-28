<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminJobStoreRequest;
use App\Http\Requests\AdminJobUpdateRequest;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with('company');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('job_type', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(AdminJobStoreRequest $request)
    {
        $validated = $request->validated();

        Job::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan berhasil dibuat.');
    }

    public function show(Job $job)
    {
        $job->load('applications.user.documents');

        return view('admin.jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(AdminJobUpdateRequest $request, Job $job)
    {
        $validated = $request->validated();

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Job $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }

    public function broadcast(Job $job)
    {
        $jobseekers = \App\Models\User::where('role', 'umum')->get();

        \Illuminate\Support\Facades\Notification::send($jobseekers, new \App\Notifications\NewJobNotification($job));

        return redirect()->back()
            ->with('success', 'Notifikasi lowongan kerja berhasil di-broadcast ke ' . $jobseekers->count() . ' pencari kerja melalui email.');
    }

    public function approve(Job $job)
    {
        $job->update(['status' => 'active']);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan disetujui dan dipublikasikan.');
    }

    public function reject(Job $job)
    {
        $job->update(['status' => 'rejected']);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Lowongan ditolak.');
    }
}
