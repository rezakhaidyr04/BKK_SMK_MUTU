<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tracer untuk umum non-alumni: tandai alumni + asal sekolah.
     * Default true agar baris lama (diisi sebelum kolom ada) tetap alumni.
     */
    public function up(): void
    {
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->boolean('is_alumni')->default(true)->after('user_id');
            $table->string('asal_sekolah', 150)->nullable()->after('jurusan');
            $table->index('is_alumni');
        });
    }

    public function down(): void
    {
        Schema::table('tracer_studies', function (Blueprint $table) {
            $table->dropIndex(['is_alumni']);
            $table->dropColumn(['is_alumni', 'asal_sekolah']);
        });
    }
};
