<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by upcoming/past
        if ($request->get('filter') === 'upcoming') {
            $query->where('start_time', '>=', now());
        } elseif ($request->get('filter') === 'past') {
            $query->where('start_time', '<', now());
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }
}
