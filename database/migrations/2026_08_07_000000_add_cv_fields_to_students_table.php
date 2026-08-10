<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (!Schema::hasColumn('students', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('address');
            }
            if (!Schema::hasColumn('students', 'portfolio_url')) {
                $table->string('portfolio_url')->nullable()->after('linkedin_url');
            }
            if (!Schema::hasColumn('students', 'preferred_position')) {
                $table->string('preferred_position')->nullable()->after('portfolio_url');
            }
            if (!Schema::hasColumn('students', 'education_history')) {
                $table->text('education_history')->nullable()->after('preferred_position');
            }
            if (!Schema::hasColumn('students', 'experience_organization')) {
                $table->text('experience_organization')->nullable()->after('education_history');
            }
            if (!Schema::hasColumn('students', 'birth_place')) {
                $table->string('birth_place')->nullable()->after('experience_organization');
            }
            if (!Schema::hasColumn('students', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('birth_place');
            }
            if (!Schema::hasColumn('students', 'gender')) {
                $table->string('gender')->nullable()->after('birth_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['linkedin_url', 'portfolio_url', 'preferred_position', 'education_history', 'experience_organization', 'birth_place', 'birth_date', 'gender']);
        });
    }
};
