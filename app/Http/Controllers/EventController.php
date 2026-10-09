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
        // Hanya pencari kerja (umum) yang boleh mendaftar — selaras jobs.apply.
        abort_unless($request->user()->role === 'umum', 403);

        $request->validate([
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($event->start_time->isPast()) {
            return back()->with('error', 'Acara ini sudah selesai, pendaftaran ditutup.');
        }

        // Transaksi + kunci baris event: cek kuota dan insert atomik agar
        // request paralel tidak overbook.
        try {
            $outcome = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $event) {
                $locked = \App\Models\Event::whereKey($event->id)->lockForUpdate()->firstOrFail();

                if ($locked->quota && $locked->registrations()->where('status', 'registered')->count() >= $locked->quota) {
                    return 'full';
                }

                $existing = EventRegistration::where('event_id', $locked->id)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($existing) {
                    if ($existing->status === 'cancelled') {
                        $paymentStatus = $locked->isPaid() ? 'unpaid' : 'verified';
                        $data = [
                            'status' => 'registered',
                            'registered_at' => now(),
                            'payment_status' => $paymentStatus,
                            'notes' => $request->notes,
                        ];
                        // Daftar ulang acara berbayar = bukti lama tidak berlaku.
                        if ($locked->isPaid()) {
                            if ($existing->payment_proof) {
                                Storage::disk('private')->delete($existing->payment_proof);
                                Storage::disk('public')->delete($existing->payment_proof);
                            }
                            $data['payment_proof'] = null;
                            $data['paid_at'] = null;
                        }
                        $existing->update($data);

                        return $locked->isPaid() ? 're-registered-paid' : 're-registered';
                    }

                    return 'already';
                }

                $paymentStatus = $locked->isPaid() ? 'unpaid' : 'verified';

                EventRegistration::create([
                    'event_id'       => $locked->id,
                    'user_id'        => Auth::id(),
                    'status'         => 'registered',
                    'payment_status' => $paymentStatus,
                    'notes'          => $request->notes,
                    'registered_at'  => now(),
                ]);

                return $locked->isPaid() ? 'registered-paid' : 'registered';
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // L4: race double-submit menabrak unique(event_id,user_id) → pesan ramah.
            return back()->with('error', 'Kamu sudah terdaftar di acara ini.');
        }

        return match ($outcome) {
            'full' => back()->with('error', 'Kuota peserta sudah penuh.'),
            'already' => back()->with('error', 'Kamu sudah terdaftar di acara ini.'),
            're-registered-paid' => back()->with('success', 'Berhasil mendaftar ulang! Silakan lakukan pembayaran ' . $event->formattedPrice() . ' dan upload bukti di halaman ini.'),
            're-registered' => back()->with('success', 'Kamu berhasil mendaftar ulang untuk acara ini!'),
            'registered-paid' => back()->with('success', 'Pendaftaran awal berhasil! Silakan lakukan pembayaran ' . $event->formattedPrice() . ' lalu upload bukti transfer di bawah.'),
            default => back()->with('success', 'Pendaftaran berhasil! Sampai jumpa di acara "' . $event->title . '".'),
        };
    }

    public function uploadPaymentProof(Request $request, Event $event)
    {
        $request->validate([
            'payment_proof' => ['required', 'image', 'max:4096', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp'],
        ], [
            'payment_proof.mimes' => 'Bukti pembayaran wajib berformat JPG, PNG, atau WebP.',
            'payment_proof.mimetypes' => 'Bukti pembayaran wajib berformat JPG, PNG, atau WebP.',
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
            // Hapus file lama milik sendiri (termasuk sisa era public-disk).
            Storage::disk('private')->delete($registration->payment_proof);
            Storage::disk('public')->delete($registration->payment_proof);
        }

        // Dokumen finansial wajib di private disk — diakses via controller berotorisasi.
        $path = $request->file('payment_proof')->store('event-payments', 'private');

        $registration->update([
            'payment_proof' => $path,
            'payment_status' => 'pending',
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin (1-2 jam kerja).');
    }

    public function downloadPaymentProof(Request $request, \App\Models\EventRegistration $registration)
    {
        $user = Auth::user();
        abort_unless(
            $user->id === $registration->user_id || $user->role === 'admin',
            403,
            'Anda tidak berhak melihat bukti pembayaran ini.'
        );

        abort_unless($registration->payment_proof, 404, 'Bukti pembayaran tidak ditemukan.');
        abort_unless(Storage::disk('private')->exists($registration->payment_proof), 404, 'File bukti pembayaran tidak ditemukan.');

        $filename = 'bukti-pembayaran-' . $registration->id . '.' . pathinfo($registration->payment_proof, PATHINFO_EXTENSION);

        if ($request->query('preview')) {
            $mime = Storage::disk('private')->mimeType($registration->payment_proof);

            return Storage::disk('private')->response(
                $registration->payment_proof,
                $filename,
                [
                    'Content-Type' => $mime,
                    'Content-Disposition' => 'inline; filename="' . \App\Support\Mask::filename($filename) . '"',
                ]
            );
        }

        return Storage::disk('private')->download($registration->payment_proof, $filename);
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
