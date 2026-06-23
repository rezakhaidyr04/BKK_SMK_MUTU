<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string', 'in:job_fair,seminar,workshop,pelatihan,lainnya'],
            'description' => ['required', 'string'],
            'start_time'  => ['required', 'date'],
            'end_time'    => ['nullable', 'date', 'after:start_time'],
            'location'    => ['required', 'string', 'max:255'],
            'poster'      => ['nullable', 'image', 'max:3072', 'mimes:jpg,jpeg,png,webp'],
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('event-posters', 'public');
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Acara berhasil dibuat.');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string', 'in:job_fair,seminar,workshop,pelatihan,lainnya'],
            'description' => ['required', 'string'],
            'start_time'  => ['required', 'date'],
            'end_time'    => ['nullable', 'date', 'after:start_time'],
            'location'    => ['required', 'string', 'max:255'],
            'poster'      => ['nullable', 'image', 'max:3072', 'mimes:jpg,jpeg,png,webp'],
        ]);

        if ($request->hasFile('poster')) {
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }
            $validated['poster'] = $request->file('poster')->store('event-posters', 'public');
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
}
