<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApplicationStatusChanged;
use Illuminate\Support\Facades\Notification;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->company;

        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil perusahaan belum lengkap. Silakan lengkapi profil perusahaan Anda.');
        }

        $applications = Application::with(['job', 'user'])
            ->whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            });

        if ($request->filled('status')) {
            $applications->where('status', $request->status);
        }

        $applications = $applications->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => $applications->total(),
            'submitted' => Application::whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('status', 'submitted')->count(),
            'under_review' => Application::whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('status', 'under_review')->count(),
            'interviewed' => Application::whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('status', 'interviewed')->count(),
            'accepted' => Application::whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('status', 'accepted')->count(),
            'rejected' => Application::whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id);
            })->where('status', 'rejected')->count(),
        ];

        return view('company.applicants.index', compact('applications', 'stats'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validate([
            'status' => 'required|in:submitted,under_review,interviewed,accepted,rejected'
        ]);

        $old = $application->status;
        $application->status = $validated['status'];
        $application->save();

        // Jika diterima, tutup lowongan (opsional)
        if ($validated['status'] === 'accepted') {
            $job = $application->job;
            $job->status = 'closed';
            $job->save();
        }

        // Kirim notifikasi ke pelamar, tapi jangan gagalkan update status kalau mail bermasalah
        try {
            Notification::send($application->user, new ApplicationStatusChanged($application, $old));
        } catch (\Throwable $e) {
            report($e);
        }

        // Log activity
        \App\Models\ActivityLog::create([
            'actor_type' => get_class(Auth::user()),
            'actor_id' => Auth::id(),
            'action' => 'application_status_changed',
            'detail' => json_encode(['old' => $old, 'new' => $application->status]),
            'subject_type' => get_class($application),
            'subject_id' => $application->id,
        ]);

        return redirect()->route('company.applicants.index')->with('success', 'Status pelamar diperbarui.');
    }
}
