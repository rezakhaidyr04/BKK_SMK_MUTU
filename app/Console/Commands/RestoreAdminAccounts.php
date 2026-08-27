<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RestoreAdminAccounts extends Command
{
    protected $signature = 'db:restore-admin
                            {--force : Paksa reset password ke default}';

    protected $description = 'Memulihkan akun admin, company, dan pengguna umum default jika terhapus';

    private array $defaultAccounts = [
        [
            'email' => 'admin@bkk.com',
            'name' => 'Super Admin BKK',
            'password' => 'password123',
            'role' => 'admin',
        ],
        [
            'email' => 'pt.contoh@bkk.com',
            'name' => 'PT Contoh BKK',
            'password' => 'password123',
            'role' => 'company',
        ],
        [
            'email' => 'pt.maju@bkk.com',
            'name' => 'PT Maju Bersama',
            'password' => 'password123',
            'role' => 'company',
        ],
        [
            'email' => 'pt.tekno@bkk.com',
            'name' => 'PT Teknologi Nusantara',
            'password' => 'password123',
            'role' => 'company',
        ],
        [
            'email' => 'pt.retail@bkk.com',
            'name' => 'PT Ritel Cikampek',
            'password' => 'password123',
            'role' => 'company',
        ],
        [
            'email' => 'pt.logistik@bkk.com',
            'name' => 'PT Cepat Kirim Logistik',
            'password' => 'password123',
            'role' => 'company',
        ],
        [
            'email' => 'pt.hotel@bkk.com',
            'name' => 'PT Cikampek Hospitality',
            'password' => 'password123',
            'role' => 'company',
        ],
        [
            'email' => 'umum@bkk.com',
            'name' => 'Pengguna Demo BKK',
            'password' => 'password123',
            'role' => 'umum',
        ],
        [
            'email' => 'budi.santoso@umum.bkk.com',
            'name' => 'Budi Santoso',
            'password' => 'password123',
            'role' => 'umum',
            'profile' => [
                'preferred_position' => 'Teknisi Mesin',
                'address' => 'Jl. Melati No. 5, Cikampek Barat',
                'education_history' => "SMK MUTU Cikampek (Teknik Mesin)",
            ],
        ],
        [
            'email' => 'siti.rahayu@umum.bkk.com',
            'name' => 'Siti Rahayu',
            'password' => 'password123',
            'role' => 'umum',
            'profile' => [
                'preferred_position' => 'Staf Akuntansi',
                'address' => 'Jl. Anggrek No. 12, Cikampek',
                'education_history' => "SMK MUTU Cikampek (Akuntansi)",
            ],
        ],
        [
            'email' => 'rizky.pratama@umum.bkk.com',
            'name' => 'Rizky Pratama',
            'password' => 'password123',
            'role' => 'umum',
            'profile' => [
                'preferred_position' => 'Programmer / Web Developer',
                'address' => 'Perumahan Cikampek Baru Blok C No. 8',
                'education_history' => "SMK MUTU Cikampek (Rekayasa Perangkat Lunak)",
            ],
        ],
        [
            'email' => 'dewi.anggraini@umum.bkk.com',
            'name' => 'Dewi Anggraini',
            'password' => 'password123',
            'role' => 'umum',
            'profile' => [
                'preferred_position' => 'Staf Housekeeping / Front Office',
                'address' => 'Jl. Pramuka No. 33, Cikampek Timur',
                'education_history' => "SMK MUTU Cikampek (Perhotelan)",
            ],
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
            collect($this->defaultAccounts)->map(fn ($a) => [
                $a['email'], $a['name'], $a['role'], $a['password'],
            ])->toArray()
        );
    }

    private function ensureRolesExist(): void
    {
        foreach (['admin', 'umum', 'company'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->line('  ✔ Role diverifikasi');
    }

    private function restoreAccount(array $account): void
    {
        $force = $this->option('force');

        $user = User::withTrashed()->where('email', $account['email'])->first();
        $created = false;

        if (! $user) {
            $user = new User();
            $user->email = $account['email'];
            $created = true;
        }

        if ($user->trashed()) {
            $user->restore();
        }

        $user->forceFill([
            'name' => $account['name'],
            'password' => Hash::make($account['password']),
            'role' => $account['role'],
            'is_active' => true,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);
        $user->save();

        if ($force || ! $user->hasRole($account['role'])) {
            $user->syncRoles([$account['role']]);
        }

        if ($account['role'] === 'company') {
            $company = Company::withTrashed()->where('user_id', $user->id)->first();

            if (! $company) {
                Company::create([
                    'user_id' => $user->id,
                    'name' => $account['name'],
                    'is_verified' => false,
                    'verification_status' => 'pending',
                ]);
            } else {
                if ($company->trashed()) {
                    $company->restore();
                }

                $company->forceFill([
                    'name' => $account['name'],
                    'is_verified' => false,
                    'verification_status' => 'pending',
                ])->save();
            }
        }

        if ($account['role'] === 'umum' && !empty($account['profile'])) {
            foreach ($account['profile'] as $field => $value) {
                if ($user->$field !== $value) {
                    $user->forceFill([$field => $value]);
                }
            }
            $user->save();
        }

        $status = $created ? '✔ Dibuat baru' : '✔ Diverifikasi';
        $this->line("  {$status}: {$account['email']} [{$account['role']}] ");
    }
}
