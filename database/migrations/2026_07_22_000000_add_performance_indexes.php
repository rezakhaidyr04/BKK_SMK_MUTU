<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('is_active');
            $table->index('email_verified_at');
        });

        // Add indexes to students table
        Schema::table('students', function (Blueprint $table) {
            $table->index('graduation_year');
            $table->index('major');
        });

        // Add indexes to alumni table
        Schema::table('alumni', function (Blueprint $table) {
            $table->index('graduation_year');
            $table->index('employment_status');
        });

        // Add indexes to companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->index('is_verified');
            $table->index('industry');
        });

        // Add additional indexes to jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->index('company_id');
            $table->index('deadline');
            $table->index('created_at');
            // Composite index for common query patterns
            $table->index(['status', 'job_type']);
        });

        // Add additional indexes to applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
            // Composite index for user's applications by status
            $table->index(['user_id', 'status']);
        });

        // Add indexes to events table (if exists)
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'type')) {
                    $table->index('type');
                }
                if (Schema::hasColumn('events', 'start_time')) {
                    $table->index('start_time');
                }
                if (Schema::hasColumn('events', 'status')) {
                    $table->index('status');
                }
            });
        }

        // Add indexes to event_registrations table (if exists)
        if (Schema::hasTable('event_registrations')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                if (Schema::hasColumn('event_registrations', 'user_id')) {
                    $table->index('user_id');
                }
                if (Schema::hasColumn('event_registrations', 'event_id')) {
                    $table->index('event_id');
                }
                if (Schema::hasColumn('event_registrations', 'status')) {
                    $table->index('status');
                }
            });
        }
    }

    public function down(): void
    {
        // Remove indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['email_verified_at']);
        });

        // Remove indexes from students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['graduation_year']);
            $table->dropIndex(['major']);
        });

        // Remove indexes from alumni table
        Schema::table('alumni', function (Blueprint $table) {
            $table->dropIndex(['graduation_year']);
            $table->dropIndex(['employment_status']);
        });

        // Remove indexes from companies table
        Schema::table('companies', function (Blueprint $table) {
            $table->dropIndex(['is_verified']);
            $table->dropIndex(['industry']);
        });

        // Remove additional indexes from jobs table
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['deadline']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status', 'job_type']);
        });

        // Remove additional indexes from applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id', 'status']);
        });

        // Remove indexes from events table (if exists)
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (Schema::hasColumn('events', 'type')) {
                    $table->dropIndex(['type']);
                }
                if (Schema::hasColumn('events', 'start_time')) {
                    $table->dropIndex(['start_time']);
                }
                if (Schema::hasColumn('events', 'status')) {
                    $table->dropIndex(['status']);
                }
            });
        }

        // Remove indexes from event_registrations table (if exists)
        if (Schema::hasTable('event_registrations')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                if (Schema::hasColumn('event_registrations', 'user_id')) {
                    $table->dropIndex(['user_id']);
                }
                if (Schema::hasColumn('event_registrations', 'event_id')) {
                    $table->dropIndex(['event_id']);
                }
                if (Schema::hasColumn('event_registrations', 'status')) {
                    $table->dropIndex(['status']);
                }
            });
        }
    }
};
