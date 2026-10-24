<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Notifications\CompanyVerificationSubmitted;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * Bug: perusahaan ditolak → memperbaiki dokumen → mengajukan ulang, tapi
 * admin tidak menerima info apa pun. Juga memastikan form verifikasi
 * (yang pernah terhapus) kembali ada di halaman profil perusahaan.
 */
class CompanyVerificationResubmitTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $over = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'admin',
            'email_verified_at' => now(),
        ], $over));
    }

    /** Company user + company berstatus rejected dengan alasan. */
    private function rejectedCompany(): array
    {
        $owner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create([
            'user_id' => $owner->id,
            'is_verified' => false,
            'verification_status' => 'rejected',
            'rejection_reason' => 'NPWP tidak terbaca.',
        ]);

        return [$owner, $company];
    }

    private function submitVerification(array $payload = []): \Illuminate\Testing\TestResponse
    {
        return $this->post(route('company.profile.verify'), array_merge([
            'tax_number' => '12.345.678.9-012.345',
        ], $payload));
    }

    public function test_resubmission_after_reject_sets_pending_and_notifies_all_admins(): void
    {
        [$owner, $company] = $this->rejectedCompany();
        $admin1 = $this->admin();
        $admin2 = $this->admin(['email' => 'admin2@example.test']);

        Notification::fake();

        $this->actingAs($owner)->submitVerification()
            ->assertRedirect(route('company.profile.edit'));

        $company->refresh();
        $this->assertSame('pending', $company->verification_status);
        $this->assertFalse($company->is_verified);
        $this->assertSame('NPWP tidak terbaca.', $company->rejection_reason, 'alasan lama tetap disimpan sebagai konteks admin');

        Notification::assertSentTo($admin1, CompanyVerificationSubmitted::class);
        Notification::assertSentTo($admin2, CompanyVerificationSubmitted::class);
        Notification::assertNotSentTo($owner, CompanyVerificationSubmitted::class);

        Notification::assertSentTo($admin1, CompanyVerificationSubmitted::class, function ($notification) use ($owner) {
            $data = $notification->toArray($owner);

            return $data['type'] === 'company_verification_submitted'
                && str_contains($data['message'], 'mengajukan ulang')
                && str_contains($data['message'], 'memperbaiki')
                && $data['rejection_reason'] === 'NPWP tidak terbaca.';
        });
    }

    public function test_resubmission_notification_is_stored_for_admin(): void
    {
        [$owner] = $this->rejectedCompany();
        $admin = $this->admin();

        $this->actingAs($owner)->submitVerification();

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $admin->id,
            'notifiable_type' => User::class,
        ]);

        $notification = $admin->notifications()->latest('id')->first();
        $this->assertSame('company_verification_submitted', $notification->data['type']);
        $this->assertStringContainsString('/admin/companies/', $notification->data['url']);
        $this->assertStringContainsString('mengajukan ulang', $notification->data['message']);
    }

    public function test_admin_clicking_notification_goes_to_company_review_page(): void
    {
        [$owner, $company] = $this->rejectedCompany();
        $admin = $this->admin();

        $this->actingAs($owner)->submitVerification();

        $notification = $admin->notifications()->latest('id')->first();
        $this->assertNotNull($notification);

        $this->actingAs($admin)
            ->post(route('notifications.go', $notification->id))
            ->assertRedirect(route('admin.companies.show', $company));
    }

    public function test_first_time_submission_does_not_notify_admins(): void
    {
        $owner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->create([
            'user_id' => $owner->id,
            'verification_status' => 'pending',
        ]);
        $this->admin();

        Notification::fake();

        $this->actingAs($owner)->submitVerification()
            ->assertRedirect(route('company.profile.edit'));

        Notification::assertNothingSent();
    }

    public function test_failed_validation_sends_no_admin_notification(): void
    {
        [$owner] = $this->rejectedCompany();
        $this->admin();

        Notification::fake();

        // Backend tetap memvalidasi file bila dikirim programatikus
        // (mis. API/lama) — gagal validasi tidak boleh kirim notifikasi.
        $this->actingAs($owner)->submitVerification([
            'business_license' => UploadedFile::fake()->create('notes.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors('business_license');

        Notification::assertNothingSent();
        $this->assertSame('rejected', $owner->company->fresh()->verification_status);
    }

    public function test_profile_page_has_verification_form_for_rejected_company(): void
    {
        [$owner] = $this->rejectedCompany();

        $response = $this->actingAs($owner)->get(route('company.profile.edit'));

        $response->assertOk()
            ->assertSee('id="verification"', false)
            ->assertSee('action="'.route('company.profile.verify').'"', false)
            ->assertSee('Ajukan Kembali Verifikasi', false)
            ->assertSee('NPWP tidak terbaca.', false)
            ->assertSee('Buka Data Perusahaan', false)
            ->assertSee('Tidak perlu mengunggah dokumen legal', false)
            // Form dokumen legal tidak boleh ada lagi (MoU admin = dasar kepercayaan).
            ->assertDontSee('name="business_license"', false)
            ->assertDontSee('name="operating_license"', false)
            ->assertDontSee('name="tax_number"', false);
    }

    public function test_profile_page_hides_form_for_verified_company(): void
    {
        $owner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->approved()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->get(route('company.profile.edit'));

        $response->assertOk()
            ->assertSee('id="verification"', false)
            ->assertSee('sudah terverifikasi', false)
            ->assertDontSee('company/profile/verify', false);
    }

    public function test_checklist_shows_missing_profile_fields(): void
    {
        [$owner] = $this->rejectedCompany();

        $response = $this->actingAs($owner)->get(route('company.profile.edit'));

        $response->assertOk()
            ->assertSee('Kelengkapan Data Perusahaan', false)
            // assertSee default: escape nilai argumen dulu (& → &amp;) cocok dgn render HTML.
            ->assertSee('Email & telepon — belum diisi')
            ->assertSee('Logo perusahaan — belum diisi')
            // Field yang sudah terisi factory tidak ditandai kosong.
            ->assertDontSee('Deskripsi perusahaan — belum diisi', false);
    }

    public function test_resubmission_without_any_payload_notifies_and_sets_pending(): void
    {
        [$owner, $company] = $this->rejectedCompany();
        $admin = $this->admin();

        Notification::fake();

        $this->actingAs($owner)->post(route('company.profile.verify'))
            ->assertRedirect(route('company.profile.edit'));

        $fresh = $company->fresh();
        $this->assertSame('pending', $fresh->verification_status);
        $this->assertFalse($fresh->is_verified);
        $this->assertNull($fresh->business_license_path);
        Notification::assertSentTo($admin, CompanyVerificationSubmitted::class);
    }
}
