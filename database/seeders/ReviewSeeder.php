<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users or create test users
        $users = User::where('role', 'umum')->limit(10)->get();

        if ($users->isEmpty()) {
            // Create test users if none exist
            $users = User::factory()->count(3)->create(['role' => 'umum']);
        }

        $reviews = [
            [
                'rating' => 5,
                'comment' => 'BKK SMK MUTU membantu saya menemukan pekerjaan impian sebagai Web Developer di PT Teknologi Nusantara. Platformnya sangat mudah digunakan!',
                'job_title' => 'Web Developer',
                'company_name' => 'PT Teknologi Nusantara',
                'name' => 'Ahmad Rizky',
                'email' => 'ahmad.rizky@email.com',
                'phone' => '08123456789',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'comment' => 'Fitur pembuat CV-nya luar biasa! CV saya sekarang ramah ATS dan banyak perusahaan yang menghubungi. Terima kasih BKK SMK MUTU!',
                'job_title' => 'Admin Staff',
                'company_name' => 'PT Maju Bersama',
                'name' => 'Siti Permata',
                'email' => 'siti.permata@email.com',
                'phone' => '08234567890',
                'status' => 'approved',
                'featured' => true,
            ],
            [
                'rating' => 5,
                'comment' => 'Proses pencarian kerja jadi sangat mudah. Dari daftar sampai diterima kerja, semuanya terpantau di satu platform. Sangat membantu!',
                'job_title' => 'Operator Produksi',
                'company_name' => 'PT Yamaha Motor',
                'name' => 'Dedi Wahyudi',
                'email' => 'dedi.wahyudi@email.com',
                'phone' => '08345678901',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'comment' => 'Sangat terbantu dengan platform ini. Interview tips dan skill development courses-nya sangat bermanfaat untuk persiapan karir.',
                'job_title' => 'Business Analyst',
                'company_name' => 'PT Digital Indonesia',
                'name' => 'Ratna Sari',
                'email' => 'ratna.sari@email.com',
                'phone' => '08456789012',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'comment' => 'Networking opportunities yang tersedia di platform ini sangat luas. Saya bisa terhubung dengan professionals di berbagai industri.',
                'job_title' => 'Marketing Executive',
                'company_name' => 'PT Media Kreatif',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '08567890123',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 4,
                'comment' => 'Platform yang sangat membantu dalam pencarian kerja. Interface user-friendly dan fitur-fiturnya lengkap. Recommended!',
                'job_title' => 'Senior Developer',
                'company_name' => 'PT Software Solutions',
                'name' => 'Eka Putri',
                'email' => 'eka.putri@email.com',
                'phone' => '08678901234',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'comment' => 'Bantu banget! Interview coaching dari mentor-mentor mereka sangat berkualitas dan relevance dengan industri terkini.',
                'job_title' => 'UI/UX Designer',
                'company_name' => 'PT Creative Studio',
                'name' => 'Fajar Rizaldi',
                'email' => 'fajar.rizaldi@email.com',
                'phone' => '08789012345',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'comment' => 'Proses rekrutmen yang transparan dan fair. Feedback yang diberikan membantu saya improve skill untuk posisi selanjutnya.',
                'job_title' => 'Data Analyst',
                'company_name' => 'PT Data Science Co',
                'name' => 'Gina Kumala',
                'email' => 'gina.kumala@email.com',
                'phone' => '08890123456',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 5,
                'comment' => 'Customer service mereka responsif dan helpful. Setiap pertanyaan saya dijawab dengan cepat dan profesional.',
                'job_title' => 'HR Manager',
                'company_name' => 'PT Consulting Group',
                'name' => 'Hendra Wijaya',
                'email' => 'hendra.wijaya@email.com',
                'phone' => '08901234567',
                'status' => 'approved',
                'featured' => false,
            ],
            [
                'rating' => 4,
                'comment' => 'Lowongan yang tersedia sangat beragam dan sesuai dengan skill saya. Update job listings-nya juga cukup sering.',
                'job_title' => 'QA Engineer',
                'company_name' => 'PT Quality Assurance',
                'name' => 'Indri Kusuma',
                'email' => 'indri.kusuma@email.com',
                'phone' => '08912345678',
                'status' => 'approved',
                'featured' => false,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create(array_merge(
                $review,
                ['user_id' => $users->random()->id]
            ));
        }
    }
}
