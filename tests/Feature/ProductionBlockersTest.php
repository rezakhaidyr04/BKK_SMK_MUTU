<?php

namespace Tests\Feature;

use App\Jobs\SendJobBroadcastChunk;
use App\Models\Application;
use App\Models\Company;
use App\Models\Job;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Regression H1–H7 FINAL PRODUCTION AUDIT.
 * Setiap test memetakan ke satu blocker dan business rule barunya.
 */
class ProductionBlockersTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUmum(array $over = []): User
    {
        return User::factory()->create(array_merge(['role' => 'umum', 'email_verified_at' => now()], $over));
    }

    private function verifiedCompany(array $over = []): User
    {
        $u = User::factory()->create(array_merge(['role' => 'company', 'email_verified_at' => now()], $over));
        Company::factory()->create(['user_id' => $u->id, 'is_verified' => true, 'verification_status' => 'verified']);
        return $u;
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
    }

    private function applyPayload(): array
    {
        return [
            'cover_letter' => str_repeat('Saya sangat tertarik dengan posisi ini dan memenuhi kualifikasi. ', 3),
            'cover_letter_file' => UploadedFile::fake()->create('surat-lamaran.pdf', 400, 'application/pdf'),
        ];
    }

    // ================= H1: definisi aktif tunggal =================

    public function test_h1_deadline_today_visible_and_appliable(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => today()->toDateString(), 'title' => 'H1 Hari Ini']);

        $this->get(route('jobs.index'))->assertOk()->assertSee('H1 Hari Ini');
        $this->get(route('jobs.show', $job))->assertOk();
        $this->getJson('/api/jobs')->assertOk()->assertJsonFragment(['title' => 'H1 Hari Ini']);
        $this->getJson('/api/jobs/' . $job->id)->assertOk();

        Storage::fake('private');
        $user = $this->verifiedUmum();
        $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload())->assertRedirect();
        $this->assertDatabaseHas('applications', ['job_id' => $job->id, 'user_id' => $user->id, 'deleted_at' => null]);
    }

    public function test_h1_deadline_yesterday_rejected_everywhere(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => today()->subDay()->toDateString(), 'title' => 'H1 Kemarin']);

        $this->get(route('jobs.index'))->assertOk()->assertDontSee('H1 Kemarin');
        $this->get(route('jobs.show', $job))->assertNotFound();
        $this->getJson('/api/jobs')->assertOk()->assertJsonMissing(['title' => 'H1 Kemarin']);
        $this->getJson('/api/jobs/' . $job->id)->assertNotFound();

        Storage::fake('private');
        $user = $this->verifiedUmum();
        $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload())->assertSessionHas('error');
        $this->assertDatabaseMissing('applications', ['job_id' => $job->id, 'user_id' => $user->id]);
    }

    public function test_h1_null_deadline_visible_and_appliable(): void
    {
        $job = Job::factory()->create(['status' => 'active', 'deadline' => null, 'title' => 'H1 Tanpa Deadline']);

        // Blade null-safe: tidak boleh 500.
        $this->get(route('jobs.index'))->assertOk()->assertSee('H1 Tanpa Deadline');
        $this->get(route('jobs.show', $job))->assertOk();
        $this->getJson('/api/jobs')->assertOk()->assertJsonFragment(['title' => 'H1 Tanpa Deadline']);

        Storage::fake('private');
        $user = $this->verifiedUmum();
        $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload())->assertRedirect();
        $this->assertDatabaseHas('applications', ['job_id' => $job->id, 'user_id' => $user->id]);
    }

    public function test_h1_counts_use_single_active_definition(): void
    {
        Job::factory()->create(['status' => 'active', 'deadline' => today()->toDateString()]);
        Job::factory()->create(['status' => 'active', 'deadline' => null]);
        Job::factory()->create(['status' => 'active', 'deadline' => today()->subDay()->toDateString()]);

        $expected = Job::active()->count();
        $this->assertEquals(2, $expected);

        $this->get(route('jobs.index'))->assertOk();
    }

    public function test_h1_past_deadline_rejected_on_create(): void
    {
        $companyUser = $this->verifiedCompany();
        $payload = [
            'title' => 'H1 Lampau', 'position' => 'Staff', 'location' => 'Karawang',
            'job_type' => 'full_time', 'description' => 'Desc', 'qualifications' => 'Kualifikasi',
            'deadline' => today()->subDay()->toDateString(),
        ];
        $this->actingAs($companyUser)->post(route('company.jobs.store', $payload))->assertSessionHasErrors(['deadline']);
    }

    // ================= H2: withdraw → re-apply =================

    public function test_h2_withdraw_then_reapply_restores_single_row(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload())->assertRedirect();
        $app = Application::where('job_id', $job->id)->where('user_id', $user->id)->firstOrFail();
        $originalCreated = $app->created_at->toDateTimeString();

        // Withdraw: soft-delete, file dipertahankan.
        $this->actingAs($user)->delete(route('applications.destroy', $app))->assertRedirect();
        $this->assertSoftDeleted('applications', ['id' => $app->id]);
        Storage::disk('private')->assertExists($app->cover_letter_path);

        // Re-apply: sukses tanpa 500, tetap 1 baris (restore), created_at utuh.
        $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload())->assertRedirect();
        $this->assertEquals(1, Application::withTrashed()->where('job_id', $job->id)->where('user_id', $user->id)->count());
        $restored = Application::where('job_id', $job->id)->where('user_id', $user->id)->firstOrFail();
        $this->assertEquals('submitted', $restored->status);
        $this->assertEquals($originalCreated, $restored->created_at->toDateTimeString());
        Storage::disk('private')->assertExists($restored->cover_letter_path);
    }

    // ================= H7: duplicate race → ramah =================

    public function test_h7_duplicate_apply_returns_error_not_500(): void
    {
        Storage::fake('private');
        $user = $this->verifiedUmum();
        $job = Job::factory()->create(['status' => 'active', 'deadline' => now()->addWeek()]);

        $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload())->assertRedirect();
        $resp = $this->actingAs($user)->post(route('jobs.apply', $job), $this->applyPayload());
        $resp->assertSessionHas('error');
        $this->assertEquals(1, Application::where('job_id', $job->id)->where('user_id', $user->id)->count());
    }

    // ================= H3: state machine =================

    public function test_h3_full_valid_chain_passes(): void
    {
        [$companyUser, $job, $app] = $this->applicationIn('submitted');
        $patch = fn ($status, $extra = []) => $this->actingAs($companyUser)->patch(
            route('company.applications.update', $app), array_merge(['status' => $status], $extra)
        )->assertRedirect();

        $patch('under_review');
        $patch('interviewed', $this->interviewFields());
        $patch('accepted');
        $this->assertEquals('accepted', $app->fresh()->status);
    }

    public function test_h3_invalid_transitions_rejected(): void
    {
        [$companyUser, $job, $app] = $this->applicationIn('submitted');

        // submitted → accepted langsung: DILARANG.
        $this->actingAs($companyUser)->patch(route('company.applications.update', $app), ['status' => 'accepted'])
            ->assertSessionHas('error');
        $this->assertEquals('submitted', $app->fresh()->status);

        // rejected → accepted: DILARANG.
        $rejected = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'rejected']);
        $this->actingAs($companyUser)->patch(route('company.applications.update', $rejected), ['status' => 'accepted'])
            ->assertSessionHas('error');
        $this->assertEquals('rejected', $rejected->fresh()->status);

        // accepted → submitted: DILARANG (final immutable).
        $accepted = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => 'accepted']);
        $this->actingAs($companyUser)->patch(route('company.applications.update', $accepted), ['status' => 'submitted'])
            ->assertSessionHas('error');
        $this->assertEquals('accepted', $accepted->fresh()->status);

        // rejected → interviewed: DILARANG.
        $this->actingAs($companyUser)->patch(route('company.applications.update', $rejected), ['status' => 'interviewed'] + $this->interviewFields())
            ->assertSessionHas('error');
        $this->assertEquals('rejected', $rejected->fresh()->status);
    }

    public function test_h3_same_status_update_allowed_idempotent(): void
    {
        [$companyUser, $job, $app] = $this->applicationIn('interviewed');
        $this->actingAs($companyUser)->patch(
            route('company.applications.update', $app),
            ['status' => 'interviewed'] + $this->interviewFields()
        )->assertRedirect();
        $this->assertEquals('interviewed', $app->fresh()->status);
    }

    /**
     * @return array{User, Job, Application}
     */
    private function applicationIn(string $status): array
    {
        $companyUser = $this->verifiedCompany();
        $job = Job::factory()->create(['company_id' => $companyUser->company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);
        $app = Application::factory()->create(['job_id' => $job->id, 'user_id' => $this->verifiedUmum()->id, 'status' => $status]);
        return [$companyUser, $job, $app];
    }

    private function interviewFields(): array
    {
        return [
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'interview_time' => '10:00',
            'interview_type' => 'offline',
            'interview_location' => 'Ruang Interview',
        ];
    }

    // ================= H4: tidak ada file sensitif di public =================

    public function test_h4_no_sensitive_files_on_public_disk(): void
    {
        $root = storage_path('app/public');
        $badDirs = ['certificates', 'event-payments', 'applications', 'user-documents', 'cover_letters', 'skck', 'company_mou', 'company_verifications', 'cv-files'];
        foreach ($badDirs as $d) {
            $this->assertDirectoryDoesNotExist($root . DIRECTORY_SEPARATOR . $d, "Direktori sensitif public/$d tidak boleh ada.");
        }
        $pdfs = [];
        if (is_dir($root)) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
            foreach ($it as $file) {
                if (strtolower($file->getExtension()) === 'pdf') {
                    $pdfs[] = $file->getPathname();
                }
            }
        }
        $this->assertEmpty($pdfs, 'PDF sensitif tidak boleh ada di disk public: ' . implode(', ', $pdfs));
    }

    // ================= H5: trust proxies =================

    public function test_h5_trust_proxies_from_config_not_hardcoded_star(): void
    {
        // Non-production (testing) boleh '*', production wajib dari env.
        $this->assertSame('*', config('app.trusted_proxies'));

        $middleware = new \App\Http\Middleware\TrustProxies();
        $prop = new \ReflectionProperty($middleware, 'proxies');
        $prop->setAccessible(true);
        $this->assertSame('*', $prop->getValue($middleware));

        // Wiring: bila config diisi (simulasi production), middleware ikut.
        config(['app.trusted_proxies' => ['203.0.113.10']]);
        $middleware2 = new \App\Http\Middleware\TrustProxies();
        $this->assertSame(['203.0.113.10'], $prop->getValue($middleware2));
    }

    // ================= H6: broadcast antrean =================

    public function test_h6_queue_retry_after_exceeds_broadcast_timeout(): void
    {
        $retryAfter = (int) config('queue.connections.database.retry_after');
        $job = new SendJobBroadcastChunk(1, 1, 50);
        $this->assertGreaterThan(
            $job->timeout, $retryAfter,
            "retry_after ($retryAfter) HARUS > timeout job ({$job->timeout}) agar tidak ada eksekusi ganda."
        );
    }

    public function test_h6_broadcast_dispatch_is_idempotent(): void
    {
        Queue::fake();
        $admin = $this->admin();
        $companyUser = $this->verifiedCompany();
        $job = Job::factory()->create(['company_id' => $companyUser->company->id, 'status' => 'active', 'deadline' => now()->addWeek()]);
        $this->verifiedUmum();
        $this->verifiedUmum();

        // Dispatch pertama: tepat 1 chunk untuk 2 user (perJob 50).
        $this->actingAs($admin)->post(route('admin.jobs.broadcast', $job))->assertRedirect();
        Queue::assertPushed(SendJobBroadcastChunk::class, 1);

        // Simulasikan 1 batch masih antre (payload persis format production:
        // JSON + serialized command) → POST kedua tidak dispatch lagi.
        Queue::fake();
        DB::table('queued_jobs')->insert([
            'queue' => 'default',
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => now()->getTimestamp(),
            'created_at' => now()->getTimestamp(),
            'payload' => json_encode([
                'displayName' => SendJobBroadcastChunk::class,
                'job' => 'Illuminate\\Queue\\CallQueuedHandler@call',
                'data' => ['command' => serialize(new SendJobBroadcastChunk($job->id, 1, 50))],
            ]),
        ]);
        $this->actingAs($admin)->post(route('admin.jobs.broadcast', $job))->assertRedirect();
        Queue::assertNothingPushed();
    }
}
