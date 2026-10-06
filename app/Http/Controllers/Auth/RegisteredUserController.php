<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view("auth.register");
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"]),
            "role" => "umum",
            "is_active" => true,
            "email_verified_at" => null,
        ]);

        $user->syncRoles(['umum']);

        // L7: SMTP down JANGAN menyebabkan 500 setelah user tercatat.
        // Akun tetap jadi + login; user diminta kirim ulang verifikasi
        // (route verification.send). Timeout SMTP dibatasi MAIL_TIMEOUT.
        try {
            event(new Registered($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email verifikasi registrasi gagal: ' . $e->getMessage(), [
                'user_id' => $user->id,
            ]);
            Auth::login($user);

            return redirect(RouteServiceProvider::HOME)->with(
                'warning',
                'Akun berhasil dibuat, tetapi email verifikasi gagal dikirim. Silakan minta tautan baru dari halaman verifikasi.'
            );
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}