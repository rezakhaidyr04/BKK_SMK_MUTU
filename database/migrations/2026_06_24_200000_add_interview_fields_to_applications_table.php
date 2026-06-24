<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dateTime('interview_date')->nullable()->after('status');
            $table->string('interview_location')->nullable()->after('interview_date');
            $table->string('interview_type')->nullable()->after('interview_location'); // online / offline
            $table->string('interview_link')->nullable()->after('interview_type');     // link zoom/meet jika online
            $table->text('interview_notes')->nullable()->after('interview_link');      // catatan tambahan dari perusahaan
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'interview_date',
                'interview_location',
                'interview_type',
                'interview_link',
                'interview_notes',
            ]);
        });
    }
};
