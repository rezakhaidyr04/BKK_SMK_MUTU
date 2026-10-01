<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // SKCK opsional (PDF) — pelamar boleh lampirkan, boleh tidak
            $table->string('skck_path')->nullable()->after('attachment_size');
            $table->string('skck_name')->nullable()->after('skck_path');
            $table->string('skck_mime')->nullable()->after('skck_name');
            $table->unsignedBigInteger('skck_size')->nullable()->after('skck_mime');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['skck_path', 'skck_name', 'skck_mime', 'skck_size']);
        });
    }
};
