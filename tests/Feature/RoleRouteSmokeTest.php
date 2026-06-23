<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Company;
use App\Models\Event;
use App\Models\Job;
use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleRouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_role_dashboards_render_or_redirect_safely(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $student = User::factory()->create(['role' => 'student']);
        $teacher = User::factory()->create(['role' => 'teacher']);
        $companyUser = User::factory()->create(['role' => 'company']);
        Company::factory()->create(['user_id' => $companyUser->id]);

        foreach ([$admin, $student, $teacher, $companyUser] as $user) {
            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertStatus(200);
        }
    }

    public function test_detail_pages_used_by_roles_render(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $student->id,
            'cover_letter' => str_repeat('Lamaran ', 20),
            'status' => 'submitted',
        ]);

        $this->actingAs($student)
            ->get(route('applications.show', $application))
            ->assertStatus(200);

        $this->actingAs($companyUser)
            ->get(route('applications.show', $application))
            ->assertStatus(200);
    }

    public function test_public_news_and_event_detail_pages_render(): void
    {
        $author = User::factory()->create(['role' => 'admin']);
        $news = News::create([
            'user_id' => $author->id,
            'title' => 'Tips Karir',
            'slug' => 'tips-karir',
            'category' => 'tips',
            'content' => 'Konten berita karir.',
            'is_published' => true,
        ]);
        $event = Event::create([
            'title' => 'Job Fair',
            'type' => 'job_fair',
            'description' => 'Acara rekrutmen.',
            'start_time' => now()->addDay(),
            'location' => 'Aula Sekolah',
        ]);

        $this->get(route('news.show', $news))->assertStatus(200);
        $this->get(route('events.show', $event))->assertStatus(200);
    }
}
