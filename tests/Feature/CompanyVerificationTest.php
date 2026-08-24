<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_registration_is_marked_pending_until_admin_verifies_it(): void
    {
        $this->markTestSkipped('Company registration via public form is currently disabled.');
        $response = $this->post(route('register'), [
            'name' => 'PT Uji Verifikasi',
            'email' => 'company.pending@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'role' => 'company',
            'company_name' => 'PT Uji Verifikasi',
            'company_industry' => 'IT',
            'company_address' => 'Cikampek',
            'company_website' => 'https://example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user = User::where('email', 'company.pending@example.com')->firstOrFail();
        $company = $user->company;

        $this->assertNotNull($company);
        $this->assertFalse($company->is_verified);
        $this->assertSame('pending', $company->verification_status);
    }

    public function test_unverified_company_cannot_create_jobs(): void
    {
        $companyUser = User::factory()->create([
            'role' => 'company',
        ]);
        Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($companyUser)
            ->get(route('company.jobs.create'));

        $response->assertRedirect(route('company.profile.edit'));
        $response->assertSessionHas('error', 'Akun perusahaan Anda belum diverifikasi oleh admin. Silakan lengkapi profil dan ajukan verifikasi.');
    }

    public function test_company_user_is_redirected_from_general_profile_route_to_company_profile(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($companyUser)
            ->get(route('profile.edit'));

        $response->assertRedirect(route('company.profile.edit'));
    }

    public function test_unverified_company_cannot_store_job(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($companyUser)
            ->post(route('company.jobs.store'), [
                'company_name' => 'PT Contoh',
                'title' => 'Backend Developer',
                'position' => 'Developer',
                'location' => 'Jakarta',
                'job_type' => 'full_time',
                'salary_min' => 5000000,
                'salary_max' => 9000000,
                'description' => 'Deskripsi pekerjaan.',
                'qualifications' => 'Minimal pengalaman 2 tahun.',
                'benefits' => 'Asuransi kesehatan',
                'deadline' => now()->addWeeks(2)->format('Y-m-d'),
                'status' => 'active',
            ]);

        $response->assertRedirect(route('company.jobs.index'));
        $response->assertSessionHas('error', 'Akun perusahaan Anda belum diverifikasi oleh admin. Tidak dapat mempublikasikan lowongan saat ini.');
    }

    public function test_verified_company_can_view_applicants(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $applicant = User::factory()->create();
        $job = Job::factory()->create([
            'company_id' => $company->id,
            'company_name' => $company->name,
            'title' => 'Backend Developer',
        ]);

        Application::factory()->create([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'status' => 'pending',
            'cover_letter' => 'Saya tertarik dengan posisi ini.',
            'attachment_name' => 'resume.pdf',
        ]);

        $response = $this->actingAs($companyUser)
            ->get(route('company.applicants.index'));

        $response->assertOk();
        $response->assertSee('Pelamar');
        $response->assertSee($applicant->name);
        $response->assertSee($job->title);
    }

    public function test_verified_company_can_create_job_and_company_id_is_saved(): void
    {
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        $response = $this->actingAs($companyUser)
            ->post(route('company.jobs.store'), [
                'company_name' => $company->name,
                'title' => 'Senior Backend Engineer',
                'position' => 'Backend Engineer',
                'location' => 'Jakarta',
                'job_type' => 'full_time',
                'salary_min' => 7000000,
                'salary_max' => 12000000,
                'description' => 'Deskripsi pekerjaan backend engineer',
                'qualifications' => 'Minimal 3 tahun pengalaman',
                'benefits' => 'Asuransi kesehatan, remote',
                'deadline' => now()->addWeeks(2)->format('Y-m-d'),
                'status' => 'active',
            ]);

        $response->assertRedirect(route('company.jobs.index'));

        $this->assertDatabaseHas('jobs', [
            'title' => 'Senior Backend Engineer',
            'company_id' => $company->id,
            'company_name' => $company->name,
        ]);
    }

    public function test_company_verification_request_stores_tax_number_and_documents(): void
    {
        Storage::fake('public');

        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $businessLicense = UploadedFile::fake()->create('business_license.pdf', 100, 'application/pdf');
        $operatingLicense = UploadedFile::fake()->image('operating_license.png');

        $response = $this->actingAs($companyUser)
            ->post(route('company.profile.verify'), [
                'tax_number' => '1234567890',
                'business_license' => $businessLicense,
                'operating_license' => $operatingLicense,
            ]);

        $response->assertRedirect(route('company.profile.edit'));

        $freshCompany = $company->fresh();

        $this->assertSame('1234567890', $freshCompany->tax_number);
        $this->assertStringStartsWith('company_verifications/' . $company->id, $freshCompany->business_license_path);
        $this->assertStringStartsWith('company_verifications/' . $company->id, $freshCompany->operating_license_path);
        Storage::disk('private')->assertExists($freshCompany->business_license_path);
        Storage::disk('private')->assertExists($freshCompany->operating_license_path);
    }

    public function test_admin_can_approve_company_and_enable_job_creation(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => false,
            'verification_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.companies.approve', $company));

        $response->assertRedirect(route('admin.companies.index'));
        $this->assertTrue($company->fresh()->is_verified);
        $this->assertSame('verified', $company->fresh()->verification_status);
    }
}
