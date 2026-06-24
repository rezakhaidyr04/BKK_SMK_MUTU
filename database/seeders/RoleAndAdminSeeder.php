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
            'student',
            'alumni',
            'company',
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

        $company = User::firstOrCreate(
            ['email' => 'company@bkk.com'],
            [
                'name' => 'PT Contoh BKK',
                'password' => Hash::make('password123'),
                'role' => 'company',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $company->assignRole('company');
        $company->company()->firstOrCreate(
            ['user_id' => $company->id],
            [
                'name' => 'PT Contoh BKK',
                'is_verified' => true,
                'verification_status' => 'verified',
            ]
        );
    }
}
