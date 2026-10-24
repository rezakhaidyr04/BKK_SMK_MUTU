<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_settings_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Admin BKKMU']);

        $response = $this->actingAs($admin)->get('/admin/settings');

        $response->assertOk();
        $response->assertSee('Pengaturan Admin');
        $response->assertSee('Keamanan Sandi');
        $response->assertSee('Profil &amp; Foto', false);
        $response->assertSee('Akses Cepat');
        $response->assertSee('Proteksi Akun');
        // Copy khusus pelamar tidak boleh muncul di halaman admin.
        $response->assertDontSee('Langkah 1');
        $response->assertDontSee('Profil Lengkap');
        $response->assertDontSee('Disamarkan untuk perusahaan');
    }

    public function test_non_admin_cannot_open_settings_page(): void
    {
        $user = User::factory()->create(['role' => 'umum']);

        $this->actingAs($user)->get('/admin/settings')->assertForbidden();
    }

    public function test_admin_profile_route_redirects_to_admin_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/profile')->assertRedirect('/admin/settings');
    }

    public function test_admin_can_update_profile_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->patch('/profile', [
            'name' => 'Admin Baru',
            'email' => 'admin.baru@example.com',
            'phone' => '08123456789',
        ]);

        $response->assertRedirect('/admin/settings');
        $response->assertSessionHasNoErrors();

        $admin->refresh();
        $this->assertSame('Admin Baru', $admin->name);
        $this->assertSame('admin.baru@example.com', $admin->email);
        $this->assertSame('08123456789', $admin->phone);
    }

    public function test_admin_role_cannot_be_changed_from_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->patch('/profile', [
            'name' => 'Admin BKKMU',
            'email' => $admin->email,
            'role' => 'umum',
        ])->assertSessionHasNoErrors();

        $this->assertSame('admin', $admin->refresh()->role);
    }
}
