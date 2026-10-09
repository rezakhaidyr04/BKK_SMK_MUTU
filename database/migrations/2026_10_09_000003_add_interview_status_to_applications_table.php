<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * D2: status konfirmasi kehadiran wawancara.
     * menunggu = default saat dijadwalkan/di-reschedule; dikonfirmasi/ditolak
     * oleh pelamar; selesai = penanda riwayat saat status keluar interviewed
     * (kolom interview lain tetap di-null seperti perilaku existing).
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->enum('interview_status', ['menunggu', 'dikonfirmasi', 'ditolak', 'selesai'])
                ->nullable()
                ->after('interview_notes');
            $table->index('interview_status');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['interview_status']);
            $table->dropColumn('interview_status');
        });
    }
};
