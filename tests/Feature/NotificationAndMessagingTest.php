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

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conv->id,
            'sender_id' => $user1->id,
            'body' => 'Hello',
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) use ($conv) {
            return $event->message->conversation_id === $conv->id;
        });
    }
}
