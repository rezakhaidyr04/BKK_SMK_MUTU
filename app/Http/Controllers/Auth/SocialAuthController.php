<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Lempar ke Google. Gagal-konfig (kunci kosong) → kembali dengan pesan jelas.
     */
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.client_id') || ! config('services.google.client_secret')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Login Google belum dikonfigurasi. Silakan masuk dengan email & kata sandi.',
            ]);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback Google: cari by google_id → tautkan by email → buat baru (umum).
     */
    public function callback(): RedirectResponse
    {
        try {
            $google = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            Log::warning('Google callback gagal: '.$e->getMessage());

            return redirect()->route('login')->withErrors([
                'email' => 'Login Google gagal. Coba lagi atau masuk dengan email & kata sandi.',
            ]);
        }

        if (! $google->getEmail()) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Google Anda tidak membagikan email.',
            ]);
        }

        // 1. Sudah pernah taut → langsung masuk (tolak akun nonaktif).
        $user = User::where('google_id', $google->getId())->first();

        if ($user && ! $user->is_active) {
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda dinonaktifkan. Hubungi tim BKK.',
            ]);
        }

        // 2. Email sudah terdaftar → tautkan google_id (tanpa ubah role).
        if (! $user) {
            $user = User::where('email', $google->getEmail())->first();

            if ($user) {
                if (! $user->is_active) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Akun Anda dinonaktifkan. Hubungi tim BKK.',
                    ]);
                }
                $user->forceFill(['google_id' => $google->getId()])->save();
            }
        }

        // 3. Baru total → buat sebagai umum (email Google = terverifikasi).
        if (! $user) {
            $user = User::create([
                'name' => $google->getName() ?: Str::before($google->getEmail(), '@'),
                'email' => $google->getEmail(),
                'password' => Hash::make(Str::random(40)),
                'role' => 'umum',
                'is_active' => true,
                'google_id' => $google->getId(),
                'email_verified_at' => now(),
            ]);
            $user->syncRoles(['umum']);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
