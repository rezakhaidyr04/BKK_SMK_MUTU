<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Data notifikasi belum dibaca (JSON) untuk toast otomatis di layout.
     */
    public function poll(): JsonResponse
    {
        $user = auth()->user();
        $items = $user->unreadNotifications()->latest()->take(5)->get()->map(fn ($n) => [
            'id' => $n->id,
            'message' => $n->data['message'] ?? 'Ada pembaruan untuk Anda.',
            'time' => $n->created_at->diffForHumans(),
            'url' => route('notifications.go', $n->id),
        ])->values();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $items,
        ]);
    }

    public function markRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }

    /**
     * Klik satu notifikasi: tandai dibaca lalu arahkan ke halaman terkait
     * (lamaran / lowongan). URL luar diabaikan demi keamanan.
     */
    public function go(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? null;

        if (is_string($url) && $url !== '') {
            $appUrl = rtrim(config('app.url'), '/');
            if (str_starts_with($url, '/') || str_starts_with($url, $appUrl)) {
                return redirect()->to($url);
            }
        }

        return redirect()->route('notifications.index');
    }
}
