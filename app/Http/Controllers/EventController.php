<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        if ($event->start_time->isPast()) {
            return back()->with('error', 'Acara ini sudah selesai, pendaftaran ditutup.');
        }

        // Kuota
        if ($event->quota && $event->registrations()->where('status', 'registered')->count() >= $event->quota) {
            return back()->with('error', 'Kuota peserta sudah penuh.');
        }

        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $paymentStatus = $event->isPaid() ? 'unpaid' : 'verified';
                $existing->update([
                    'status' => 'registered',
                    'registered_at' => now(),
                    'payment_status' => $paymentStatus,
                    'notes' => $request->notes,
                ]);
                if ($event->isPaid()) {
                    return back()->with('success', 'Berhasil mendaftar ulang! Silakan lakukan pembayaran ' . $event->formattedPrice() . ' dan upload bukti di halaman ini.');
                }
                return back()->with('success', 'Kamu berhasil mendaftar ulang untuk acara ini!');
            }
            return back()->with('error', 'Kamu sudah terdaftar di acara ini.');
        }

        $paymentStatus = $event->isPaid() ? 'unpaid' : 'verified';

        EventRegistration::create([
            'event_id'       => $event->id,
            'user_id'        => Auth::id(),
            'status'         => 'registered',
            'payment_status' => $paymentStatus,
            'notes'          => $request->notes,
            'registered_at'  => now(),
        ]);

        if ($event->isPaid()) {
            return back()->with('success', 'Pendaftaran awal berhasil! Silakan lakukan pembayaran ' . $event->formattedPrice() . ' lalu upload bukti transfer di bawah.');
        }

        return back()->with('success', 'Pendaftaran berhasil! Sampai jumpa di acara "' . $event->title . '".');
    }

    public function uploadPaymentProof(Request $request, Event $event)
    {
        $request->validate([
            'payment_proof' => ['required', 'image', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->where('status', 'registered')
            ->firstOrFail();

        if (!$event->isPaid()) {
            return back()->with('error', 'Acara ini gratis, tidak perlu bukti pembayaran.');
        }

        if ($registration->payment_status === 'verified') {
            return back()->with('error', 'Pembayaran sudah terverifikasi.');
        }

        if ($registration->payment_proof) {
            Storage::disk('public')->delete($registration->payment_proof);
        }

        $path = $request->file('payment_proof')->store('event-payments', 'public');

        $registration->update([
            'payment_proof' => $path,
            'payment_status' => 'pending',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin (1-2 jam kerja).');
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
