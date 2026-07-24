<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'upcoming') {
                $query->where('start_date', '>=', now());
            } elseif ($request->status === 'past') {
                $query->where('end_date', '<', now());
            }
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        $events = $query->orderBy('start_date', 'desc')->paginate(20);
        
        // Get statistics
        $stats = [
            'total_events' => Event::count(),
            'upcoming' => Event::where('start_date', '>=', now())->count(),
            'total_registrations' => EventRegistration::count(),
            'student_participation' => EventRegistration::distinct('user_id')->count(),
        ];
        
        return view('dashboard.teacher-events', compact('events', 'stats'));
    }
}
