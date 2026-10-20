<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Identitas & kontak untuk tracer study.
     *
     * - nisn  : Nomor Induk Siswa Nasional, KUNCI data alumni (KPI sekolah).
     *            Hanya diisi oleh alumni.
     * - email : email peserta tracer, untuk DOCUMENTS/follow-up.
     *
     * Nomor telepon tetap memakai kolom no_wa yang sudah ada (dipakai juga
     * sebagai nomor HP / WhatsApp), jadi tidak ada kolom nomor dobel.
     */
    public function up(): void
    {
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->string('nisn', 20)->nullable()->after('is_alumni')->index();
            $table->string('email', 255)->nullable()->after('asal_sekolah')->index();
        });
    }

    public function down(): void
    {
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->dropIndex(['nisn']);
            $table->dropIndex(['email']);
            $table->dropColumn(['nisn', 'email']);
        });
    }
};
