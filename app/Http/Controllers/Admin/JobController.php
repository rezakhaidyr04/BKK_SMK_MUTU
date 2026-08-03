<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::query();

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', Rule::in(['full_time', 'part_time', 'internship', 'contract'])],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'qualifications' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['active', 'inactive', 'closed'])],
        ]);

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

    public function update(Request $request, Job $job)
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', Rule::in(['full_time', 'part_time', 'internship', 'contract'])],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'qualifications' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['active', 'inactive', 'closed'])],
        ]);

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
        $students = \App\Models\User::where('role', 'student')->get();
        
        \Illuminate\Support\Facades\Notification::send($students, new \App\Notifications\NewJobNotification($job));

        return redirect()->back()
            ->with('success', 'Notifikasi lowongan kerja berhasil di-broadcast ke ' . $students->count() . ' siswa melalui email.');
    }
}
