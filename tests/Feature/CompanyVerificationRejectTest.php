<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use App\Notifications\CompanyVerificationResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Bug: admin menolak verifikasi dengan alasan, tapi perusahaan tidak
 * menerima info apa pun dan dashboard tetap menampilkan "Menunggu verifikasi".
 */
class CompanyVerificationRejectTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'company', 'guard_name' => 'web']);

        $this->admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->admin->assignRole('admin');
    }

    private function companyWithOwner(): array
    {
        $owner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create([
            'user_id' => $owner->id,
            'verification_status' => 'pending',
        ]);

        return [$owner, $company];
    }

    public function test_reject_notifies_company_owner_with_reason(): void
    {
        [$owner, $company] = $this->companyWithOwner();

        Notification::fake();

        $this->actingAs($this->admin)->post(route('admin.companies.reject', $company), [
            'rejection_reason' => 'Dokumen NPWP belum diunggah.',
        ])->assertRedirect(route('admin.companies.index'));

        Notification::assertSentTo($owner, CompanyVerificationResult::class, function ($notification) use ($company, $owner) {
            $data = $notification->toArray($owner);

            return $data['type'] === 'company_verification'
                && $data['approved'] === false
                && $data['rejection_reason'] === 'Dokumen NPWP belum diunggah.'
                && str_contains($data['message'], 'ditolak');
        });
    }

    public function test_reject_stores_database_notification_for_owner(): void
    {
        [$owner, $company] = $this->companyWithOwner();

        $this->actingAs($this->admin)->post(route('admin.companies.reject', $company), [
            'rejection_reason' => 'Alamat kantor tidak sesuai.',
        ]);

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $owner->id,
            'notifiable_type' => User::class,
        ]);

        $notification = $owner->notifications()->latest('id')->first();
        $this->assertSame('company_verification', $notification->data['type']);
        $this->assertFalse($notification->data['approved']);
        $this->assertSame('Alamat kantor tidak sesuai.', $notification->data['rejection_reason']);
        $this->assertNotEmpty($notification->data['message']);
    }

    public function test_approve_notifies_company_owner(): void
    {
        [$owner, $company] = $this->companyWithOwner();

        Notification::fake();

        $this->actingAs($this->admin)->post(route('admin.companies.approve', $company))
            ->assertRedirect(route('admin.companies.index'));

        Notification::assertSentTo($owner, CompanyVerificationResult::class, function ($notification) use ($owner) {
            return $notification->toArray($owner)['approved'] === true;
        });
    }

    public function test_reject_company_without_account_does_not_fail(): void
    {
        $company = Company::factory()->withoutAccount()->create(['verification_status' => 'pending']);

        $this->actingAs($this->admin)->post(route('admin.companies.reject', $company), [
            'rejection_reason' => 'Identitas belum jelas.',
        ])->assertRedirect(route('admin.companies.index'));

        $this->assertSame('rejected', $company->fresh()->verification_status);
    }

    public function test_dashboard_shows_rejected_status_and_reason(): void
    {
        [$owner, $company] = $this->companyWithOwner();
        $company->update([
            'verification_status' => 'rejected',
            'is_verified' => false,
            'rejection_reason' => 'NPWP tidak terbaca.',
        ]);

        $response = $this->actingAs($owner)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Verifikasi Ditolak')
            ->assertSee('NPWP tidak terbaca.')
            ->assertDontSee('Menunggu verifikasi');
    }

    public function test_dashboard_pending_still_shows_waiting_status(): void
    {
        [$owner] = $this->companyWithOwner();

        $response = $this->actingAs($owner)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Menunggu verifikasi')
            ->assertDontSee('Verifikasi Ditolak');
    }

    public function test_dashboard_verified_shows_verified_status(): void
    {
        $owner = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        Company::factory()->approved()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->get(route('dashboard'));

        $response->assertOk()
            ->assertSee('Terverifikasi')
            ->assertDontSee('Verifikasi Ditolak');
    }
}
