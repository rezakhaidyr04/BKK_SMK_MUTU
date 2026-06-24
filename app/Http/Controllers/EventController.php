<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::withCount('registrations');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->get('filter') === 'upcoming') {
            $query->where('start_time', '>=', now());
        } elseif ($request->get('filter') === 'past') {
            $query->where('start_time', '<', now());
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(12);

        // Tandai acara mana yang sudah didaftarkan user yang login
        $registeredIds = [];
        if (Auth::check()) {
            $registeredIds = EventRegistration::where('user_id', Auth::id())
                ->where('status', 'registered')
                ->pluck('event_id')
                ->toArray();
        }

        return view('events.index', compact('events', 'registeredIds'));
    }

    public function show(Event $event)
    {
        $event->loadCount('registrations');

        $registration = null;
        if (Auth::check()) {
            $registration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('events.show', compact('event', 'registration'));
    }

    public function register(Request $request, Event $event)
    {
        // Pastikan acara belum selesai
        if ($event->start_time->isPast()) {
            return back()->with('error', 'Acara ini sudah selesai, pendaftaran ditutup.');
        }

        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                // Daftar ulang
                $existing->update(['status' => 'registered', 'registered_at' => now()]);
                return back()->with('success', 'Kamu berhasil mendaftar ulang untuk acara ini!');
            }
            return back()->with('error', 'Kamu sudah terdaftar di acara ini.');
        }

        EventRegistration::create([
            'event_id'      => $event->id,
            'user_id'       => Auth::id(),
            'status'        => 'registered',
            'notes'         => $request->notes,
            'registered_at' => now(),
        ]);

        return back()->with('success', 'Pendaftaran berhasil! Sampai jumpa di acara "' . $event->title . '".');
    }

    public function cancel(Event $event)
    {
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', 'registered')
            ->firstOrFail();

        $registration->update(['status' => 'cancelled']);

        return back()->with('success', 'Pendaftaran acara berhasil dibatalkan.');
    }

    public function myEvents()
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', Auth::id())
            ->orderByDesc('registered_at')
            ->paginate(12);

        return view('events.my-events', compact('registrations'));
    }
}
