<?php

namespace Tests\Feature;

use App\Jobs\GenerateCvJob;
use App\Services\CvBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CvGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_cv_generate_endpoint_dispatches_job(): void
    {
        Queue::fake();

        $user = \App\Models\User::factory()->create(['role' => 'umum']);
        $user->skills()->create(['name' => 'PHP']);

        $response = $this->actingAs($user)
            ->post(route('cv.generate'), [
                'include_skills' => true,
                'include_certificates' => false,
            ]);

        $response->assertRedirect();
        Queue::assertPushed(GenerateCvJob::class);
    }

    public function test_cv_builder_service_dispatches_job_with_user_payload(): void
    {
        Queue::fake();

        $user = \App\Models\User::factory()->create(['role' => 'umum']);
        $this->actingAs($user);

        (new CvBuilderService())->generateCv([
            'include_skills' => true,
            'include_certificates' => true,
        ]);

        Queue::assertPushed(GenerateCvJob::class, function (GenerateCvJob $job) use ($user) {
            return $job->userId === $user->id
                && ($job->data['include_skills'] ?? null) === true
                && ($job->data['include_certificates'] ?? null) === true;
        });
    }
}
