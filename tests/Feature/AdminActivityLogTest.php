<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminActivityLogTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole($role);

        return $admin;
    }

    public function test_admin_write_action_is_logged(): void
    {
        $admin = $this->admin();
        $user = User::factory()->create(['email' => 'target@example.com']);

        $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
            'name' => 'Budi Santoso',
            'email' => 'target@example.com',
            'role' => 'umum',
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseCount('activities', 1);

        $activity = Activity::first();

        $this->assertEquals($admin->id, $activity->user_id);
        $this->assertEquals('update', $activity->type);
        $this->assertEquals('admin.users.update', $activity->route);
        $this->assertEquals(User::class, $activity->subject_type);
        $this->assertEquals($user->id, $activity->subject_id);
    }

    public function test_admin_read_action_is_not_logged(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)->get(route('admin.users.index'));

        $this->assertDatabaseCount('activities', 0);
    }

    public function test_activities_index_is_viewable_by_admin(): void
    {
        $admin = $this->admin();
        Activity::create([
            'user_id' => $admin->id,
            'type' => 'store',
            'description' => 'Admin melakukan store (admin.users.store)',
            'route' => 'admin.users.store',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.activities.index'));

        $response->assertStatus(200);
        $response->assertSee('Log Aktivitas');
        $response->assertSee('admin.users.store');
    }
}
