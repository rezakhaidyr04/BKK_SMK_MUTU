<?php

namespace Tests\Feature;

use App\Http\Controllers\CvBuilderController;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Event;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * L1–L8 low-priority hardening regression.
 */
class LowHardeningTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge(['role' => 'umum', 'email_verified_at' => now()], $over));
    }

    // ================= L1: mutasi notifikasi via POST =================

    public function test_l1_mark_read_requires_post(): void
    {
        $user = $this->verifiedUmum();
        $user->notify(new \App\Notifications\ApplicationStatusUpdated(
            \App\Models\Application::factory()->create(['user_id' => $user->id])
        ));

        // GET ditolak (405), POST bekerja + CSRF otomatis via actingAs form.
        $this->actingAs($user)->get(route('notifications.markAllRead'))->assertStatus(405);
        $this->actingAs($user)->post(route('notifications.markAllRead'))->assertRedirect();
        $this->assertEquals(0, $user->unreadNotifications()->count());
    }

    public function test_l1_go_requires_post_and_marks_read(): void
    {
        $user = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);
        $user->notify(new \App\Notifications\NewJobPosted($job));
        $notif = $user->notifications()->firstOrFail();

        $this->actingAs($user)->get(route('notifications.go', $notif->id))->assertStatus(405);
        $resp = $this->actingAs($user)->post(route('notifications.go', $notif->id));
        $resp->assertRedirect(route('jobs.show', $job));
        $this->assertNotNull($notif->fresh()->read_at);
    }

    // ================= L2: open redirect =================

    public function test_l2_go_rejects_external_and_tricky_urls(): void
    {
        $user = $this->verifiedUmum();
        $cases = ['//evil.com/x', 'https://evil.com/abc', '\\\\evil.com', 'javascript:alert(1)'];
        foreach ($cases as $i => $url) {
            $user->notifications()->create([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'type' => \App\Notifications\NewJobPosted::class,
                'data' => ['url' => $url, 'message' => "L2-$i"],
            ]);
        }
        foreach ($user->notifications as $notif) {
            $this->actingAs($user)->post(route('notifications.go', $notif->id))
                ->assertRedirect(route('notifications.index'));
        }
        // Path internal tetap diizinkan.
        $user->notifications()->create([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'type' => \App\Notifications\NewJobPosted::class,
            'data' => ['url' => '/jobs', 'message' => 'internal'],
        ]);
        $internal = $user->notifications()->where('data->message', 'internal')->firstOrFail();
        $this->actingAs($user)->post(route('notifications.go', $internal->id))
            ->assertRedirect('/jobs');
    }

    // ================= L3: judul sertifikat =================

    public function test_l3_cv_preview_lists_certificate_titles(): void
    {
        $user = $this->verifiedUmum();
        Certificate::factory()->create([
            'user_id' => $user->id,
            'title' => 'Sertifikat Las B4TXYZ',
            'file_path' => 'certificates/las.pdf',
        ]);

        $method = new \ReflectionMethod(CvBuilderController::class, 'buildPreviewData');
        $method->setAccessible(true);
        $data = $method->invoke(new CvBuilderController(), $user);

        $this->assertContains('Sertifikat Las B4TXYZ', $data['certificates']);
    }

    // ================= L4: double register event =================

    public function test_l4_double_event_register_no_500(): void
    {
        $user = $this->verifiedUmum();
        $event = Event::factory()->create(['start_time' => now()->addDays(5)]);

        $this->actingAs($user)->post(route('events.register', $event))->assertRedirect();
        $this->actingAs($user)->post(route('events.register', $event))->assertSessionHas('error');
        $this->assertEquals(1, \App\Models\EventRegistration::where('user_id', $user->id)->where('event_id', $event->id)->count());
    }

    // ================= L6: similarJobs tidak pernah expired =================

    public function test_l6_similar_jobs_never_expired(): void
    {
        $cu = User::factory()->create(['role' => 'company', 'email_verified_at' => now()]);
        $company = Company::factory()->create(['user_id' => $cu->id]);
        $live = Job::factory()->create([
            'company_id' => $company->id, 'status' => 'active', 'deadline' => now()->addWeek(),
            'title' => 'L6 Live', 'location' => 'Karawang',
        ]);
        Job::factory()->create([
            'company_id' => $company->id, 'status' => 'active', 'deadline' => today()->subDay()->toDateString(),
            'title' => 'L6 Expired Sama Lokasi', 'location' => 'Karawang',
        ]);

        $this->get(route('jobs.show', $live))->assertOk()->assertDontSee('L6 Expired Sama Lokasi');
    }

    // ================= L7: register tahan SMTP gagal =================

    public function test_l7_register_succeeds_when_mail_fails(): void
    {
        // Registrasi auto-verifikasi: tidak ada email yang dikirim saat daftar,
        // sehingga SMTP down tidak mungkin menggagalkan registrasi.
        $this->mock(
            \Illuminate\Contracts\Notifications\Dispatcher::class,
            function ($mock) {
                $mock->shouldReceive('send')->zeroOrMoreTimes()->andThrow(new \RuntimeException('smtp down'));
            }
        );

        $resp = $this->post(route('register'), [
            'name' => 'L7 User',
            'email' => 'l7user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $resp->assertRedirect(route('dashboard', absolute: false) ?? '/dashboard');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'l7user@example.com']);
        $this->assertTrue(\App\Models\User::where('email', 'l7user@example.com')->first()->hasVerifiedEmail());
    }

    // ================= L8: indeks read_at =================

    public function test_l8_notifications_read_index_exists(): void
    {
        $names = collect(Schema::getIndexes('notifications'))->pluck('name')->all();
        $this->assertContains('notifications_notifiable_read_index', $names);
    }
}
