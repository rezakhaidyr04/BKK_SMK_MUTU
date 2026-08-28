<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportingExportTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole($role);

        return $admin;
    }

    public function test_csv_export_returns_csv_download(): void
    {
        Company::factory()->create();
        Job::factory()->count(2)->create();
        Application::factory()->count(3)->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.reports.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=utf-8');
        $response->assertSee('Total Pencari Kerja');
    }

    public function test_excel_export_returns_real_spreadsheetml(): void
    {
        Job::factory()->count(2)->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.reports.export-excel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.ms-excel; charset=utf-8');
        $response->assertSee('xml', false);
        $response->assertSee('Workbook', false);
    }

    public function test_pdf_export_returns_pdf_download(): void
    {
        Job::factory()->count(2)->create();

        $response = $this->actingAs($this->admin())
            ->get(route('admin.reports.export-pdf'));

        $response->assertStatus(200);
        $contentType = $response->headers->get('content-type');
        $disposition = $response->headers->get('content-disposition');
        $this->assertStringContainsString('pdf', strtolower((string) $contentType));
        $this->assertStringContainsString('attachment', strtolower((string) $disposition));
    }
}
