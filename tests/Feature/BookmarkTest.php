<?php

namespace Tests\Feature;

use App\Models\Bookmark;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookmarkTest extends TestCase
{
    use RefreshDatabase;

    public function test_bookmarks_page_handles_deleted_jobs_gracefully(): void
    {
        $user = User::factory()->create();
        $job = Job::factory()->create();

        Bookmark::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
        ]);

        $job->delete();

        $response = $this
            ->actingAs($user)
            ->get('/bookmarks');

        $response->assertOk();
        $response->assertSeeText('Lowongan yang sudah dihapus');
    }
}
