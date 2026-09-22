<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * P1 H-07: Conversation mass assignment + factory regression.
 */
class ConversationGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_conversation_factory_create_passes(): void
    {
        $conversation = Conversation::create();

        $this->assertNotNull($conversation->id);
        $this->assertDatabaseHas('conversations', ['id' => $conversation->id]);
    }

    public function test_conversation_create_with_no_attributes_passes(): void
    {
        $conversation = new Conversation();
        $conversation->save();

        $this->assertNotNull($conversation->id);
    }

    public function test_message_factory_creates_conversation(): void
    {
        $message = Message::factory()->create();

        $this->assertNotNull($message->conversation_id);
        $this->assertDatabaseHas('conversations', ['id' => $message->conversation_id]);
        $this->assertDatabaseHas('messages', ['id' => $message->id]);
    }

    public function test_conversation_mass_assignment_protection(): void
    {
        // Allowlist kosong: id tidak boleh di-mass-assign → harus ditolak
        // dengan MassAssignmentException, bukan disimpan.
        $this->expectException(\Illuminate\Database\Eloquent\MassAssignmentException::class);

        Conversation::create(['id' => 999999]);
    }

    public function test_messaging_existing_flow_still_works(): void
    {
        $umum = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $companyUser = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);

        $response = $this->actingAs($umum)->post(route('messages.start'), [
            'recipient_id' => $companyUser->id,
        ]);

        $response->assertRedirect();
        $this->assertSame(1, Conversation::count());
    }
}
