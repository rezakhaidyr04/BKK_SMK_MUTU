<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobAlert;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_rejects_empty_criteria(): void
    {
        $user = User::factory()->create(['role' => 'umum']);
        $resp = $this->actingAs($user)->post(route('job-alerts.store'), []);
        $resp->assertSessionHasErrors('keyword');
        $this->assertDatabaseCount('job_alerts', 0);
    }

    public function test_store_enforces_max_five(): void
    {
        $user = User::factory()->create(['role' => 'umum']);
        JobAlert::factory()->count(5)->create(['user_id' => $user->id]);
        $resp = $this->actingAs($user)->post(route('job-alerts.store'), ['keyword' => 'x']);
        $resp->assertSessionHas('error');
        $this->assertDatabaseCount('job_alerts', 5);
    }

    public function test_owner_can_toggle_and_destroy_but_not_others(): void
    {
        $owner = User::factory()->create(['role' => 'umum']);
        $other = User::factory()->create(['role' => 'umum']);
        $alert = JobAlert::factory()->create(['user_id' => $owner->id, 'is_active' => true]);

        $this->actingAs($other)->patch(route('job-alerts.toggle', $alert))->assertForbidden();
        $this->actingAs($other)->delete(route('job-alerts.destroy', $alert))->assertForbidden();

        $this->actingAs($owner)->patch(route('job-alerts.toggle', $alert))->assertRedirect();
        $this->assertFalse($alert->fresh()->is_active);
        $this->actingAs($owner)->delete(route('job-alerts.destroy', $alert))->assertRedirect();
        $this->assertDatabaseCount('job_alerts', 0);
    }
}
