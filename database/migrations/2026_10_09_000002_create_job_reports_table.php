<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * C1: laporan lowongan mencurigakan. Satu user satu laporan per lowongan
     * (unique) agar tidak bisa di-spam; race ditangani controller via 1062.
     */
    public function up(): void
    {
        Schema::create('job_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('reason', ['penipuan', 'pungutan', 'info_palsu', 'diskriminasi', 'lainnya'])->index();
            $table->text('detail')->nullable();
            $table->enum('status', ['menunggu', 'ditindak', 'ditolak'])->default('menunggu')->index();
            $table->timestamps();

            $table->unique(['job_id', 'user_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_reports');
    }
};
