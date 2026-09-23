<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\CvFile;
use App\Models\Job;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P5.4: File upload & storage edge cases (TEST-ONLY).
 *
 * Follows existing production behavior as source of truth.
 * Does not duplicate P5.1 (ApplicationAuthorizationTest,
 * SecureFileAndMessageTest), P5.2 (AuthorizationEdgeCaseTest),
 * P5.3 (BusinessFlowTest), JobApplicationAttachmentTest (valid apply
 * with attachment), Phase2AdminCompanyTest (valid MOU store/download),
 * or CompanyVerificationTest (valid verify docs).
 *
 * Covers only real gaps: invalid MIME, oversized, valid-store private
 * placement, missing-file download/delete, replace cleanup, filename
 * path safety, MOU/legal isolation, role restriction on verify.
 */
class FileStorageEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'umum',
            'email_verified_at' => now(),
        ], $over));
    }

    private function verifiedCompany(array $over = []): User
    {
        $u = User::factory()->create(array_merge([
            'role' => 'company',
            'email_verified_at' => now(),
        ], $over));
        Company::factory()->create([
            'user_id' => $u->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);

        return $u;
    }

    private function admin(): User
    {
        $a = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $a->assignRole(\Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']));

        return $a;
    }

    private function certPayload(string $title = 'Sertifikat PHP'): array
    {
        return [
            'title' => $title,
            'issuer' => 'Dicoding',
            'issue_date' => now()->format('Y-m-d'),
        ];
    }

    private function coverLetter(): string
    {
        return str_repeat('Saya sangat tertarik dengan posisi ini dan memenuhi kualifikasi. ', 3);
    }

    // ---------- Certificate: validation ----------

    public function test_certificate_store_rejects_invalid_mime(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('certificates.store'), array_merge(
            $this->certPayload(),
            ['file' => UploadedFile::fake()->create('notes.txt', 100, 'text/plain')]
        ))->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('certificates', 0);
    }

    public function test_certificate_store_rejects_oversized_file(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('certificates.store'), array_merge(
            $this->certPayload(),
            ['file' => UploadedFile::fake()->create('big.pdf', 6000, 'application/pdf')]
        ))->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('certificates', 0);
    }

    public function test_certificate_store_requires_file(): void
    {
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('certificates.store'), $this->certPayload())
            ->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('certificates', 0);
    }

    public function test_certificate_store_valid_pdf_saved_to_private_disk(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $user = $this->verifiedUmum();

        $resp = $this->actingAs($user)->post(route('certificates.store'), array_merge(
            $this->certPayload(),
            ['file' => UploadedFile::fake()->create('cert.pdf', 200, 'application/pdf')]
        ));

        $resp->assertRedirect();
        $resp->assertSessionHas('success');

        $cert = Certificate::where('user_id', $user->id)->firstOrFail();
        $this->assertStringStartsWith('certificates/', $cert->file_path);
        $this->assertStringNotContainsString('..', $cert->file_path);
        Storage::disk('private')->assertExists($cert->file_path);
        Storage::disk('public')->assertMissing($cert->file_path);
    }

    public function test_certificate_destroy_succeeds_when_file_already_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $cert = Certificate::factory()->create([
            'user_id' => $owner->id,
            'file_path' => 'certificates/already-gone.pdf',
        ]);

        $this->actingAs($owner)->delete(route('certificates.destroy', $cert))
            ->assertRedirect();

        $this->assertDatabaseMissing('certificates', ['id' => $cert->id]);
    }

    // ---------- UserDocument: validation + storage ----------

    public function test_user_document_store_rejects_invalid_mime(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('documents.store'), [
            'document_type' => 'ktp',
            'file' => UploadedFile::fake()->create('notes.txt', 100, 'text/plain'),
        ])->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('user_documents', 0);
    }

    public function test_user_document_store_rejects_oversized_file(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();

        $this->actingAs($user)->post(route('documents.store'), [
            'document_type' => 'ktp',
            'file' => UploadedFile::fake()->create('big.pdf', 6000, 'application/pdf'),
        ])->assertSessionHasErrors(['file']);

        $this->assertDatabaseCount('user_documents', 0);
    }

    public function test_user_document_store_valid_file_saved_to_private_disk(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $user = $this->verifiedUmum();

        $resp = $this->actingAs($user)->post(route('documents.store'), [
            'document_type' => 'ktp',
            'file' => UploadedFile::fake()->create('ktp.pdf', 200, 'application/pdf'),
        ]);

        $resp->assertRedirect();
        $resp->assertSessionHas('success');

        $doc = UserDocument::where('user_id', $user->id)->firstOrFail();
        $this->assertSame('ktp.pdf', $doc->original_name);
        $this->assertStringStartsWith('user-documents/', $doc->file_path);
        Storage::disk('private')->assertExists($doc->file_path);
        Storage::disk('public')->assertMissing($doc->file_path);
    }

    public function test_user_document_download_returns_404_when_file_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $doc = UserDocument::create([
            'user_id' => $owner->id,
            'document_type' => 'ktp',
            'file_path' => 'user-documents/missing.pdf',
            'original_name' => 'missing.pdf',
        ]);

        $this->actingAs($owner)->get(route('documents.download', $doc))
            ->assertNotFound();
    }

    public function test_user_document_destroy_succeeds_when_file_already_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $doc = UserDocument::create([
            'user_id' => $owner->id,
            'document_type' => 'ktp',
            'file_path' => 'user-documents/already-gone.pdf',
            'original_name' => 'gone.pdf',
        ]);

        $this->actingAs($owner)->delete(route('documents.destroy', $doc))
            ->assertRedirect();

        $this->assertDatabaseMissing('user_documents', ['id' => $doc->id]);
    }

    // ---------- Application attachment: validation + path safety ----------

    public function test_job_apply_rejects_invalid_attachment_mime(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->actingAs($user)->post(route('jobs.apply', $job), [
            'cover_letter' => $this->coverLetter(),
            'attachment' => UploadedFile::fake()->create('notes.txt', 100, 'text/plain'),
        ])->assertSessionHasErrors(['attachment']);

        $this->assertDatabaseMissing('applications', ['job_id' => $job->id, 'user_id' => $user->id]);
    }

    public function test_job_apply_rejects_oversized_attachment(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->actingAs($user)->post(route('jobs.apply', $job), [
            'cover_letter' => $this->coverLetter(),
            'attachment' => UploadedFile::fake()->create('big.pdf', 6000, 'application/pdf'),
        ])->assertSessionHasErrors(['attachment']);

        $this->assertDatabaseMissing('applications', ['job_id' => $job->id, 'user_id' => $user->id]);
    }

    public function test_job_apply_attachment_name_uses_basename_and_private_path(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->actingAs($user)->post(route('jobs.apply', $job), [
            'cover_letter' => $this->coverLetter(),
            'attachment' => UploadedFile::fake()->create('cv-siswa.pdf', 400, 'application/pdf'),
        ])->assertRedirect(route('jobs.show', $job));

        $app = Application::where('job_id', $job->id)->where('user_id', $user->id)->firstOrFail();
        $this->assertSame('cv-siswa.pdf', $app->attachment_name);
        $this->assertSame(basename($app->attachment_name), $app->attachment_name);
        $this->assertStringStartsWith('applications/', $app->attachment_path);
        $this->assertStringNotContainsString('..', $app->attachment_path);
        $this->assertStringNotContainsString('..', $app->attachment_name);
        Storage::disk('private')->assertExists($app->attachment_path);
    }

    public function test_application_destroy_succeeds_when_attachment_already_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $app = Application::factory()->create([
            'user_id' => $owner->id,
            'status' => 'submitted',
            'attachment_path' => 'applications/already-gone.pdf',
            'attachment_name' => 'gone.pdf',
        ]);

        $resp = $this->actingAs($owner)->delete(route('applications.destroy', $app));
        $resp->assertRedirect(route('applications.index'));
        $resp->assertSessionHas('success');
        $this->assertSoftDeleted('applications', ['id' => $app->id]);
    }

    // ---------- Company verify: validation + replace cleanup + role ----------

    public function test_company_verify_rejects_invalid_mime(): void
    {
        Storage::fake('private');
        $companyUser = $this->verifiedCompany();
        $company = $companyUser->company;

        $this->actingAs($companyUser)->post(route('company.profile.verify'), [
            'business_license' => UploadedFile::fake()->create('notes.txt', 100, 'text/plain'),
        ])->assertSessionHasErrors(['business_license']);

        $this->assertNull($company->fresh()->business_license_path);
    }

    public function test_company_verify_rejects_oversized_file(): void
    {
        Storage::fake('private');
        $companyUser = $this->verifiedCompany();
        $company = $companyUser->company;

        $this->actingAs($companyUser)->post(route('company.profile.verify'), [
            'business_license' => UploadedFile::fake()->create('big.pdf', 6000, 'application/pdf'),
        ])->assertSessionHasErrors(['business_license']);

        $this->assertNull($company->fresh()->business_license_path);
    }

    public function test_company_verify_replaces_old_file_and_cleans_up(): void
    {
        Storage::fake('private');
        $companyUser = $this->verifiedCompany();
        $company = $companyUser->company;

        $oldPath = 'company_verifications/'.$company->id.'/business_license_old.pdf';
        Storage::disk('private')->put($oldPath, 'old-content');
        $company->update(['business_license_path' => $oldPath]);

        $resp = $this->actingAs($companyUser)->post(route('company.profile.verify'), [
            'business_license' => UploadedFile::fake()->create('new-license.pdf', 200, 'application/pdf'),
        ]);

        $resp->assertRedirect(route('company.profile.edit'));
        $fresh = $company->fresh();
        $this->assertNotNull($fresh->business_license_path);
        $this->assertNotSame($oldPath, $fresh->business_license_path);
        $this->assertStringStartsWith('company_verifications/'.$company->id, $fresh->business_license_path);
        Storage::disk('private')->assertMissing($oldPath);
        Storage::disk('private')->assertExists($fresh->business_license_path);
    }

    public function test_company_verify_forbidden_for_umum_role(): void
    {
        $umum = $this->verifiedUmum();

        $this->actingAs($umum)->post(route('company.profile.verify'), [
            'tax_number' => '123',
        ])->assertForbidden();
    }

    // ---------- Admin MOU: validation + replace cleanup ----------

    public function test_admin_company_store_rejects_invalid_mou_mime(): void
    {
        Storage::fake('private');
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.companies.store'), [
            'name' => 'PT Invalid MoU',
            'mou_path' => UploadedFile::fake()->create('notes.txt', 100, 'text/plain'),
        ])->assertSessionHasErrors(['mou_path']);

        $this->assertDatabaseMissing('companies', ['name' => 'PT Invalid MoU']);
    }

    public function test_admin_company_store_rejects_oversized_mou(): void
    {
        Storage::fake('private');
        $admin = $this->admin();

        $this->actingAs($admin)->post(route('admin.companies.store'), [
            'name' => 'PT Oversized MoU',
            'mou_path' => UploadedFile::fake()->create('big.pdf', 11000, 'application/pdf'),
        ])->assertSessionHasErrors(['mou_path']);

        $this->assertDatabaseMissing('companies', ['name' => 'PT Oversized MoU']);
    }

    public function test_admin_mou_update_replaces_old_file_and_cleans_up(): void
    {
        Storage::fake('private');
        Storage::fake('local');
        $admin = $this->admin();
        $oldPath = 'company_mou/old-mou.pdf';
        Storage::disk('private')->put($oldPath, 'old-mou');
        $company = Company::factory()->create(['name' => 'PT Replace MoU', 'mou_path' => $oldPath]);

        $resp = $this->actingAs($admin)->put(route('admin.companies.update', $company), [
            'name' => 'PT Replace MoU',
            'mou_path' => UploadedFile::fake()->create('new-mou.pdf', 200, 'application/pdf'),
        ]);

        $resp->assertRedirect(route('admin.companies.index'));
        $fresh = $company->fresh();
        $this->assertNotNull($fresh->mou_path);
        $this->assertNotSame($oldPath, $fresh->mou_path);
        $this->assertStringStartsWith('company_mou/', $fresh->mou_path);
        Storage::disk('private')->assertMissing($oldPath);
        Storage::disk('private')->assertExists($fresh->mou_path);
    }

    // ---------- Company MOU download isolation ----------

    public function test_company_mou_download_is_scoped_to_own_company(): void
    {
        Storage::fake('private');
        Storage::fake('local');
        $owner = $this->verifiedCompany();
        $other = $this->verifiedCompany();
        $path = 'company_mou/owner-secret.pdf';
        Storage::disk('private')->put($path, 'mou-content');
        $owner->company->update(['mou_path' => $path]);

        $ok = $this->actingAs($owner)->get(route('company.mou.download'));
        $ok->assertOk();
        $this->assertNotNull($ok->headers->get('content-disposition'));

        // Existing behavior: route is scoped to auth user's own company
        // (no {company} parameter), so another company resolves its own
        // company — which has no MOU — and receives 404, never the
        // owner's file. This proves no cross-company leakage.
        $this->actingAs($other)->get(route('company.mou.download'))
            ->assertNotFound();
    }

    public function test_company_mou_download_returns_404_when_file_missing(): void
    {
        Storage::fake('private');
        Storage::fake('local');
        $owner = $this->verifiedCompany();
        $owner->company->update(['mou_path' => 'company_mou/gone.pdf']);

        $this->actingAs($owner)->get(route('company.mou.download'))
            ->assertNotFound();
    }

    // ---------- Admin legal document download ----------

    public function test_admin_legal_document_download_isolated(): void
    {
        Storage::fake('private');
        $admin = $this->admin();
        $path = 'company_verifications/1/business_license_test.pdf';
        Storage::disk('private')->put($path, 'legal-content');
        $company = Company::factory()->create(['business_license_path' => $path]);

        $this->actingAs($admin)
            ->get(route('admin.companies.documents.download', [$company, 'business-license']))
            ->assertOk();
    }

    public function test_admin_legal_document_download_returns_404_for_unknown_type_or_missing_file(): void
    {
        Storage::fake('private');
        $admin = $this->admin();
        $company = Company::factory()->create(['business_license_path' => null]);

        // unknown document slug aborts 404 via match default
        $this->actingAs($admin)
            ->get(route('admin.companies.documents.download', [$company, 'not-a-doc']))
            ->assertNotFound();

        // known type but no path stored
        $this->actingAs($admin)
            ->get(route('admin.companies.documents.download', [$company, 'business-license']))
            ->assertNotFound();

        // path stored but file missing in storage
        $company->update(['business_license_path' => 'company_verifications/1/gone.pdf']);
        $this->actingAs($admin)
            ->get(route('admin.companies.documents.download', [$company->fresh(), 'business-license']))
            ->assertNotFound();
    }

    public function test_admin_legal_document_download_forbidden_for_non_admin(): void
    {
        Storage::fake('private');
        $umum = $this->verifiedUmum();
        $path = 'company_verifications/1/business_license_test.pdf';
        Storage::disk('private')->put($path, 'legal-content');
        $company = Company::factory()->create(['business_license_path' => $path]);

        $this->actingAs($umum)
            ->get(route('admin.companies.documents.download', [$company, 'business-license']))
            ->assertForbidden();
    }

    // ---------- CvFile destroy when missing ----------

    public function test_cv_destroy_succeeds_when_file_already_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $cv = CvFile::factory()->create([
            'user_id' => $owner->id,
            'file_path' => 'cv-files/already-gone.pdf',
        ]);

        $this->actingAs($owner)->delete(route('cv.destroy', $cv))
            ->assertRedirect();

        $this->assertDatabaseMissing('cv_files', ['id' => $cv->id]);
    }
}
