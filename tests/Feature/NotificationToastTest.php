<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationToastTest extends TestCase
{
    use RefreshDatabase;

    public function test_poll_returns_unread_json(): void
    {
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $user->notify(new \App\Notifications\ApplicationStatusUpdated(
            \App\Models\Application::factory()->create(['user_id' => $user->id])
        ));

        $resp = $this->actingAs($user)->getJson(route('notifications.poll'));
        $resp->assertOk()
            ->assertJsonPath('unread_count', 1)
            ->assertJsonStructure(['notifications' => [['id', 'message', 'time', 'url']]]);
    }

    public function test_dashboard_embeds_toast_with_message(): void
    {
        $user = User::factory()->create(['role' => 'umum', 'email_verified_at' => now()]);
        $user->notify(new \App\Notifications\ApplicationStatusUpdated(
            \App\Models\Application::factory()->create(['user_id' => $user->id])
        ));

        $resp = $this->actingAs($user)->get(route('dashboard'));
        $resp->assertOk();
        $resp->assertSee('notif-popup-driver', false);
        $resp->assertSee('notif-bell-btn', false);
        $resp->assertSee('notifications/poll', false);
        // dropdown + pemicu popup terpasang, pesan ikut ke-render
        $resp->assertSee('notif-popup-open', false);
        $resp->assertSee('origin-top-right', false);
        $resp->assertSee('Status lamaran Anda', false);
    }

    public function test_guest_dashboard_has_no_toast_js_errors(): void
    {
        // layout dipakai banyak halaman; pastikan partial aman untuk tamu
        $this->get(route('dashboard'))->assertRedirect();
        $html = view('layouts.notification-toasts')->render();
        $this->assertStringNotContainsString('notif-popup-driver', $html);
    }
}
