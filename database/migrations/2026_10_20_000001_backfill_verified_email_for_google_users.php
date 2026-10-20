<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaiki akun yang sudah pernah login dengan Google tapi
     * email_verified_at-nya null.
     *
     * Penyebab: SocialAuthController mengirim email_verified_at lewat
     * User::create(), padahal kolom itu TIDAK ada di User::$fillable
     * sehingga nilainya dibuang diam-diam oleh Eloquent. Akun Google
     * lama ikut tertinggal tidak terverifikasi.
     *
     * Aman: google_id hanya terisi setelah Google mengonfirmasi bahwa
     * email tersebut terverifikasi (email_verified === true).
     */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'google_id')) {
            return;
        }

        DB::table('users')
            ->whereNotNull('google_id')
            ->whereNull('email_verified_at')
            ->update([
                'email_verified_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Intentionally left as a no-op. Migration ini hanya mengisi
        // timestamp verifikasi akun yang sudah ditautkan ke Google.
    }
};
