<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use PDF;

class AhmadNurHarrySeeder extends Seeder
{
    public function run(): void
    {
        $userModel = \App\Models\User::class;
        $skillModel = \App\Models\Skill::class;
        $studentModel = \App\Models\Student::class;
        $certificateModel = \App\Models\Certificate::class;
        $cvFileModel = \App\Models\CvFile::class;

        // Create or update user
        $user = $userModel::updateOrCreate([
            'email' => 'harry.nur@siswa.bkk.com'
        ], [
            'name' => 'Ahmad nur harry',
            'password' => Hash::make('password123'),
            'role' => 'student',
            'phone' => '081234560002',
            'bio' => 'Lulusan SMK Akuntansi dengan pengalaman magang dan keahlian Microsoft Office serta pembukuan sederhana.',
            'avatar' => null,
        ]);

        // Student profile
        $studentData = [
            'nisn' => '0001234567',
            'major' => 'Akuntansi',
            'graduation_year' => 2024,
            'address' => 'Jl. Anggrek No. 12, Karawang',
            'linkedin_url' => 'https://linkedin.com/in/ahmadnurharry',
            'portfolio_url' => 'https://ahmad-harry.example.com',
            'preferred_position' => 'Akuntan Junior / Admin Keuangan',
            'education_history' => "SD Negeri 1 Cikampek\nSMP Negeri 2 Cikampek\nSMK MUTU Cikampek (Akuntansi)",
            'experience_organization' => "Magang di Toko ABC sebagai admin keuangan (3 bulan)\nBendahara OSIS (1 tahun)",
            'birth_place' => 'Karawang',
            'birth_date' => '2004-06-15',
            'gender' => 'Laki-laki',
        ];

        // Ensure student record is created or updated without causing unique conflicts
        \App\Models\Student::updateOrCreate([
            'user_id' => $user->id,
        ], $studentData);

        // Skills
        $skills = ['Microsoft Excel', 'Pembukuan', 'Administrasi Perkantoran', 'Komunikasi'];
        $skillIds = [];
        foreach ($skills as $name) {
            $s = $skillModel::firstOrCreate(['name' => $name]);
            $skillIds[$s->id] = ['proficiency' => 4];
        }
        $user->skills()->sync($skillIds);

        // Certificates (if model exists)
        if (class_exists($certificateModel)) {
            $certificateModel::updateOrCreate([
                'user_id' => $user->id,
                'title' => 'Sertifikat Microsoft Excel Dasar'
            ], [
                'issuer' => 'Pusat Pelatihan Lokal',
                'issue_date' => now()->subYears(1)->toDateString(),
                'file_path' => '',
            ]);
        }

        // Generate sample PDF for preview (modern)
        try {
            $user->load('student', 'skills', 'certificates');

            $cvData = (new \App\Http\Controllers\CvBuilderController)->buildPreviewData($user);

            $pdf = PDF::loadView('cv.templates.modern', array_merge($cvData, [
                'user' => $user,
                'include_photo' => true,
                'include_skills' => true,
                'include_certificates' => true,
                'custom_headline' => $cvData['headline'],
                'custom_summary' => $cvData['summary'],
                'custom_experience' => $cvData['experience'],
            ]))->setPaper('a4', 'portrait');

            $fileName = 'cv/ahmad_nur_harry_modern.pdf';
            Storage::disk('public')->put($fileName, $pdf->output());

            // record CvFile if model exists
            if (class_exists($cvFileModel)) {
                $cvFileModel::updateOrCreate([
                    'user_id' => $user->id,
                    'file_path' => $fileName,
                ], [
                    'is_ats_friendly' => false,
                ]);
            }
        } catch (\Throwable $e) {
            // don't fail seeder if PDF generation not available
            \Log::warning('Seeder: could not generate CV PDF sample: ' . $e->getMessage());
        }
    }
}
