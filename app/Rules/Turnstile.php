<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * G1: validasi Cloudflare Turnstile.
 * - Kunci belum diisi + production → GAGAL (fail-closed, anti lupa konfig).
 * - Kunci belum diisi + local/testing → lolos (kemudahan dev & test).
 * - Gagal jaringan ke Cloudflare → lolos + warning log (fail-open agar
 *   auth tidak mati total saat insiden pihak ketiga; bot masih tertahan
 *   rate-limit + throttle bawaan).
 */
class Turnstile implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret_key');

        if (! $secret) {
            if (app()->environment('production')) {
                $fail('Verifikasi keamanan belum dikonfigurasi. Hubungi administrator.');
            }

            return;
        }

        if (! is_string($value) || $value === '') {
            $fail('Mohon selesaikan verifikasi keamanan.');

            return;
        }

        try {
            $response = Http::timeout(10)->asForm()->post(
                'https://challenges.cloudflare.com/turnstile/v0/siteverify',
                [
                    'secret' => $secret,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]
            );

            if (! ($response->json('success') ?? false)) {
                $fail('Verifikasi keamanan gagal. Silakan coba lagi.');
            }
        } catch (\Throwable $e) {
            Log::warning('Turnstile siteverify gagal: '.$e->getMessage());
        }
    }
}
