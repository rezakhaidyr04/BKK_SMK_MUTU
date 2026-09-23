<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P5.1 P0: Authorization / IDOR + status transition untuk Application.
 */
class ApplicationAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'umum',
            'email_verified_at' => now(),
        ], $over));
    }

    private function verifiedCompanyUser(array $over = []): User
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

    public function test_umum_cannot_view_other_users_application(): void
    {
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $app = Application::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->get(route('applications.show', $app))
            ->assertForbidden();
    }

    public function test_umum_cannot_download_other_users_attachment(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        // create real file for owner
        $path = 'applications/test-attach.pdf';
        Storage::disk('private')->put($path, 'dummy');

        $app = Application::factory()->create([
            'user_id' => $owner->id,
            'attachment_path' => $path,
            'attachment_name' => 'cv.pdf',
        ]);

        $this->actingAs($other)
            ->get(route('applications.attachment.download', $app))
            ->assertForbidden();

        // owner should succeed (200 download)
        $this->actingAs($owner)
            ->get(route('applications.attachment.download', $app))
            ->assertOk();
    }

    public function test_download_returns_404_when_file_missing(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $app = Application::factory()->create([
            'user_id' => $owner->id,
            'attachment_path' => 'applications/missing.pdf',
            'attachment_name' => 'missing.pdf',
        ]);

        $this->actingAs($owner)
            ->get(route('applications.attachment.download', $app))
            ->assertNotFound();
    }

    public function test_download_returns_404_when_no_attachment(): void
    {
        $owner = $this->verifiedUmum();
        $app = Application::factory()->create([
            'user_id' => $owner->id,
            'attachment_path' => null,
        ]);

        $this->actingAs($owner)
            ->get(route('applications.attachment.download', $app))
            ->assertNotFound();
    }

    public function test_cannot_destroy_other_users_application(): void
    {
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $app = Application::factory()->create(['user_id' => $owner->id, 'status' => 'submitted']);

        $this->actingAs($other)
            ->delete(route('applications.destroy', $app))
            ->assertForbidden();

        $this->assertDatabaseHas('applications', ['id' => $app->id]);
    }

    public function test_cannot_destroy_accepted_application(): void
    {
        $owner = $this->verifiedUmum();
        $app = Application::factory()->create(['user_id' => $owner->id, 'status' => 'accepted']);

        $response = $this->actingAs($owner)->delete(route('applications.destroy', $app));
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('applications', ['id' => $app->id]);
    }

    public function test_cannot_destroy_rejected_application(): void
    {
        $owner = $this->verifiedUmum();
        $app = Application::factory()->create(['user_id' => $owner->id, 'status' => 'rejected']);

        $response = $this->actingAs($owner)->delete(route('applications.destroy', $app));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('applications', ['id' => $app->id]);
    }

    public function test_owner_can_destroy_submitted_application_and_file_is_deleted(): void
    {
        Storage::fake('private');
        $owner = $this->verifiedUmum();
        $path = 'applications/to-delete.pdf';
        Storage::disk('private')->put($path, 'content');

        $app = Application::factory()->create([
            'user_id' => $owner->id,
            'status' => 'submitted',
            'attachment_path' => $path,
        ]);

        $response = $this->actingAs($owner)->delete(route('applications.destroy', $app));
        $response->assertRedirect(route('applications.index'));
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('applications', ['id' => $app->id]);
        Storage::disk('private')->assertMissing($path);
    }

    public function test_company_can_view_application_for_own_job_but_not_others(): void
    {
        $companyOwner = $this->verifiedCompanyUser();
        $companyOther = $this->verifiedCompanyUser();
        $applicant = $this->verifiedUmum();

        $companyOwned = $companyOwner->company;
        $jobOwned = Job::factory()->create(['company_id' => $companyOwned->id]);
        $app = Application::factory()->create(['job_id' => $jobOwned->id, 'user_id' => $applicant->id]);

        // owner company can view
        $this->actingAs($companyOwner)->get(route('applications.show', $app))->assertOk();
        $this->actingAs($companyOwner)->get(route('applications.attachment.download', $app->fresh()))->assertNotFound(); // no file, but authorized (404 not 403)

        // other company cannot view
        $this->actingAs($companyOther)->get(route('applications.show', $app))->assertForbidden();
    }

    public function test_admin_can_view_any_application_via_policy(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $admin->assignRole(\Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']));

        $owner = $this->verifiedUmum();
        $app = Application::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($admin)->get(route('applications.show', $app))->assertOk();
    }

    public function test_guest_cannot_access_applications(): void
    {
        $app = Application::factory()->create();
        $this->get(route('applications.index'))->assertRedirect();
        $this->get(route('applications.show', $app))->assertRedirect();
    }

    public function test_surapengantar_requires_authorization(): void
    {
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $app = Application::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)->get(route('applications.surat-pengantar', $app))->assertForbidden();
        // owner passes authorization (will attempt pdf generation, assert not forbidden)
        $resp = $this->actingAs($owner)->get(route('applications.surat-pengantar', $app));
        $this->assertNotEquals(403, $resp->getStatusCode());
    }
}
