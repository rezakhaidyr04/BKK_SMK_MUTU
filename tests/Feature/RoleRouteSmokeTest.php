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
        $umum = User::factory()->create(['role' => 'umum']);
        $companyUser = User::factory()->create(['role' => 'company']);
        Company::factory()->create(['user_id' => $companyUser->id]);

        foreach ([$admin, $umum, $companyUser] as $user) {
            $this->actingAs($user)
                ->get(route('dashboard'))
                ->assertStatus(200);
        }
    }

    public function test_detail_pages_used_by_roles_render(): void
    {
        $umum = User::factory()->create(['role' => 'umum']);
        $companyUser = User::factory()->create(['role' => 'company']);
        $company = Company::factory()->create([
            'user_id' => $companyUser->id,
            'is_verified' => true,
            'verification_status' => 'verified',
        ]);
        $job = Job::factory()->create(['company_id' => $company->id]);
        $application = Application::create([
            'job_id' => $job->id,
            'user_id' => $umum->id,
            'cover_letter' => str_repeat('Lamaran ', 20),
            'status' => 'submitted',
        ]);

        $this->actingAs($umum)
            ->get(route('applications.show', $application))
            ->assertStatus(200);

        $this->actingAs($companyUser)
            ->get(route('applications.show', $application))
            ->assertStatus(200);
    }

    public function test_public_news_and_event_detail_pages_render(): void
    {
        $news = News::create([
            'title' => 'Tips Karir',
            'slug' => 'tips-karir',
            'category' => 'career_tips',
            'content' => 'Konten berita karir.',
            'is_published' => true,
            'published_at' => now(),
        ]);
        $event = Event::create([
            'title' => 'Job Fair',
            'type' => 'job_fair',
            'description' => 'Acara rekrutmen.',
            'start_time' => now()->addDay(),
            'location' => 'Aula Sekolah',
        ]);

        // Pelamar/tamu tanpa auth harus bisa baca berita & acara publik.
        $this->get(route('news.index'))->assertStatus(200);
        $this->get(route('news.show', $news))->assertStatus(200);
        $this->get(route('events.show', $event))->assertStatus(200);
    }

    public function test_public_news_draft_and_scheduled_are_hidden_from_guests(): void
    {
        $draft = News::create([
            'title' => 'Draf',
            'category' => 'career_tips',
            'content' => 'Belum terbit.',
            'is_published' => false,
        ]);
        $scheduled = News::create([
            'title' => 'Terjadwal',
            'category' => 'work_world',
            'content' => 'Nanti terbit.',
            'is_published' => true,
            'published_at' => now()->addDay(),
        ]);

        $this->get(route('news.show', $draft))->assertStatus(404);
        $this->get(route('news.show', $scheduled))->assertStatus(404);
        $this->get(route('news.index'))->assertStatus(200)->assertDontSee('Belum terbit.');
    }
}
