<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailAddress;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Stub driver Socialite dengan user Google palsu.
     */
    private function fakeGoogleUser(string $id, string $email, bool $emailVerified = true): void
    {
        $googleUser = Mockery::mock(\Laravel\Socialite\Two\User::class);
        $googleUser->shouldReceive('getId')->andReturn($id);
        $googleUser->shouldReceive('getEmail')->andReturn($email);
        $googleUser->shouldReceive('getName')->andReturn('Pengguna Google');
        $googleUser->shouldReceive('getRaw')->andReturn(['email_verified' => $emailVerified]);

        Socialite::shouldReceive('driver->user')->andReturn($googleUser);
    }

    public function test_google_login_creates_user_with_verified_email(): void
    {
        $this->fakeGoogleUser('google-'.uniqid(), 'baru@gmail.com');

        $response = $this->get(route('social.callback'));

        $response->assertRedirect(RouteServiceProvider::HOME);

        $user = User::where('email', 'baru@gmail.com')->firstOrFail();

        $this->assertNotNull(
            $user->email_verified_at,
            'Login Google harus menandai email terverifikasi (email_verified_at tidak boleh null).'
        );
        $this->assertTrue($user->hasVerifiedEmail());
        $this->assertSame('umum', $user->role);
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_login_verifies_existing_unverified_account(): void
    {
        $existing = User::factory()->create([
            'email' => 'lama@gmail.com',
            'email_verified_at' => null,
        ]);

        $this->fakeGoogleUser('google-'.uniqid(), 'lama@gmail.com');

        $response = $this->get(route('social.callback'));

        $response->assertRedirect(RouteServiceProvider::HOME);

        $existing->refresh();

        $this->assertNotNull($existing->google_id);
        $this->assertTrue(
            $existing->hasVerifiedEmail(),
            'Akun lama yang ditautkan ke Google harus ikut terverifikasi.'
        );
    }

    public function test_google_login_rejects_unverified_google_email(): void
    {
        $this->fakeGoogleUser('google-'.uniqid(), 'belum@gmail.com', false);

        $response = $this->get(route('social.callback'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');

        $this->assertNull(User::where('email', 'belum@gmail.com')->first());
        $this->assertGuest();
    }

    public function test_verification_resend_sends_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertSessionHas('status', 'verification-link-sent');
        Notification::assertSentTo($user, VerifyEmailAddress::class);
    }

    public function test_verification_resend_does_not_return_500_when_mail_server_fails(): void
    {
        Mail::extend('broken', fn () => new class extends AbstractTransport
        {
            public function send(
                \Symfony\Component\Mime\RawMessage $message,
                ?\Symfony\Component\Mailer\Envelope $envelope = null
            ): ?\Symfony\Component\Mailer\SentMessage {
                throw new TransportException('SMTP gagal diakses.');
            }

            protected function doSend(\Symfony\Component\Mailer\SentMessage $message): void
            {
                throw new TransportException('SMTP gagal diakses.');
            }

            public function __toString(): string
            {
                return 'broken';
            }
        });

        config(['mail.default' => 'broken']);

        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->post(route('verification.send'));

        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
