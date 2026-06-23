<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->company;

        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil perusahaan belum lengkap. Silakan lengkapi profil perusahaan Anda.');
        }

        $query = Job::where('company_id', $company->id)->withCount('applications');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('position', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();

        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('company.jobs.create');
    }

    public function store(Request $request)
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'Profil perusahaan belum lengkap.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|in:full_time,part_time,internship,contract',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'description' => 'required|string',
            'qualifications' => 'nullable|string',
            'benefits' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:active,closed,draft',
        ]);

        $validated['company_id'] = $company->id;

        Job::create($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil dibuat.');
    }

    public function edit(Job $job)
    {
        $company = Auth::user()->company;
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        return view('company.jobs.edit', compact('job'));
    }

    public function update(Request $request, Job $job)
    {
        $company = Auth::user()->company;
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'job_type' => 'nullable|string|in:full_time,part_time,internship,contract',
            'salary_min' => 'nullable|numeric',
            'salary_max' => 'nullable|numeric|gte:salary_min',
            'description' => 'required|string',
            'qualifications' => 'nullable|string',
            'benefits' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:active,closed,draft',
        ]);

        $job->update($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Job $job)
    {
        $company = Auth::user()->company;
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $job->delete();

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil dihapus.');
    }
}
