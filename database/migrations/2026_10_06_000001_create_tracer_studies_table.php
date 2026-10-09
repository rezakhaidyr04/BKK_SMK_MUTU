<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tracer Study alumni — satu baris per user (umum).
     * Dipakai untuk KPI BKK: % bekerja/kuliah/wirausaha + keselarasan jurusan.
     */
    public function up(): void
    {
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->enum('status_kerja', ['bekerja', 'kuliah', 'wirausaha', 'menganggur'])->index();
            // Nama perusahaan / kampus / usaha — label dinamis di form.
            $table->string('company_name', 150)->nullable();
            // Jabatan / program studi / bidang usaha.
            $table->string('position', 100)->nullable();
            // Rentang penghasilan bulanan (enum agar gampang direkap).
            $table->enum('salary_range', ['<3jt', '3-5jt', '5-10jt', '>10jt', 'rahasia'])->nullable();
            // Apakah pekerjaan/kuliah/usaha selaras dengan jurusan SMK?
            $table->boolean('is_relevant')->nullable();
            $table->unsignedSmallInteger('tahun_lulus')->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('no_wa', 20)->nullable();
            $table->timestamp('filled_at')->nullable();
            $table->timestamps();

            $table->index('tahun_lulus');
            $table->index('filled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
