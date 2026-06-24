<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RestoreAdminAccounts extends Command
{
    protected $signature = 'db:restore-admin
                            {--force : Paksa reset password ke default}';

    protected $description = 'Memulihkan akun admin, guru, company, dan siswa default jika terhapus';

    private array $defaultAccounts = [
        [
            'email'    => 'admin@bkk.com',
            'name'     => 'Super Admin BKK',
            'password' => 'password123',
            'role'     => 'admin',
        ],
        [
            'email'    => 'guru@bkk.com',
            'name'     => 'Guru BKK',
            'password' => 'password123',
            'role'     => 'teacher',
        ],
        [
            'email'    => 'company@bkk.com',
            'name'     => 'PT Contoh BKK',
            'password' => 'password123',
            'role'     => 'company',
        ],
        [
            'email'    => 'siswa@bkk.com',
            'name'     => 'Siswa Demo BKK',
            'password' => 'password123',
            'role'     => 'student',
        ],
    ];

    public function handle(): void
    {
        $this->info('🔄 Memulihkan akun default BKK...');
        $this->newLine();

        $this->ensureRolesExist();

        foreach ($this->defaultAccounts as $account) {
            $this->restoreAccount($account);
        }

        $this->newLine();
        $this->info('✅ Semua akun default berhasil dipulihkan!');
        $this->newLine();
        $this->table(
            ['Email', 'Nama', 'Role', 'Password'],
            collect($this->defaultAccounts)->map(fn($a) => [
                $a['email'], $a['name'], $a['role'], $a['password'],
            ])->toArray()
        );
    }

    private function ensureRolesExist(): void
    {
        foreach (['admin', 'teacher', 'student', 'alumni', 'company'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        $this->line('  ✔ Role diverifikasi');
    }

    private function restoreAccount(array $account): void
    {
        $force = $this->option('force');

        $user = User::firstOrCreate(
            ['email' => $account['email']],
            [
                'name'              => $account['name'],
                'password'          => Hash::make($account['password']),
                'role'              => $account['role'],
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // Jika --force, reset password dan pastikan aktif
        if ($force && !$user->wasRecentlyCreated) {
            $user->update([
                'password'  => Hash::make($account['password']),
                'is_active' => true,
            ]);
        }

        // Selalu pastikan aktif & terverifikasi
        if (!$user->wasRecentlyCreated) {
            $user->update([
                'is_active'         => true,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        }

        // Assign role Spatie
        if (!$user->hasRole($account['role'])) {
            $user->assignRole($account['role']);
        }

        // Buat company profile jika role company
        if ($account['role'] === 'company') {
            Company::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'                => $account['name'],
                    'is_verified'         => true,
                    'verification_status' => 'verified',
                ]
            );
        }

        $status = $user->wasRecentlyCreated ? '✔ Dibuat baru' : '✔ Diverifikasi';
        $this->line("  {$status}: {$account['email']} [{$account['role']}]");
    }
}
