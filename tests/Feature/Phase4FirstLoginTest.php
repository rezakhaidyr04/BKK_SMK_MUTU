<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * PHASE 4 — First Login Tests
 *
 * Ketentuan:
 * - Jika must_change_password = true, user redirect ke password.force-change
 * - Mencegah akses ke rute auth lainnya sampai password diubah
 * - Mengubah password mengupdate must_change_password jadi false dan redirect ke dashboard
 */
class Phase4FirstLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_must_change_password_redirects_to_force_change_route(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        
        $response->assertRedirect(route('password.force-change'));
    }

    /** @test */
    public function user_can_view_force_change_password_page(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->get(route('password.force-change'));
        
        $response->assertOk();
        $response->assertSee('Password Sementara');
    }

    /** @test */
    public function user_without_must_change_password_redirects_away_from_force_change(): void
    {
        $user = User::factory()->create([
            'must_change_password' => false,
        ]);

        $response = $this->actingAs($user)->get(route('password.force-change'));
        
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function user_can_update_password_and_clear_flag(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('temp123!'),
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->post(route('password.force-update'), [
            'current_password' => 'temp123!',
            'password' => 'newpassword123!',
            'password_confirmation' => 'newpassword123!',
        ]);

        $response->assertRedirect('/dashboard');
        
        $user->refresh();
        $this->assertFalse((bool) $user->must_change_password);
        $this->assertNotNull($user->password_changed_at);
        $this->assertTrue(Hash::check('newpassword123!', $user->password));
    }

    /** @test */
    public function wrong_current_password_fails_force_update(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('temp123!'),
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->post(route('password.force-update'), [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123!',
            'password_confirmation' => 'newpassword123!',
        ]);

        $response->assertSessionHasErrors('current_password');
        
        $user->refresh();
        $this->assertTrue((bool) $user->must_change_password);
    }
}
