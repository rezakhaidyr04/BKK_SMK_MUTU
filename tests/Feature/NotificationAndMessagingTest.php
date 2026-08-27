<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Company;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Event;
use App\Notifications\ApplicationReceived;
use App\Events\MessageSent;
use App\Repositories\ApplicationRepository;

class NotificationAndMessagingTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_received_notification_is_dispatched()
    {
        Notification::fake();

        $companyUser = User::factory()->create();
        $applicant = User::factory()->create();

        $company = Company::factory()->create(['user_id' => $companyUser->id]);
        $job = Job::factory()->create(['company_id' => $company->id]);

        (new ApplicationRepository())->createApplication([
            'job_id' => $job->id,
            'user_id' => $applicant->id,
            'cover_letter' => 'I apply',
        ]);

        Notification::assertSentTo($companyUser, ApplicationReceived::class);
    }

    public function test_message_send_broadcasts_event()
    {
        Event::fake();

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $conv = Conversation::create();
        $conv->users()->attach([$user1->id, $user2->id]);

        $response = $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->actingAs($user1)
            ->post(route('messages.send', ['conversation' => $conv->id]), [
                'body' => 'Hello'
            ]);

        $response->assertOk();
        $this->assertTrue($response->json('success'));

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conv->id,
            'sender_id' => $user1->id,
            'body' => 'Hello',
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) use ($conv) {
            return $event->message->conversation_id === $conv->id;
        });
    }

    public function test_umum_user_can_start_conversation_with_company()
    {
        $umum = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);

        $response = $this->actingAs($umum)
            ->post(route('messages.start'), [
                'recipient_id' => $companyUser->id,
            ]);

        $response->assertRedirect();

        $conversation = Conversation::whereHas('users', fn ($q) => $q->where('user_id', $umum->id))
            ->whereHas('users', fn ($q) => $q->where('user_id', $companyUser->id))
            ->first();

        $this->assertNotNull($conversation, 'Conversation should be created');

        // Memulai ulang dengan penerima yang sama tidak boleh membuat percakapan baru.
        $this->actingAs($umum)
            ->post(route('messages.start'), [
                'recipient_id' => $companyUser->id,
            ]);

        $this->assertSame(1, Conversation::count());
    }

    public function test_users_cannot_start_conversation_with_themselves()
    {
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);

        $response = $this->actingAs($user)
            ->from('/dashboard')
            ->post(route('messages.start'), [
                'recipient_id' => $user->id,
            ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors(['recipient_id']);
    }
}
