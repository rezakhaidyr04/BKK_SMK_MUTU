<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;

        if (! $company) {
            abort(404, 'Profil perusahaan tidak ditemukan.');
        }

        $applications = Application::with(['job', 'user'])
            ->whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })
            ->latest()
            ->paginate(10);

        return view('company.applicants.index', compact('applications', 'company'));
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application);

        $application->load([
            'job.company',
            'user',
            'user.skills',
            'user.cvFiles',
            'user.certificates',
            'user.documents',
        ]);

        return view('company.applicants.show', compact('application'));
    }

    public function update(UpdateApplicationRequest $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validated();

        $oldStatus = $application->status;
        $application->status = $validated['status'];

        if ($validated['status'] === 'interviewed') {
            $application->interview_date = $validated['interview_date'] ? $validated['interview_date'] . ' ' . $validated['interview_time'] . ':00' : null;
            $application->interview_type = $validated['interview_type'];
            $application->interview_link = $validated['interview_link'] ?? null;
            $application->interview_location = $validated['interview_location'] ?? null;
            $application->interview_notes = $validated['interview_notes'] ?? null;
        } else {
            $application->interview_date = null;
            $application->interview_type = null;
            $application->interview_link = null;
            $application->interview_location = null;
            $application->interview_notes = null;
        }

        $application->save();

        if ($oldStatus !== $application->status) {
            if ($application->status === 'interviewed') {
                $application->user->notify(new \App\Notifications\InterviewScheduled($application));
            } else {
                $application->user->notify(new \App\Notifications\ApplicationStatusUpdated($application));
            }
        }

        return redirect()->back()->with('success', 'Status lamaran berhasil diperbarui.');
    }
}
