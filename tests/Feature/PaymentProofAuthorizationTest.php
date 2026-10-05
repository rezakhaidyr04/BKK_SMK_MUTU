<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * P1: bukti pembayaran adalah dokumen finansial — wajib private + otorisasi.
 */
class PaymentProofAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function paidEvent(): Event
    {
        return Event::factory()->create([
            'start_time' => now()->addDays(5),
            'is_paid' => true,
            'price' => 50000,
        ]);
    }

    private function verifiedUmum(): User
    {
        return User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
    }

    private function registrationWithProof(User $user, ?Event $event = null): EventRegistration
    {
        Storage::fake('private');
        $event ??= $this->paidEvent();
        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
            'payment_status' => 'pending',
            'registered_at' => now(),
        ]);
        $path = UploadedFile::fake()->image('bukti.jpg')->store('event-payments', 'private');
        $reg->update(['payment_proof' => $path]);
        return $reg->fresh();
    }

    public function test_owner_can_view_own_payment_proof(): void
    {
        $user = $this->verifiedUmum();
        $reg = $this->registrationWithProof($user);
        $this->actingAs($user)
            ->get(route('events.payment-proof.download', $reg))
            ->assertOk();
    }

    public function test_other_user_cannot_view_someone_elses_proof(): void
    {
        $owner = $this->verifiedUmum();
        $other = $this->verifiedUmum();
        $reg = $this->registrationWithProof($owner);
        $this->actingAs($other)
            ->get(route('events.payment-proof.download', $reg))
            ->assertForbidden();
    }

    public function test_admin_can_view_payment_proof(): void
    {
        $owner = $this->verifiedUmum();
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $reg = $this->registrationWithProof($owner);
        $this->actingAs($admin)
            ->get(route('events.payment-proof.download', $reg))
            ->assertOk();
    }

    public function test_guest_cannot_view_payment_proof(): void
    {
        $owner = $this->verifiedUmum();
        $reg = $this->registrationWithProof($owner);
        $this->get(route('events.payment-proof.download', $reg))->assertRedirect();
    }

    public function test_company_cannot_view_payment_proof(): void
    {
        $owner = $this->verifiedUmum();
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $reg = $this->registrationWithProof($owner);
        $this->actingAs($companyUser)
            ->get(route('events.payment-proof.download', $reg))
            ->assertForbidden();
    }

    public function test_missing_file_returns_404(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $event = $this->paidEvent();
        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
            'payment_status' => 'pending',
            'registered_at' => now(),
            'payment_proof' => 'event-payments/sudah-hilang.jpg',
        ]);
        $this->actingAs($user)
            ->get(route('events.payment-proof.download', $reg))
            ->assertNotFound();
    }

    public function test_no_proof_returns_404(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $event = $this->paidEvent();
        $reg = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
            'payment_status' => 'unpaid',
            'registered_at' => now(),
        ]);
        $this->actingAs($user)
            ->get(route('events.payment-proof.download', $reg))
            ->assertNotFound();
    }

    public function test_upload_stores_proof_on_private_disk(): void
    {
        Storage::fake('private');
        Storage::fake('public');
        $user = $this->verifiedUmum();
        $event = $this->paidEvent();
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => 'registered',
            'payment_status' => 'unpaid',
            'registered_at' => now(),
        ]);

        $this->actingAs($user)->post(route('events.payment-proof', $event), [
            'payment_proof' => UploadedFile::fake()->image('transfer.jpg'),
        ])->assertRedirect();

        $reg = EventRegistration::where('event_id', $event->id)->where('user_id', $user->id)->firstOrFail();
        $this->assertStringStartsWith('event-payments/', $reg->payment_proof);
        Storage::disk('private')->assertExists($reg->payment_proof);
        Storage::disk('public')->assertMissing($reg->payment_proof);
    }
}
