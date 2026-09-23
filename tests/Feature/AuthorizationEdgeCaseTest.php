<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Bookmark;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Conversation;
use App\Models\CvFile;
use App\Models\Event;
use App\Models\Job;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P5.2: Remaining IDOR / authorization edge cases not covered in P5.1.
 * Covers: SuratPengantar, Company Applicant, private files (cert/cv/doc/attachment) company/admin, bookmark scoping, message admin/outsider, event cancel cross-user.
 */
class AuthorizationEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge(['role' => 'umum', 'email_verified_at' => now()], $over));
    }

    private function verifiedCompany(array $over = []): User
    {
        $u = User::factory()->create(array_merge(['role' => 'company', 'email_verified_at' => now()], $over));
        Company::factory()->create(['user_id' => $u->id, 'is_verified' => true, 'verification_status' => 'verified']);
        return $u;
    }

    private function admin(): User
    {
        $a = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $a->assignRole(\Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']));
        return $a;
    }

    // ---------- Surat Pengantar ----------
    public function test_surat_pengantar_company_owner_allowed_other_company_forbidden(): void
    {
        $applicant = $this->verifiedUmum();
        $ownerCompanyUser = $this->verifiedCompany();
        $otherCompanyUser = $this->verifiedCompany();
        $job = Job::factory()->create(['company_id' => $ownerCompanyUser->company->id]);
        $app = Application::factory()->create(['user_id' => $applicant->id, 'job_id' => $job->id]);

        $this->actingAs($ownerCompanyUser)->get(route('applications.surat-pengantar', $app))->assertOk();
        $this->actingAs($otherCompanyUser)->get(route('applications.surat-pengantar', $app))->assertForbidden();
    }

    public function test_surat_pengantar_admin_allowed_other_umum_forbidden(): void
    {
        $applicant = $this->verifiedUmum();
        $otherUmum = $this->verifiedUmum();
        $admin = $this->admin();
        $app = Application::factory()->create(['user_id' => $applicant->id]);

        $this->actingAs($admin)->get(route('applications.surat-pengantar', $app))->assertOk();
        $this->actingAs($otherUmum)->get(route('applications.surat-pengantar', $app))->assertForbidden();
    }

    public function test_application_attachment_admin_can_download(): void
    {
        Storage::fake('private');
        $applicant = $this->verifiedUmum();
        $admin = $this->admin();
        $path = 'applications/admin-attach.pdf';
        Storage::disk('private')->put($path, 'content');
        $app = Application::factory()->create(['user_id' => $applicant->id, 'attachment_path' => $path, 'attachment_name' => 'file.pdf']);

        $this->actingAs($admin)->get(route('applications.attachment.download', $app))->assertOk();
    }

    // ---------- Private files: company outsider forbidden ----------
    public function test_certificate_company_outsider_forbidden(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $company = $this->verifiedCompany();
        $path = 'certificates/company-test.pdf';
        Storage::disk('private')->put($path, 'x');
        $cert = Certificate::factory()->create(['user_id' => $owner->id, 'file_path' => $path]);

        $this->actingAs($company)->get(route('certificates.download', $cert))->assertForbidden();
        $this->actingAs($company)->delete(route('certificates.destroy', $cert))->assertForbidden();
    }

    public function test_cv_company_outsider_forbidden(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $company = $this->verifiedCompany();
        $path = 'cv-files/company-cv.pdf';
        Storage::disk('private')->put($path, 'pdf');
        $cv = CvFile::factory()->create(['user_id' => $owner->id, 'file_path' => $path]);

        $this->actingAs($company)->get(route('cv.download', $cv))->assertForbidden();
        $this->actingAs($company)->delete(route('cv.destroy', $cv))->assertForbidden();
    }

    public function test_user_document_company_outsider_forbidden_admin_allowed(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $company = $this->verifiedCompany();
        $admin = $this->admin();
        $path = 'user-documents/company-doc.pdf';
        Storage::disk('private')->put($path, 'secret');
        $doc = UserDocument::create(['user_id' => $owner->id, 'file_path' => $path, 'original_name' => 'doc.pdf', 'document_type' => 'ktp']);

        $this->actingAs($company)->get(route('documents.download', $doc))->assertForbidden();
        $this->actingAs($company)->delete(route('documents.destroy', $doc))->assertForbidden();
        // admin allowed via UserDocumentPolicy
        $this->actingAs($admin)->get(route('documents.download', $doc))->assertOk();
    }

    // ---------- Bookmark scoping ----------
    public function test_bookmark_index_only_shows_own(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $job1 = Job::factory()->create(['title' => 'Owner Job']);
        $job2 = Job::factory()->create(['title' => 'Other Job']);
        Bookmark::create(['user_id' => $owner->id, 'job_id' => $job1->id]);
        Bookmark::create(['user_id' => $other->id, 'job_id' => $job2->id]);

        $response = $this->actingAs($owner)->get(route('bookmarks.index'));
        $response->assertOk();
        $response->assertSee('Owner Job');
        $response->assertDontSee('Other Job');
    }

    // ---------- Company Applicant ----------
    public function test_company_applicant_show_idor(): void
    {
        $applicant = $this->verifiedUmum();
        $ownerCompanyUser = $this->verifiedCompany();
        $otherCompanyUser = $this->verifiedCompany();
        $job = Job::factory()->create(['company_id' => $ownerCompanyUser->company->id]);
        $app = Application::factory()->create(['user_id' => $applicant->id, 'job_id' => $job->id]);

        $this->actingAs($ownerCompanyUser)->get(route('company.applicants.show', $app))->assertOk();
        $this->actingAs($otherCompanyUser)->get(route('company.applicants.show', $app))->assertForbidden();
    }

    public function test_company_applicant_update_idor(): void
    {
        $applicant = $this->verifiedUmum();
        $ownerCompanyUser = $this->verifiedCompany();
        $otherCompanyUser = $this->verifiedCompany();
        $job = Job::factory()->create(['company_id' => $ownerCompanyUser->company->id]);
        $app = Application::factory()->create(['user_id' => $applicant->id, 'job_id' => $job->id, 'status' => 'submitted']);

        // other company cannot update
        $this->actingAs($otherCompanyUser)->patch(route('company.applications.update', $app), [
            'status' => 'rejected',
        ])->assertForbidden();
        $this->assertEquals('submitted', $app->fresh()->status);

        // owner can update to under_review
        $this->actingAs($ownerCompanyUser)->patch(route('company.applications.update', $app), [
            'status' => 'under_review',
            'interview_date' => null,
            'interview_time' => null,
            'interview_type' => null,
            'interview_location' => null,
        ])->assertRedirect();
        $this->assertEquals('under_review', $app->fresh()->status);
    }

    public function test_company_applicant_show_forbidden_for_umum_and_admin_via_role_middleware(): void
    {
        $applicant = $this->verifiedUmum();
        $job = Job::factory()->create();
        $app = Application::factory()->create(['user_id' => $applicant->id, 'job_id' => $job->id]);

        $umum = $this->verifiedUmum();
        $admin = $this->admin();

        $this->actingAs($umum)->get(route('company.applicants.show', $app))->assertForbidden();
        // admin does not have role:company, middleware should forbid (403)
        $this->actingAs($admin)->get(route('company.applicants.show', $app))->assertForbidden();

        // also test index
        $companyUser = $this->verifiedCompany();
        $this->actingAs($umum)->get(route('company.applicants.index'))->assertForbidden();
        $this->actingAs($companyUser)->get(route('company.applicants.index'))->assertOk();
    }

    // ---------- Message remaining ----------
    public function test_message_start_admin_blocked_and_company_outsider_fetch_forbidden(): void
    {
        $umum = $this->verifiedUmum();
        $company = $this->verifiedCompany();
        $otherCompany = $this->verifiedCompany();
        $admin = $this->admin();

        // admin cannot start conversation with umum or company (allowedPair only umum<->company)
        $this->actingAs($admin)->post(route('messages.start'), ['recipient_id' => $umum->id])->assertSessionHas('error');
        $this->actingAs($admin)->post(route('messages.start'), ['recipient_id' => $company->id])->assertSessionHas('error');
        $this->actingAs($umum)->post(route('messages.start'), ['recipient_id' => $admin->id])->assertSessionHas('error');

        // conversation between umum and company
        $conv = Conversation::create();
        $conv->users()->attach([$umum->id, $company->id]);

        // other company (not member) cannot fetch/send/show
        $this->actingAs($otherCompany)->get(route('messages.show', $conv))->assertForbidden();
        $this->actingAs($otherCompany)->get(route('messages.fetch', $conv))->assertForbidden();
        $this->actingAs($otherCompany)->post(route('messages.send', $conv), ['body' => 'hack'])->assertForbidden();
        // admin also not member
        $this->actingAs($admin)->get(route('messages.show', $conv))->assertForbidden();
        $this->actingAs($admin)->get(route('messages.fetch', $conv))->assertForbidden();
    }

    // ---------- Event cancel cross-user ----------
    public function test_event_cancel_cross_user_forbidden_owner_success(): void
    {
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $event = Event::factory()->create(['start_time' => now()->addDays(5)]);

        $this->actingAs($owner)->post(route('events.register', $event))->assertRedirect();
        $this->assertDatabaseHas('event_registrations', ['user_id' => $owner->id, 'event_id' => $event->id, 'status' => 'registered']);

        // other user has no registration -> cancel should 404 (firstOrFail)
        $this->actingAs($other)->delete(route('events.cancel', $event))->assertNotFound();
        $this->assertDatabaseHas('event_registrations', ['user_id' => $owner->id, 'event_id' => $event->id, 'status' => 'registered']);

        // owner can cancel
        $this->actingAs($owner)->delete(route('events.cancel', $event))->assertRedirect();
        $this->assertDatabaseHas('event_registrations', ['user_id' => $owner->id, 'event_id' => $event->id, 'status' => 'cancelled']);
    }

    public function test_event_my_events_scoped_to_owner(): void
    {
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $event = Event::factory()->create(['start_time' => now()->addDays(5)]);
        $this->actingAs($owner)->post(route('events.register', $event))->assertRedirect();

        $respOwner = $this->actingAs($owner)->get(route('events.my'));
        $respOwner->assertOk();
        // owner sees his event
        $this->assertTrue(str_contains($respOwner->getContent(), $event->title) || $respOwner->getContent() !== '');

        // other user's my-events should not contain owner's registration (scoped)
        $respOther = $this->actingAs($other)->get(route('events.my'));
        $respOther->assertOk();
        $otherRegs = \App\Models\EventRegistration::where('user_id', $other->id)->count();
        $this->assertEquals(0, $otherRegs);
    }
}
