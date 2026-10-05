<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Index tambahan untuk pola WHERE/ORDER yang sering dipakai.
 * Murni aditif (tanpa ubah/hapus kolom atau data).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->index(['status', 'deadline'], 'jobs_status_deadline_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'applications_user_status_index');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'event_registrations_user_status_index');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->index('verification_status', 'companies_verification_status_index');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex('jobs_status_deadline_index');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex('applications_user_status_index');
        });

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropIndex('event_registrations_user_status_index');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex('companies_verification_status_index');
        });
    }
};
