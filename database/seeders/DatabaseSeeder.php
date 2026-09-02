<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndAdminSeeder::class,
            CompanyRolePermissionSeeder::class,
            DummyDataSeeder::class,
            EventAndNewsSeeder::class,
            ReviewSeeder::class,
        ]);

        // Create sample user Ahmad Nur Harry for CV preview
        try {
            $user = \App\Models\User::updateOrCreate([
                'email' => 'harry.nur@umum.bkk.com'
            ], [
                'name' => 'Ahmad nur harry',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'umum',
                'phone' => '081234560002',
                'bio' => 'Lulusan SMK Akuntansi dengan pengalaman magang dan keahlian Microsoft Office serta pembukuan sederhana.',
                'address' => 'Jl. Anggrek No. 12, Karawang',
                'linkedin_url' => 'https://linkedin.com/in/ahmadnurharry',
                'portfolio_url' => 'https://ahmad-harry.example.com',
                'preferred_position' => 'Akuntan Junior / Admin Keuangan',
                'education_history' => "SD Negeri 1 Cikampek\nSMP Negeri 2 Cikampek\nSMK MUTU Cikampek (Akuntansi)",
                'experience_organization' => "Magang di Toko ABC sebagai admin keuangan (3 bulan)\nBendahara OSIS (1 tahun)",
                'birth_place' => 'Karawang',
                'birth_date' => '2004-06-15',
                'gender' => 'Laki-laki',
            ]);

            $skills = ['Microsoft Excel', 'Pembukuan', 'Administrasi Perkantoran', 'Komunikasi'];
            $skillIds = [];
            foreach ($skills as $name) {
                $s = \App\Models\Skill::firstOrCreate(['name' => $name]);
                $skillIds[$s->id] = ['proficiency' => 4];
            }
            $user->skills()->sync($skillIds);

            // Certificates
            if (class_exists(\App\Models\Certificate::class)) {
                \App\Models\Certificate::updateOrCreate([
                    'user_id' => $user->id,
                    'title' => 'Sertifikat Microsoft Excel Dasar'
                ], [
                    'issuer' => 'Pusat Pelatihan Lokal',
                    'issue_date' => now()->subYear()->toDateString(),
                    'file_path' => '',
                ]);
            }

            // Generate sample CV PDF (modern)
            try {
                $user->load('skills', 'certificates');
                $cvData = (new \App\Http\Controllers\CvBuilderController)->buildPreviewData($user);
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cv.templates.modern', array_merge($cvData, [
                    'user' => $user,
                    'include_photo' => true,
                    'include_skills' => true,
                    'include_certificates' => true,
                    'custom_headline' => $cvData['headline'],
                    'custom_summary' => $cvData['summary'],
                    'custom_experience' => $cvData['experience'],
                ]))->setPaper('a4', 'portrait');

                $fileName = 'cv-files/ahmad_nur_harry_modern.pdf';
                \Illuminate\Support\Facades\Storage::disk('private')->put($fileName, $pdf->output());

                if (class_exists(\App\Models\CvFile::class)) {
                    \App\Models\CvFile::updateOrCreate([
                        'user_id' => $user->id,
                        'file_path' => $fileName,
                    ], [
                        'is_ats_friendly' => false,
                    ]);
                }
            } catch (\Throwable $e) {
                \Log::warning('Seeder: CV generation failed: ' . $e->getMessage());
            }

        } catch (\Throwable $e) {
            \Log::warning('Seeder: Ahmad creation failed: ' . $e->getMessage());
        }
    }
}
