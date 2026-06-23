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
        $admin = User::factory()->create(['role' => 'admin']);
        $company = Company::factory()->create();
        $job = Job::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Pengguna');

        $response = $this->actingAs($admin)
            ->get(route('admin.companies.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Perusahaan');

        $response = $this->actingAs($admin)
            ->get(route('admin.jobs.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Lowongan');

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Admin');
    }

    public function test_admin_can_update_company_verification()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $company = Company::factory()->create(['is_verified' => false]);

        $response = $this->actingAs($admin)
            ->put(route('admin.companies.update', $company), [
                'name' => $company->name,
                'industry' => $company->industry,
                'description' => $company->description,
                'website' => $company->website,
                'address' => $company->address,
                'is_verified' => '1',
            ]);

        $response->assertRedirect(route('admin.companies.index'));
        $this->assertTrue($company->fresh()->is_verified);
    }

    public function test_admin_can_export_reports_csv()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->get(route('admin.reports.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
        $response->assertSee('Total Students');
    }
}
