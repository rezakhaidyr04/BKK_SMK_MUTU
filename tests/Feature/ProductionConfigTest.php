<?php

namespace Tests\Feature;

use App\Jobs\GenerateCvJob;
use App\Jobs\SendJobBroadcastChunk;
use App\Jobs\SendJobNotificationsChunk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * M10: guard konfigurasi production. Semua invarian yang bila dilanggar
 * menyebabkan deployment failure / timeout / security regression.
 */
class ProductionConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_m10_timezone_is_asia_jakarta(): void
    {
        $this->assertSame('Asia/Jakarta', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', date_default_timezone_get());
    }

    public function test_m10_smtp_timeout_is_bounded(): void
    {
        $timeout = config('mail.mailers.smtp.timeout');
        $this->assertNotNull($timeout, 'MAIL timeout tidak boleh null/unlimited.');
        $this->assertGreaterThanOrEqual(5, (int) $timeout);
        $this->assertLessThanOrEqual(30, (int) $timeout);
    }

    public function test_m10_session_cookie_hardened(): void
    {
        $this->assertTrue((bool) config('session.http_only'));
        $this->assertSame('lax', config('session.same_site'));
        // secure wajib bisa dinyalakan via env (production checklist).
        $this->assertArrayHasKey('secure', config('session'));
    }

    public function test_m10_queue_invariant_retry_after_exceeds_all_job_timeouts(): void
    {
        $retryAfter = (int) config('queue.connections.database.retry_after');
        foreach ([
            new SendJobBroadcastChunk(1, 1, 50),
            new SendJobNotificationsChunk(1, 1, 200),
            new GenerateCvJob(1, [], 'modern', 'x.pdf'),
        ] as $job) {
            $this->assertGreaterThan(
                $job->timeout, $retryAfter,
                get_class($job) . ": retry_after ($retryAfter) harus > timeout ({$job->timeout})."
            );
        }
    }

    public function test_m10_queue_and_session_tables_migrated(): void
    {
        $this->assertTrue(Schema::hasTable('queued_jobs'), 'Tabel queued_jobs wajib ada (QUEUE_CONNECTION=database).');
        $this->assertTrue(Schema::hasTable('failed_jobs'), 'Tabel failed_jobs wajib ada.');
        $this->assertTrue(Schema::hasTable('sessions'), 'Tabel sessions wajib ada (SESSION_DRIVER=database).');
    }

    public function test_m10_no_env_calls_in_runtime_code(): void
    {
        // Setelah config:cache, env() di luar config/* bernilai null.
        $offenders = [];
        foreach (['app', 'routes'] as $dir) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(base_path($dir)));
            foreach ($it as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }
                $src = file_get_contents($file->getPathname());
                if (preg_match('/\benv\s*\(/', $src)) {
                    $offenders[] = substr($file->getPathname(), strlen(base_path()) + 1);
                }
            }
        }
        $this->assertEmpty($offenders, 'env() di runtime code (akan null saat config:cache): ' . implode(', ', $offenders));
    }

    public function test_m10_trust_hosts_allows_tunnels_only_outside_production(): void
    {
        // Resolve via container (parent butuh Application di constructor).
        $middleware = app(\App\Http\Middleware\TrustHosts::class);
        $this->assertCount(4, $middleware->tunnelHosts());
        // Env testing = non-production → tunnel hosts tetap diizinkan (dev allowance).
        $this->assertNotEmpty(array_filter($middleware->hosts(), fn ($h) => str_contains((string) $h, 'ngrok')));
    }

    public function test_m10_production_env_template_is_complete(): void
    {
        $tpl = file_get_contents(base_path('.env.production.example'));
        foreach ([
            'APP_ENV=production', 'APP_DEBUG=false', 'APP_URL=https://',
            'SESSION_SECURE_COOKIE=true', 'QUEUE_CONNECTION=database',
            'TRUSTED_PROXIES=', 'SANCTUM_STATEFUL_DOMAINS=', 'MAIL_TIMEOUT=',
            'LOG_LEVEL=warning',
        ] as $needle) {
            $this->assertStringContainsString($needle, $tpl, ".env.production.example wajib memuat: $needle");
        }
        $this->assertStringNotContainsString('APP_DEBUG=true', $tpl);
    }

    public function test_m10_dev_env_template_documents_new_vars(): void
    {
        $tpl = file_get_contents(base_path('.env.example'));
        foreach (['TRUSTED_PROXIES=', 'SANCTUM_STATEFUL_DOMAINS=', 'MAIL_TIMEOUT='] as $needle) {
            $this->assertStringContainsString($needle, $tpl, ".env.example wajib memuat: $needle");
        }
    }
}
