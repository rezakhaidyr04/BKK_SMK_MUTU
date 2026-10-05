<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Pengaman insiden 2026-10-01: test yang bocor ke DB dev pernah
     * menghapus seluruh data development. Gagalkan dengan keras bila
     * environment/database test tidak sesuai.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Config cache membekukan env/DB (insiden 2026-10-01: test bocor ke
        // DB dev dan menghapus data). Test wajib jalan tanpa config cache.
        $this->assertFalse(
            app()->configurationIsCached(),
            'SAFETY: config ter-cache — jalankan php artisan optimize:clear dulu!'
        );
        $this->assertSame(
            'testing',
            config('app.env'),
            'SAFETY: test berjalan di luar APP_ENV=testing — hentikan!'
        );
        $this->assertSame(
            'bkk_smk_mutu_testing',
            config('database.connections.mysql.database'),
            'SAFETY: test tidak memakai DB testing — hentikan!'
        );
    }
}
