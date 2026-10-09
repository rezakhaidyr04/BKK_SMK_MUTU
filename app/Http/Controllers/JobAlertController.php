<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobAlertRequest;
use App\Models\Job;
use App\Models\JobAlert;

class JobAlertController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['index', 'store', 'destroy']);
    }

    public function index()
    {
        abort_unless(auth()->user()->role === 'umum', 403);

        $alerts = JobAlert::where('user_id', auth()->id())->latest()->get();
        $jobTypes = Job::select('job_type')->distinct()->pluck('job_type');

        return view('job-alerts.index', compact('alerts', 'jobTypes'));
    }

    public function store(StoreJobAlertRequest $request)
    {
        if (JobAlert::where('user_id', auth()->id())->count() >= JobAlert::MAX_PER_USER) {
            return back()->with('error', 'Maksimal '.JobAlert::MAX_PER_USER.' langganan aktif. Hapus salah satu dulu.')->withInput();
        }

        JobAlert::create([
            'user_id' => auth()->id(),
            'keyword' => $request->validated('keyword') ?: null,
            'job_type' => $request->validated('job_type') ?: null,
            'province' => $request->validated('province') ?: null,
            'city' => $request->validated('city') ?: null,
            'district' => $request->validated('district') ?: null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Langganan dibuat! Ringkasan lowongan cocok dikirim tiap minggu via email.');
    }

    public function toggle(JobAlert $jobAlert)
    {
        abort_unless($jobAlert->user_id === auth()->id(), 403);

        $jobAlert->update(['is_active' => ! $jobAlert->is_active]);

        return back()->with('success', $jobAlert->is_active ? 'Langganan diaktifkan.' : 'Langganan dijeda.');
    }

    public function destroy(JobAlert $jobAlert)
    {
        abort_unless($jobAlert->user_id === auth()->id(), 403);

        $jobAlert->delete();

        return back()->with('success', 'Langganan dihapus.');
    }
}
