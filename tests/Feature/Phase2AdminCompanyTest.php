<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * PHASE 2 — Admin Company Management Tests
 */
class Phase2AdminCompanyTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed required Spatie roles
        Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'company',  'guard_name' => 'web']);

        $this->admin = User::factory()->create([
            'role'     => 'admin',
            'is_active' => true,
        ]);
        $this->admin->assignRole('admin');
    }

    // ─────────────────────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_companies_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.index'));
        $response->assertOk();
    }

    /** @test */
    public function non_admin_cannot_access_admin_companies(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $response = $this->actingAs($student)->get(route('admin.companies.index'));
        $response->assertForbidden();
    }

    // ─────────────────────────────────────────────────────────────
    // CREATE / STORE
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_create_company_form(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.companies.create'));
        $response->assertOk();
        $response->assertSee('Tambah Perusahaan');
    }

    /** @test */
    public function admin_can_create_company_without_user_account(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.store'), [
            'name'     => 'PT Maju Bersama',
            'industry' => 'Manufaktur',
            'email'    => 'hrd@majubersama.com',
            'address'  => 'Jl. Industri No. 1',
        ]);

        $response->assertRedirect(route('admin.companies.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('companies', [
            'name'             => 'PT Maju Bersama',
            'user_id'          => null,
            'verification_status' => 'pending',
        ]);
    }

    /** @test */
    public function store_requires_company_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.companies.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function admin_can_upload_mou_when_creating_company(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('mou.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.companies.store'), [
            'name'           => 'PT Test MoU',
            'mou_path'       => $file,
            'mou_number'     => 'MOU/BKK/2024/001',
            'mou_signed_at'  => '2024-01-15',
            'mou_expires_at' => '2025-01-15',
        ]);

        $response->assertRedirect(route('admin.companies.index'));

        $company = Company::where('name', 'PT Test MoU')->first();
        $this->assertNotNull($company);
        $this->assertNotNull($company->mou_path);
        $this->assertEquals('MOU/BKK/2024/001', $company->mou_number);

        // File harus tersimpan di disk local (private)
        Storage::disk('local')->assertExists($company->mou_path);
    }

    // ─────────────────────────────────────────────────────────────
    // SHOW
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_view_company_detail(): void
    {
        $company = Company::factory()->create([
            'name'                => 'PT Detail Test',
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.companies.show', $company));
        $response->assertOk();
        $response->assertSee('PT Detail Test');
    }

    // ─────────────────────────────────────────────────────────────
    // MOU DOWNLOAD (private)
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_download_mou_via_private_route(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('mou_secret.pdf', 100, 'application/pdf');
        $path = $file->store('company_mou', 'local');

        $company = Company::factory()->create(['mou_path' => $path]);

        $response = $this->actingAs($this->admin)->get(route('admin.companies.mou.download', $company));
        $response->assertOk();
        $response->assertHeader('content-disposition');
    }

    /** @test */
    public function mou_download_returns_404_when_no_mou(): void
    {
        $company = Company::factory()->create(['mou_path' => null]);

        $response = $this->actingAs($this->admin)->get(route('admin.companies.mou.download', $company));
        $response->assertNotFound();
    }

    /** @test */
    public function non_admin_cannot_download_mou(): void
    {
        Storage::fake('local');
        $path = UploadedFile::fake()->create('mou.pdf', 100)->store('company_mou', 'local');
        $company = Company::factory()->create(['mou_path' => $path]);

        $student = User::factory()->create(['role' => 'student']);
        $response = $this->actingAs($student)->get(route('admin.companies.mou.download', $company));
        $response->assertForbidden();
    }

    // ─────────────────────────────────────────────────────────────
    // APPROVE
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_approve_company_and_sets_reviewer(): void
    {
        $company = Company::factory()->create(['verification_status' => 'pending']);

        $response = $this->actingAs($this->admin)->post(route('admin.companies.approve', $company));

        $response->assertRedirect(route('admin.companies.index'));
        $response->assertSessionHas('success');

        $company->refresh();
        $this->assertEquals('verified', $company->verification_status);
        $this->assertTrue($company->is_verified);
        $this->assertNull($company->rejection_reason);
        $this->assertEquals($this->admin->id, $company->reviewed_by);
        $this->assertNotNull($company->reviewed_at);
    }

    // ─────────────────────────────────────────────────────────────
    // REJECT — wajib ada alasan
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_reject_company_with_reason(): void
    {
        $company = Company::factory()->create(['verification_status' => 'pending']);

        $response = $this->actingAs($this->admin)->post(route('admin.companies.reject', $company), [
            'rejection_reason' => 'Dokumen belum lengkap.',
        ]);

        $response->assertRedirect(route('admin.companies.index'));

        $company->refresh();
        $this->assertEquals('rejected', $company->verification_status);
        $this->assertFalse($company->is_verified);
        $this->assertEquals('Dokumen belum lengkap.', $company->rejection_reason);
        $this->assertEquals($this->admin->id, $company->reviewed_by);
        $this->assertNotNull($company->reviewed_at);
    }

    /** @test */
    public function reject_without_reason_fails_validation(): void
    {
        $company = Company::factory()->create(['verification_status' => 'pending']);

        $response = $this->actingAs($this->admin)->post(route('admin.companies.reject', $company), [
            'rejection_reason' => '',
        ]);

        $response->assertSessionHasErrors('rejection_reason');

        $company->refresh();
        $this->assertEquals('pending', $company->verification_status);
    }

    // ─────────────────────────────────────────────────────────────
    // EDIT / UPDATE
    // ─────────────────────────────────────────────────────────────

    /** @test */
    public function admin_can_update_company_info(): void
    {
        $company = Company::factory()->create(['name' => 'Lama']);

        $response = $this->actingAs($this->admin)->put(route('admin.companies.update', $company), [
            'name'    => 'PT Baru Sekali',
            'industry' => 'Teknologi',
        ]);

        $response->assertRedirect(route('admin.companies.index'));

        $company->refresh();
        $this->assertEquals('PT Baru Sekali', $company->name);
        $this->assertEquals('Teknologi', $company->industry);
    }
}
