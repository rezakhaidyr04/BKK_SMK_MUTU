<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;
        $query = Job::where('company_id', $company?->id);

        if ($request->filled('search')) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('position', 'like', '%'.$request->search.'%')
                    ->orWhere('location', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->withCount('applications')->latest()->paginate(10)->withQueryString();

        return view('company.jobs.index', compact('jobs'));
    }

    public function create()
    {
        if (\Illuminate\Support\Facades\Gate::denies('create', Job::class)) {
            return redirect()->route('company.profile.edit')->with('error', 'Akun perusahaan Anda belum diverifikasi oleh admin. Silakan lengkapi profil dan ajukan verifikasi.');
        }

        return view('company.jobs.create');
    }

    public function store(Request $request)
    {
        if (Gate::denies('create', Job::class)) {
            return redirect()->route('company.jobs.index')->with(
                'error',
                'Akun perusahaan Anda belum diverifikasi oleh admin. Tidak dapat mempublikasikan lowongan saat ini.',
            );
        }

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_type' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'qualifications' => ['required', 'string'],
            'benefits' => ['nullable', 'string'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'string'],
        ]);

        $validated['company_id'] = $request->user()->company?->id;
        $validated['company_name'] = $request->user()->company?->name ?? $validated['company_name'];

        Job::create($validated);

        return redirect()->route('company.jobs.index')->with('success', 'Lowongan berhasil dibuat.');
    }
}
