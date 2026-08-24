<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'teacher',
            'jobseeker',
            'company',
            // legacy Spatie roles sengaja tidak dibuat ulang di seeder aktif
            // karena account role pencari kerja sekarang sudah dikonsolidasikan.
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@bkk.com'],
            [
                'name' => 'Super Admin BKK',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $admin->assignRole('admin');

        $teacher = User::firstOrCreate(
            ['email' => 'guru@bkk.com'],
            [
                'name' => 'Guru BKK',
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $teacher->assignRole('teacher');
    }
}
