<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyApplicantSeeder extends Seeder
{
    private const EMAIL = 'ahmad.nur.ajril@gmail.com';

    private const LEGACY_EMAIL = 'ahmad.ajril@umum.bkk.com';

    private const PASSWORD = 'password123';

    public function run(): void
    {
        $user = User::withTrashed()
            ->whereIn('email', [self::EMAIL, self::LEGACY_EMAIL])
            ->first();

        if (! $user) {
            $user = new User();
            $user->email = self::EMAIL;
        }

        if ($user->trashed()) {
            $user->restore();
        }

        $user->forceFill([
            'email' => self::EMAIL,
            'name' => 'Ahmad Nur Ajril',
            'password' => Hash::make(self::PASSWORD),
            'role' => 'umum',
            'phone' => '081234560009',
            'bio' => 'Profesional dengan pengalaman memimpin operasional program MBG, mengelola koperasi desa, dan berorganisasi sebagai ketua partai di tingkat daerah.',
            'preferred_position' => 'Manajer Operasional / Manajer Koperasi / Koordinator Program',
            'education_history' => 'SMA/SMK sederajat',
            'experience_organization' => "Manajer Program Makan Bergizi Gratis (MBG) — Mengelola operasional harian, koordinasi tim, pengadaan, distribusi, serta memastikan program berjalan sesuai standar.\nManajer Koperasi Desa — Mengelola kegiatan usaha koperasi, administrasi keuangan, pelayanan anggota, dan pengembangan unit usaha desa.\nKetua Partai PDA tingkat daerah — Memimpin organisasi, menyusun program kerja, membangun koordinasi dengan pengurus dan masyarakat, serta mengelola kegiatan sosial kemasyarakatan.",
            'address' => 'Kabupaten Karawang, Jawa Barat',
            'birth_place' => 'Karawang',
            'birth_date' => '1985-04-12',
            'gender' => 'Laki-laki',
            'is_active' => true,
            'email_verified_at' => now(),
        ])->save();

        $user->syncRoles(['umum']);

        $this->command->info('Akun pelamar Ahmad Nur Ajril berhasil dibuat/diperbarui.');
        $this->command->info('Email: '.self::EMAIL);
        $this->command->info('Password: '.self::PASSWORD);
    }
}
