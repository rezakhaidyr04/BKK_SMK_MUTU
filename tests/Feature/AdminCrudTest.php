<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Job;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_sections()
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole($role);
        
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.companies.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Perusahaan');

        $response = $this->actingAs($admin)
            ->get(route('admin.jobs.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Lowongan');
    }

    public function test_admin_can_update_company_verification()
    {
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole($role);
        
        $company = Company::factory()->create(['verification_status' => 'pending']);

        $response = $this->actingAs($admin)
            ->post(route('admin.companies.approve', $company));

        $response->assertRedirect(route('admin.companies.index'));
        $this->assertEquals('verified', $company->fresh()->verification_status);
    }
}
