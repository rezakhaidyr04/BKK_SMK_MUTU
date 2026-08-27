<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function ($table) {
            $table->text('address')->nullable()->after('bio');
            $table->string('preferred_position')->nullable()->after('address');
            $table->text('education_history')->nullable()->after('preferred_position');
            $table->text('experience_organization')->nullable()->after('education_history');
            $table->string('birth_place')->nullable()->after('experience_organization');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('gender')->nullable()->after('birth_date');
            $table->string('linkedin_url')->nullable()->after('gender');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
        });

        // Pindahkan data profil dari tabel students & alumni ke users.
        $students = DB::table('students')->get();
        foreach ($students as $student) {
            DB::table('users')
                ->where('id', $student->user_id)
                ->update([
                    'address' => $student->address,
                    'preferred_position' => $student->preferred_position,
                    'education_history' => $student->education_history,
                    'experience_organization' => $student->experience_organization,
                    'birth_place' => $student->birth_place,
                    'birth_date' => $student->birth_date,
                    'gender' => $student->gender,
                    'linkedin_url' => $student->linkedin_url,
                    'portfolio_url' => $student->portfolio_url,
                    'updated_at' => now(),
                ]);
        }

        $alumni = DB::table('alumni')->whereNotNull('experience')->get();
        foreach ($alumni as $alumnus) {
            DB::table('users')
                ->where('id', $alumnus->user_id)
                ->whereNull('experience_organization')
                ->update([
                    'experience_organization' => $alumnus->experience,
                    'updated_at' => now(),
                ]);
        }

        Schema::dropIfExists('students');
        Schema::dropIfExists('alumni');
    }

    public function down(): void
    {
        // Data profil yang sudah dipindah ke users tidak bisa dipulihkan
        // utuh karena kolom lama (nisn, major, dsb.) sudah tidak relevan.
    }
};
