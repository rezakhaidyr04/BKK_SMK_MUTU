<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class BulkUmumSeeder extends Seeder
{
    /**
     * Menambah akun umum sampai total mencapai target.
     * Default 10.000. Bisa dioverride via env BULK_UMUM_TARGET.
     * Idempotent: jika sudah >= target, tidak menambah.
     * Cepat: bulk insert 1000/chunk + 1x hash password.
     */
    public function run(): void
    {
        $target = (int) (env('BULK_UMUM_TARGET', 10000) ?: 10000);
        $current = User::where('role', 'umum')->count();
        $need = $target - $current;

        if ($need <= 0) {
            $this->command?->info("Akun umum sudah {$current} (>= target {$target}). Tidak ada yang ditambah.");
            return;
        }

        $this->command?->info("Menambah {$need} akun umum (saat ini {$current}, target {$target})...");

        $role = Role::where('name', 'umum')->first();
        if (! $role) {
            $role = Role::create(['name' => 'umum', 'guard_name' => 'web']);
        }

        $passwordHash = Hash::make('password123');
        $now = now()->toDateTimeString();

        $first = ['Budi','Siti','Rizky','Dewi','Andi','Maya','Dimas','Nurul','Putri','Agus','Intan','Fajar','Ratna','Eko','Lia','Ahmad','Rina','Doni','Yoga','Tania','Bagus','Sinta','Hendra','Wulan','Galih','Nita','Bayu','Fitri','Rudi','Anisa','Dian','Rizal','Lina','Feri','Yuli','Dadan','Mega','Ilham','Sari','Tono'];
        $last = ['Santoso','Rahayu','Pratama','Anggraini','Kurniawan','Fitriani','Setiawan','Hidayah','Wulandari','Hermawan','Permata','Nugroho','Sari','Saputra','Hapsari','Wijaya','Kusuma','Ramadhan','Saputra','Maharani','Firmansyah','Lestari','Gunawan','Puspita','Hakim','Utami','Firmansyah','Handoko','Pramudya','Setiawan'];
        $positions = ['Operator Produksi / Teknisi Mesin','Staf Akuntansi / Admin Keuangan','Web Developer / IT Support','Resepsionis / Pramusaji','Teknisi Jaringan / IT Support','Pramusaji / Barista','Mekanik / Operator','Staff Administrasi / Kasir','Marketing / Customer Service','Teknisi Listrik / Operator','Kasir / Admin Keuangan','UI/UX Designer / Frontend','QC Garment / Admin','Operator Packaging / Gudang','Staff Marketing / Sales'];
        $cities = ['Cikampek','Karawang','Purwakarta','Subang','Bekasi','Cikarang','Klaten','Bandung','Cirebon','Indramayu'];

        // Nomor urut email anti-tabrakan: mulai setelah jumlah yang sudah ada pola ini.
        $existingSeq = DB::table('users')->where('email', 'like', 'pengguna%@bkk.local')->count();
        $seq = $existingSeq + 1;

        $chunkSize = 1000;
        $made = 0;

        mt_srand(12345 + $current);

        while ($made < $need) {
            $n = min($chunkSize, $need - $made);
            $rows = [];
            $emails = [];
            for ($k = 0; $k < $n; $k++, $seq++) {
                $name = $first[mt_rand(0, count($first) - 1)] . ' ' . $last[mt_rand(0, count($last) - 1)];
                $email = sprintf('pengguna%05d@bkk.local', $seq);
                $emails[] = $email;
                $rows[] = [
                    'name' => $name,
                    'email' => $email,
                    'email_verified_at' => $now,
                    'password' => $passwordHash,
                    'phone' => '08' . str_pad((string) mt_rand(1000000000, 1999999999), 10, '0', STR_PAD_LEFT),
                    'role' => 'umum',
                    'bio' => 'Lulusan SMK, siap bekerja dan berkembang bersama perusahaan.',
                    'preferred_position' => $positions[mt_rand(0, count($positions) - 1)],
                    'address' => $cities[mt_rand(0, count($cities) - 1)] . ', Jawa Barat',
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::transaction(function () use ($rows, $emails, $role) {
                DB::table('users')->insert($rows);
                $ids = DB::table('users')->whereIn('email', $emails)->pluck('id');
                $pivot = [];
                foreach ($ids as $id) {
                    $pivot[] = [
                        'role_id' => $role->id,
                        'model_type' => 'App\\Models\\User',
                        'model_id' => $id,
                    ];
                }
                if ($pivot !== []) {
                    DB::table('model_has_roles')->insertOrIgnore($pivot);
                }
            });

            $made += $n;
            $this->command?->info("  ... {$made}/{$need}");
        }

        $final = User::where('role', 'umum')->count();
        $this->command?->info("Selesai. Total akun umum sekarang: {$final} (target {$target}). Login: penggunaXXXXX@bkk.local / password123");
    }
}
