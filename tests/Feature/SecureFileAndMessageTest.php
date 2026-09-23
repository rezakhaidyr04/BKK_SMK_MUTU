<?php

namespace Tests\Feature;

use App\Models\Bookmark;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Conversation;
use App\Models\CvFile;
use App\Models\Job;
use App\Models\User;
use App\Models\UserDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P5.1 P0/P1: IDOR untuk private files (cert/cv/doc/bookmark) + Message authorization.
 */
class SecureFileAndMessageTest extends TestCase
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

    // ---------- Certificate ----------
    public function test_certificate_download_idor(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $path = 'certificates/test.pdf';
        Storage::disk('private')->put($path, 'content');
        $cert = Certificate::factory()->create(['user_id' => $owner->id, 'file_path' => $path]);

        $this->actingAs($other)->get(route('certificates.download', $cert))->assertForbidden();
        $this->actingAs($owner)->get(route('certificates.download', $cert))->assertOk();
    }

    public function test_certificate_destroy_idor_and_file_deleted(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $path = 'certificates/del.pdf';
        Storage::disk('private')->put($path, 'x');
        $cert = Certificate::factory()->create(['user_id' => $owner->id, 'file_path' => $path]);

        $this->actingAs($other)->delete(route('certificates.destroy', $cert))->assertForbidden();
        $this->assertDatabaseHas('certificates', ['id' => $cert->id]);

        $this->actingAs($owner)->delete(route('certificates.destroy', $cert))->assertRedirect();
        $this->assertDatabaseMissing('certificates', ['id' => $cert->id]);
        Storage::disk('private')->assertMissing($path);
    }

    public function test_certificate_download_returns_404_when_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $cert = Certificate::factory()->create(['user_id' => $owner->id, 'file_path' => 'certificates/missing.pdf']);

        $this->actingAs($owner)->get(route('certificates.download', $cert))->assertNotFound();
    }

    public function test_admin_can_download_any_certificate(): void
    {
        Storage::fake('private');
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $admin->assignRole(\Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']));
        $owner = $this->verifiedUmum();
        $path = 'certificates/admin.pdf';
        Storage::disk('private')->put($path, 'x');
        $cert = Certificate::factory()->create(['user_id' => $owner->id, 'file_path' => $path]);

        $this->actingAs($admin)->get(route('certificates.download', $cert))->assertOk();
    }

    // ---------- CvFile ----------
    public function test_cv_download_and_destroy_idor(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $path = 'cv-files/cv.pdf';
        Storage::disk('private')->put($path, 'pdf');
        $cv = CvFile::factory()->create(['user_id' => $owner->id, 'file_path' => $path]);

        $this->actingAs($other)->get(route('cv.download', $cv))->assertForbidden();
        $this->actingAs($other)->delete(route('cv.destroy', $cv))->assertForbidden();
        $this->assertDatabaseHas('cv_files', ['id' => $cv->id]);

        $this->actingAs($owner)->get(route('cv.download', $cv))->assertOk();
        $this->actingAs($owner)->delete(route('cv.destroy', $cv))->assertRedirect();
        $this->assertDatabaseMissing('cv_files', ['id' => $cv->id]);
        Storage::disk('private')->assertMissing($path);
    }

    public function test_cv_download_returns_error_when_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $cv = CvFile::factory()->create(['user_id' => $owner->id, 'file_path' => 'cv-files/missing.pdf']);

        $resp = $this->actingAs($owner)->get(route('cv.download', $cv));
        // controller returns back with error when file missing, not 404
        $resp->assertRedirect();
        $resp->assertSessionHas('error');
    }

    // ---------- UserDocument ----------
    public function test_user_document_store_requires_umum_role(): void
    {
        Storage::fake('private');
        $company = $this->verifiedCompany();
        $company->email_verified_at = now();
        $company->save();

        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');
        $this->actingAs($company)->post(route('documents.store'), [
            'document_type' => 'ktp',
            'file' => $file,
        ])->assertForbidden();
    }

    public function test_user_document_download_and_destroy_idor(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $path = 'user-documents/secret.pdf';
        Storage::disk('private')->put($path, 'secret');
        $doc = UserDocument::create(['user_id' => $owner->id, 'file_path' => $path, 'original_name' => 'secret.pdf', 'document_type' => 'ktp']);

        $this->actingAs($other)->get(route('documents.download', $doc))->assertForbidden();
        $this->actingAs($other)->delete(route('documents.destroy', $doc))->assertForbidden();
        $this->assertDatabaseHas('user_documents', ['id' => $doc->id]);

        $this->actingAs($owner)->get(route('documents.download', $doc))->assertOk();
        $this->actingAs($owner)->delete(route('documents.destroy', $doc))->assertRedirect();
        $this->assertDatabaseMissing('user_documents', ['id' => $doc->id]);
        Storage::disk('private')->assertMissing($path);
    }

    // ---------- Bookmark ----------
    public function test_bookmark_destroy_idor(): void
    {
        $owner = User::factory()->create(['email_verified_at' => now()]);
        $other = User::factory()->create(['email_verified_at' => now()]);
        $job = Job::factory()->create();
        $bookmark = Bookmark::create(['user_id' => $owner->id, 'job_id' => $job->id]);

        $this->actingAs($other)->delete(route('bookmarks.destroy', $bookmark))->assertForbidden();
        $this->assertDatabaseHas('bookmarks', ['id' => $bookmark->id]);

        $this->actingAs($owner)->delete(route('bookmarks.destroy', $bookmark))->assertRedirect();
        $this->assertDatabaseMissing('bookmarks', ['id' => $bookmark->id]);
    }

    // ---------- Message ----------
    public function test_message_show_fetch_send_forbidden_for_outsider(): void
    {
        $umum = $this->verifiedUmum();
        $company = $this->verifiedCompany();
        $outsider = $this->verifiedUmum();

        // create conversation between umum and company
        $conv = Conversation::create();
        $conv->users()->attach([$umum->id, $company->id]);

        $this->actingAs($outsider)->get(route('messages.show', $conv))->assertForbidden();
        $this->actingAs($outsider)->get(route('messages.fetch', $conv))->assertForbidden();
        $this->actingAs($outsider)->post(route('messages.send', $conv), ['body' => 'hi'])->assertForbidden();

        // insider can access
        $this->actingAs($umum)->get(route('messages.show', $conv))->assertOk();
        $this->actingAs($umum)->get(route('messages.fetch', $conv))->assertOk();
    }

    public function test_message_start_blocked_for_same_role_pairs(): void
    {
        $umum1 = $this->verifiedUmum();
        $umum2 = $this->verifiedUmum();
        $comp1 = $this->verifiedCompany();
        $comp2 = $this->verifiedCompany();
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);

        // umum -> umum blocked
        $this->actingAs($umum1)->post(route('messages.start'), ['recipient_id' => $umum2->id])
            ->assertSessionHas('error');

        // company -> company blocked
        $this->actingAs($comp1)->post(route('messages.start'), ['recipient_id' => $comp2->id])
            ->assertSessionHas('error');

        // umum -> admin blocked
        $this->actingAs($umum1)->post(route('messages.start'), ['recipient_id' => $admin->id])
            ->assertSessionHas('error');

        // company -> admin blocked
        $this->actingAs($comp1)->post(route('messages.start'), ['recipient_id' => $admin->id])
            ->assertSessionHas('error');

        $this->assertEquals(0, Conversation::count());
    }

    public function test_duplicate_apply_blocked_and_company_cannot_apply(): void
    {
        $umum = $this->verifiedUmum();
        $companyUser = $this->verifiedCompany();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $cover = str_repeat('Saya sangat tertarik dengan posisi ini dan memenuhi kualifikasi. ', 3);
        // first apply success
        $this->actingAs($umum)->post(route('jobs.apply', $job), ['cover_letter' => $cover])->assertRedirect();
        $this->assertDatabaseHas('applications', ['job_id' => $job->id, 'user_id' => $umum->id]);

        // second apply blocked
        $resp = $this->actingAs($umum)->post(route('jobs.apply', $job), ['cover_letter' => $cover]);
        $resp->assertSessionHas('error');
        $this->assertEquals(1, \App\Models\Application::where('job_id', $job->id)->where('user_id', $umum->id)->count());

        // company cannot apply (403)
        $this->actingAs($companyUser)->post(route('jobs.apply', $job), ['cover_letter' => $cover])->assertForbidden();
    }

    public function test_event_register_handles_double_and_past_event(): void
    {
        $verified = $this->verifiedUmum();
        $event = \App\Models\Event::factory()->create(['start_time' => now()->addDays(5)]);

        // first register success
        $this->actingAs($verified)->post(route('events.register', $event))->assertRedirect();
        $this->assertDatabaseHas('event_registrations', ['user_id' => $verified->id, 'event_id' => $event->id, 'status' => 'registered']);

        // double register blocked
        $this->actingAs($verified)->post(route('events.register', $event))->assertSessionHas('error');
        $this->assertEquals(1, \App\Models\EventRegistration::where('user_id', $verified->id)->where('event_id', $event->id)->count());

        // past event blocked
        $past = \App\Models\Event::factory()->create(['start_time' => now()->subDay()]);
        $this->actingAs($verified)->post(route('events.register', $past))->assertSessionHas('error');
        $this->assertDatabaseMissing('event_registrations', ['user_id' => $verified->id, 'event_id' => $past->id]);
    }
}
