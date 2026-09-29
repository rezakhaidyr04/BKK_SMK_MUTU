<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom dinamis untuk detail lowongan agar jam kerja, benefit,
     * dan persyaratan mengikuti data input perusahaan (temuan testing #4).
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('education')->nullable()->after('qualifications');
            $table->string('experience')->nullable()->after('education');
            $table->string('gender')->nullable()->after('experience');
            $table->string('age_range')->nullable()->after('gender');
            $table->string('work_hours')->nullable()->after('age_range');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['education', 'experience', 'gender', 'age_range', 'work_hours']);
        });
    }
};
