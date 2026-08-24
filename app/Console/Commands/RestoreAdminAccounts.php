<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RestoreAdminAccounts extends Command
{
    protected $signature = 'db:restore-admin
                            {--force : Paksa reset password ke default}';

    protected $description = 'Memulihkan akun admin, guru, company, dan pencari kerja default jika terhapus';

    private array $defaultAccounts = [
        [
            'email' => 'admin@bkk.com',
            'name' => 'Super Admin BKK',
            'password' => 'password123',
            'role' => 'admin',
        ],
        [
            'email' => 'guru@bkk.com',
            'name' => 'Guru BKK',
            'password' => 'password123',
            'role' => 'teacher',
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
            'email' => 'siswa@bkk.com',
            'name' => 'Siswa Demo BKK',
            'password' => 'password123',
            'role' => 'jobseeker',
        ],
        [
            'email' => 'budi.santoso@siswa.bkk.com',
            'name' => 'Budi Santoso',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345601',
                'major' => 'Teknik Mesin',
                'graduation_year' => 2024,
                'address' => 'Jl. Melati No. 5, Cikampek Barat',
            ],
        ],
        [
            'email' => 'siti.rahayu@siswa.bkk.com',
            'name' => 'Siti Rahayu',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345602',
                'major' => 'Akuntansi',
                'graduation_year' => 2024,
                'address' => 'Jl. Anggrek No. 12, Cikampek',
            ],
        ],
        [
            'email' => 'rizky.pratama@siswa.bkk.com',
            'name' => 'Rizky Pratama',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345603',
                'major' => 'Rekayasa Perangkat Lunak',
                'graduation_year' => 2024,
                'address' => 'Perumahan Cikampek Baru Blok C No. 8',
            ],
        ],
        [
            'email' => 'dewi.anggraini@siswa.bkk.com',
            'name' => 'Dewi Anggraini',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345604',
                'major' => 'Perhotelan',
                'graduation_year' => 2024,
                'address' => 'Jl. Pramuka No. 33, Cikampek Timur',
            ],
        ],
        [
            'email' => 'andi.kurniawan@siswa.bkk.com',
            'name' => 'Andi Kurniawan',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345605',
                'major' => 'Teknik Komputer dan Jaringan',
                'graduation_year' => 2024,
                'address' => 'Jl. Veteran No. 17, Cikampek',
            ],
        ],
        [
            'email' => 'maya.fitriani@siswa.bkk.com',
            'name' => 'Maya Fitriani',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345606',
                'major' => 'Tata Boga',
                'graduation_year' => 2025,
                'address' => 'Jl. Pahlawan No. 21, Cikampek',
            ],
        ],
        [
            'email' => 'dimas.setiawan@siswa.bkk.com',
            'name' => 'Dimas Setiawan',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345607',
                'major' => 'Teknik Otomotif',
                'graduation_year' => 2024,
                'address' => 'Desa Wadas, Cikampek Barat',
            ],
        ],
        [
            'email' => 'nurul.hidayah@siswa.bkk.com',
            'name' => 'Nurul Hidayah',
            'password' => 'password123',
            'role' => 'jobseeker',
            'student' => [
                'nisn' => '0012345608',
                'major' => 'Administrasi Perkantoran',
                'graduation_year' => 2025,
                'address' => 'Jl. Cikarang No. 4, Cikampek',
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
        foreach (['admin', 'teacher', 'jobseeker', 'company'] as $role) {
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

        if ($account['role'] === 'jobseeker') {
            $student = Student::withTrashed()->where('user_id', $user->id)->first();
            $studentData = $account['student'] ?? [];

            if (! $student) {
                Student::create(array_merge([
                    'user_id' => $user->id,
                ], $studentData));
            } else {
                if ($student->trashed()) {
                    $student->restore();
                }

                $student->forceFill(array_merge([
                    'user_id' => $user->id,
                ], $studentData))->save();
            }

            $existingStudent = Student::where('nisn', $studentData['nisn'] ?? null)
                ->where('id', '!=', $student?->id)
                ->first();

            if ($existingStudent) {
                $existingStudent->forceFill([
                    'nisn' => null,
                ])->save();
            }
        }

        $status = $created ? '✔ Dibuat baru' : '✔ Diverifikasi';
        $this->line("  {$status}: {$account['email']} [{$account['role']}] ");
    }
}
