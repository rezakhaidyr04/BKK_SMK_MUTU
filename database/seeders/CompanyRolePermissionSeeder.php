<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CompanyRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $companyRole = Role::firstOrCreate(['name' => 'company', 'guard_name' => 'web']);

        $permissions = [
            'company.manage-profile',
            'company.manage-jobs',
            'company.view-applicants',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $companyRole->syncPermissions($permissions);

        User::where('role', 'company')->each(function (User $user) use ($companyRole) {
            $user->syncRoles([$companyRole]);
        });
    }
}