<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Kirim ulang email verifikasi.
     *
     * Notifikasi dikirim lewat SMTP. Kalau kredensial SMTP salah atau server
     * mail sedang down, exception harus ditangkap di sini supaya tidak
     * menjadi HTTP 500 (halaman putih). Pengguna menerima pesan error
     * yang jelas beserta catatan bahwa admin perlu memperbaiki konfigurasi mail.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        try {
            $request->user()->sendEmailVerificationNotification();
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email verifikasi ke '.$request->user()->email.': '.get_class($e).': '.$e->getMessage());

            return back()->withErrors([
                'email' => 'Email verifikasi gagal dikirim. Silakan coba lagi beberapa saat lagi, '
                    .'atau hubungi admin BKK untuk bantuan.',
            ]);
        }

        return back()->with('status', 'verification-link-sent');
    }
}
