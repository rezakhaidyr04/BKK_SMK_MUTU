<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['job.company.user'])
            ->where('user_id', Auth::id())
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Application::where('user_id', Auth::id())->count(),
            'submitted' => Application::where('user_id', Auth::id())->where('status', 'submitted')->count(),
            'under_review' => Application::where('user_id', Auth::id())->where('status', 'under_review')->count(),
            'interviewed' => Application::where('user_id', Auth::id())->where('status', 'interviewed')->count(),
            'accepted' => Application::where('user_id', Auth::id())->where('status', 'accepted')->count(),
            'rejected' => Application::where('user_id', Auth::id())->where('status', 'rejected')->count(),
        ];

        return view('applications.index', compact('applications', 'stats'));
    }

    public function show(Application $application)
    {
        $application->load(['job.company.user']);
        $this->authorize('view', $application);

        // Timeline for status tracking
        $timeline = [
            [
                'status'    => 'submitted',
                'label'     => 'Lamaran Dikirim',
                'icon'      => 'document',
                'completed' => true,
                'date'      => $application->created_at,
            ],
            [
                'status'    => 'under_review',
                'label'     => 'Sedang Ditinjau',
                'icon'      => 'eye',
                'completed' => in_array($application->status, ['under_review', 'interviewed', 'accepted']),
                'date'      => null,
            ],
            [
                'status'    => 'interviewed',
                'label'     => 'Wawancara' . ($application->interview_date ? ' — ' . $application->interview_date->format('d M Y, H:i') : ''),
                'icon'      => 'calendar',
                'completed' => in_array($application->status, ['interviewed', 'accepted']),
                'date'      => $application->interview_date,
            ],
            [
                'status'      => 'accepted',
                'label'       => $application->status === 'rejected' ? 'Ditolak' : 'Diterima',
                'icon'        => $application->status === 'rejected' ? 'x' : 'check',
                'completed'   => in_array($application->status, ['accepted', 'rejected']),
                'date'        => null,
                'is_final'    => true,
                'is_rejected' => $application->status === 'rejected',
            ],
        ];

        return view('applications.show', compact('application', 'timeline'));
    }

    public function destroy(Application $application)
    {
        abort_unless($application->user_id === Auth::id(), 403);

        // Can only withdraw if not yet accepted/rejected
        if (in_array($application->status, ['accepted', 'rejected'])) {
            return back()->with('error', 'Lamaran ini tidak dapat ditarik.');
        }

        $application->delete();

        return redirect()->route('applications.index')
            ->with('success', 'Lamaran berhasil ditarik.');
    }
}
