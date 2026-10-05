<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Aturan konsistensi (fix rating > pengguna):
     * - Jumlah review TIDAK boleh melebihi jumlah user umum.
     * - Setiap review wajib ter-link ke user asli (pakai nama/email user,
     *   bukan nama fake) dan ke perusahaan yang benar-benar ada.
     * - Idempotent via updateOrCreate agar re-seed tidak duplikat.
     * - Bersihkan review yatim (user_id null karena user dihapus).
     */
    public function run(): void
    {
        // Bersihkan review yatim agar statistik jujur.
        Review::whereNull('user_id')->forceDelete();

        $users = User::where('role', 'umum')->orderBy('id')->get();

        if ($users->isEmpty()) {
            $users = User::factory()->count(3)->create(['role' => 'umum']);
        }

        $companyNames = \App\Models\Company::orderBy('id')->pluck('name', 'id');
        $companyList = $companyNames->values()->all();

        $templates = [
            [5, 'Platform BKKMU sangat membantu saya menemukan pekerjaan yang sesuai. Proses lamar sampai interview terpantau jelas di satu tempat.'],
            [5, 'Fitur pembuat CV-nya luar biasa! CV saya jadi ramah ATS dan beberapa perusahaan langsung menghubungi saya.', true],
            [5, 'Proses pencarian kerja jadi jauh lebih mudah. Lowongannya relevan untuk lulusan SMK dan update setiap hari.'],
            [4, 'Interface mudah dipakai, fitur lengkap. Proses rekrutmen transparan dan ada feedback yang membangun.'],
            [5, 'Tips interview dan info lowongannya sangat bermanfaat untuk persiapan karir saya sebagai fresh graduate.'],
            [5, 'Pelayanan admin responsif, setiap pertanyaan dijawab cepat dan profesional. Sangat terbantu!'],
            [4, 'Lowongan beragam dan sesuai skill. Sudah coba lamar 2 perusahaan, satu langsung panggilan interview.'],
            [5, 'Alhamdulillah diterima kerja lewat BKKMU. Pelacakan lamaran real-time-nya bikin tenang.'],
        ];

        // Cap: maksimal 1 review per user, dan maksimal sejumlah template.
        $count = min($users->count(), count($templates));

        for ($i = 0; $i < $count; $i++) {
            $user = $users[$i];
            [$rating, $comment] = [$templates[$i][0], $templates[$i][1]];
            $featured = ($templates[$i][2] ?? false) === true;

            $companyName = $companyList[$i % max(count($companyList), 1)] ?? null;
            $companyId = $companyName
                ? $companyNames->search($companyName) ?: \App\Models\Company::where('name', $companyName)->value('id')
                : null;

            Review::updateOrCreate(
                ['user_id' => $user->id, 'company_name' => $companyName],
                [
                    'company_id' => $companyId,
                    'rating' => $rating,
                    'comment' => $comment,
                    'job_title' => $user->preferred_position ?? 'Pencari Kerja',
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'status' => 'approved',
                    'featured' => $featured,
                ]
            );
        }
    }
}
