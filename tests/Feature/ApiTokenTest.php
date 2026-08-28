<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ApiTokenTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole($role);

        return $admin;
    }

    public function test_admin_can_create_personal_access_token(): void
    {
        $admin = $this->admin();

        $response = $this->actingAs($admin)->post(route('admin.api-tokens.store'), [
            'token_name' => 'aplikasi-mobile',
        ]);

        $response->assertRedirect(route('admin.api-tokens.index'));
        $response->assertSessionHas('api_token');

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'aplikasi-mobile',
        ]);
    }

    public function test_token_can_authenticate_api_user_endpoint(): void
    {
        $admin = $this->admin();
        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/user');

        $response->assertStatus(200);
        $response->assertJson(['id' => $admin->id]);
    }

    public function test_public_jobs_api_returns_only_active_jobs(): void
    {
        Job::factory()->create(['status' => 'active', 'title' => 'Lowongan Aktif']);
        Job::factory()->create(['status' => 'draft', 'title' => 'Lowongan Draft']);

        $response = $this->getJson('/api/jobs');

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Lowongan Aktif']);
        $response->assertJsonMissing(['title' => 'Lowongan Draft']);
    }
}
