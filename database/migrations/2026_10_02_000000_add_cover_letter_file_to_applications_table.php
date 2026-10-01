<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // File surat lamaran PDF wajib (ganti textarea ketik)
            $table->string('cover_letter_path')->nullable()->after('cover_letter');
            $table->string('cover_letter_name')->nullable()->after('cover_letter_path');
            $table->string('cover_letter_mime')->nullable()->after('cover_letter_name');
            $table->unsignedBigInteger('cover_letter_size')->nullable()->after('cover_letter_mime');
        });

        // cover_letter teks lama biarkan nullable untuk data lama (sudah nullable sejak awal)
        // Tidak perlu ubah tipe, hanya pastikan nullable.
        try {
            Schema::table('applications', function (Blueprint $table) {
                $table->text('cover_letter')->nullable()->change();
            });
        } catch (\Throwable $e) {
            // abaikan jika driver tidak support change() tanpa doctrine/dbal
        }
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'cover_letter_path',
                'cover_letter_name',
                'cover_letter_mime',
                'cover_letter_size',
            ]);
        });
    }
};
