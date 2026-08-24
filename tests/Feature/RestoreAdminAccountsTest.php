<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RestoreAdminAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_restores_soft_deleted_company_account_and_company_profile(): void
    {
        Role::firstOrCreate(['name' => 'company', 'guard_name' => 'web']);

        // Use an email that is in the RestoreAdminAccounts command's defaultAccounts list
        // so the command knows to restore it.
        $user = User::create([
            'name'     => 'PT Contoh BKK',
            'email'    => 'pt.contoh@bkk.com',
            'password' => bcrypt('password123'),
            'role'     => 'company',
            'is_active' => true,
        ]);
        $user->assignRole('company');
        $user->delete();

        $this->artisan('db:restore-admin', ['--force' => true])->assertSuccessful();

        $restoredUser = User::withTrashed()->where('email', 'pt.contoh@bkk.com')->first();

        $this->assertNotNull($restoredUser);
        $this->assertNull($restoredUser->deleted_at);
        $this->assertTrue($restoredUser->is_active);
        $this->assertTrue($restoredUser->hasRole('company'));
        $this->assertDatabaseHas('companies', [
            'user_id' => $restoredUser->id,
            'name'    => 'PT Contoh BKK',
        ]);
    }
}
