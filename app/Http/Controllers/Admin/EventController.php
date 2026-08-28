<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminEventStoreRequest;
use App\Http\Requests\AdminEventUpdateRequest;
use App\Models\Event;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::latest('start_time');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('filter') === 'upcoming') {
            $query->where('start_time', '>=', now());
        }

        $events = $query->paginate(15)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(AdminEventStoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('poster')) {
            $processor = new ImageProcessor(quality: 82, maxWidth: 1200, maxHeight: 900);
            $validated['poster'] = $processor->store(
                $request->file('poster'),
                'event-posters',
                'poster-' . time()
            );
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(AdminEventUpdateRequest $request, Event $event)
    {
        $validated = $request->validated();

        if ($request->hasFile('poster')) {
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            $processor = new ImageProcessor(quality: 82, maxWidth: 1200, maxHeight: 900);
            $validated['poster'] = $processor->store(
                $request->file('poster'),
                'event-posters',
                'poster-' . time()
            );
        } else {
            unset($validated['poster']);
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil dihapus.');
    }

    public function registrants(Event $event)
    {
        $registrations = $event->registrations()
            ->with('user')
            ->orderByDesc('registered_at')
            ->paginate(20);

        return view('admin.events.registrants', compact('event', 'registrations'));
    }
}
