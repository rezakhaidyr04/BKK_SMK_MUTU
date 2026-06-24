<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\InterviewScheduled;
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
            'submitted' => Application::whereHas('job', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'submitted')->count(),
            'under_review' => Application::whereHas('job', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'under_review')->count(),
            'interviewed' => Application::whereHas('job', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'interviewed')->count(),
            'accepted' => Application::whereHas('job', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })->where('status', 'accepted')->count(),
            'rejected' => Application::whereHas('job', function ($q) use ($company) {
                $q->where('company_id', $company->id);
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

        if ($validated['status'] === 'accepted') {
            $application->job->update(['status' => 'closed']);
        }

        try {
            Notification::send($application->user, new ApplicationStatusChanged($application, $old));
        } catch (\Throwable $e) {
            report($e);
        }

        \App\Models\ActivityLog::create([
            'actor_type'   => get_class(Auth::user()),
            'actor_id'     => Auth::id(),
            'action'       => 'application_status_changed',
            'detail'       => json_encode(['old' => $old, 'new' => $application->status]),
            'subject_type' => get_class($application),
            'subject_id'   => $application->id,
        ]);

        return redirect()->route('company.applicants.index')
            ->with('success', 'Status pelamar diperbarui.');
    }

    // ── Form jadwal interview ──────────────────────────────────────
    public function showInterviewForm(Application $application)
    {
        $this->authorize('update', $application);
        $application->load(['job', 'user']);
        return view('company.applicants.interview', compact('application'));
    }

    // ── Simpan jadwal & kirim notifikasi ──────────────────────────
    public function scheduleInterview(Request $request, Application $application)
    {
        $this->authorize('update', $application);

        $validated = $request->validate([
            'interview_date'     => 'required|date|after:now',
            'interview_location' => 'required|string|max:255',
            'interview_type'     => 'required|in:online,offline',
            'interview_link'     => 'nullable|url|required_if:interview_type,online',
            'interview_notes'    => 'nullable|string|max:1000',
        ], [
            'interview_date.required'     => 'Tanggal wawancara wajib diisi.',
            'interview_date.after'        => 'Tanggal wawancara harus di masa mendatang.',
            'interview_location.required' => 'Tempat wawancara wajib diisi.',
            'interview_type.required'     => 'Tipe wawancara wajib dipilih.',
            'interview_link.url'          => 'Link wawancara harus berupa URL yang valid.',
            'interview_link.required_if'  => 'Link wawancara wajib diisi untuk wawancara online.',
        ]);

        // Update application
        $application->update(array_merge($validated, ['status' => 'interviewed']));

        // Kirim notifikasi ke pelamar
        try {
            $application->user->notify(new InterviewScheduled($application));
        } catch (\Throwable $e) {
            report($e);
        }

        // Log activity
        \App\Models\ActivityLog::create([
            'actor_type'   => get_class(Auth::user()),
            'actor_id'     => Auth::id(),
            'action'       => 'interview_scheduled',
            'detail'       => json_encode([
                'application_id' => $application->id,
                'date'           => $validated['interview_date'],
                'location'       => $validated['interview_location'],
            ]),
            'subject_type' => get_class($application),
            'subject_id'   => $application->id,
        ]);

        return redirect()->route('company.applicants.index')
            ->with('success', 'Jadwal wawancara berhasil dikirim ke pelamar!');
    }
}
