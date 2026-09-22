<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ABTestingService
{
    protected string $strategy;
    protected bool $enabled;

    public function __construct()
    {
        $this->enabled = Config::get('ab_testing.enabled', true);
        $this->strategy = Config::get('ab_testing.assignment_strategy', 'session');
    }

    /**
     * Get variation untuk test tertentu
     */
    public function getVariation(string $testName): string
    {
        if (!$this->enabled) {
            return Config::get("ab_testing.{$testName}.default", 'control');
        }

        $config = Config::get("ab_testing.{$testName}");
        if (!$config) {
            return 'control';
        }

        $sessionKey = "ab_test_{$testName}";
        $existing = Session::get($sessionKey);

        if ($existing && isset($config['variations'][$existing])) {
            return $existing;
        }

        $variation = $this->weightedRandom($config['variations']);
        Session::put($sessionKey, $variation);

        return $variation;
    }

    /**
     * Get CTA copy berdasarkan variation
     */
    public function getCtaCopy(string $ctaName, ?string $variation = null): array
    {
        $variation = $variation ?? $this->getVariation($ctaName);
        $config = Config::get("ab_testing.cta_copy.{$ctaName}");

        if (!$config || !isset($config['variations'][$variation])) {
            return $config['variations'][$config['default']] ?? ['label' => 'CTA'];
        }

        return $config['variations'][$variation];
    }

    /**
     * Get hero heading berdasarkan variation
     */
    public function getHeroHeading(?string $variation = null): string
    {
        $variation = $variation ?? $this->getVariation('hero_heading');
        $config = Config::get('ab_testing.hero_heading');

        if (!$config || !isset($config['variations'][$variation])) {
            return $config['variations'][$config['default']]['text'] ?? '';
        }

        return $config['variations'][$variation]['text'];
    }

    /**
     * Track event untuk analisis — P1-M03: sanitasi log
     */
    public function trackEvent(string $eventName, array $data = []): void
    {
        $config = Config::get("ab_testing.events.{$eventName}");
        $event = $config ?? "ab_test.{$eventName}";
        // Sanitasi: eventName hanya alphanumeric + _ . -
        $eventName = (string) Str::of($eventName)->limit(100);
        $event = (string) Str::of($event)->limit(100);

        // Sanitasi data tambahan: hanya allow string/array scalar, limit panjang, strip token
        $sanitizedData = [];
        foreach ($data as $k => $v) {
            $key = (string) Str::of((string) $k)->limit(50);
            if (is_string($v)) {
                // Hapus query param sensitif dari url/variant
                $val = Str::limit($v, 200, '');
                // Jika key url, buang query string (cegah token=password-reset?token=)
                if ($key === 'url' && str_contains($val, '?')) {
                    $val = strtok($val, '?');
                }
                // Hapus karakter kontrol/log injection
                $val = str_replace(["\n", "\r", "\0"], '', $val);
                $sanitizedData[$key] = $val;
            } elseif (is_array($v)) {
                // variations: hanya allow key dalam allowlist, value string terbatas
                $allowed = ['hero_primary', 'hero_secondary', 'cta_banner', 'hero_heading', 'variant', 'variations'];
                if (! in_array($key, $allowed, true)) {
                    continue;
                }
                $sanitizedData[$key] = $this->sanitizeVariations($v);
            } elseif (is_scalar($v)) {
                $sanitizedData[$key] = $v;
            }
        }

        $payload = array_merge([
            'event' => $event,
            'timestamp' => now()->toISOString(),
            // P1-M03: session_id tidak perlu full, hash saja untuk korelasi tanpa PII
            'session_hash' => hash('sha256', Session::getId()),
            'user_id' => auth()->id(),
            'variations' => $this->getActiveVariations(),
        ], $sanitizedData);

        // Log ke file/channel terpisah untuk analisis
        \Log::channel('ab_testing')->info($event, $payload);

        // Bisa dikirim ke analytics service (Mixpanel, GA, dll)
        // event(new ABTestEvent($payload));
    }

    /**
     * Get semua active variations untuk session ini
     */
    public function getActiveVariations(): array
    {
        $tests = ['hero_primary', 'hero_secondary', 'cta_banner', 'hero_heading'];
        $variations = [];

        foreach ($tests as $test) {
            $key = "ab_test_{$test}";
            if (Session::has($key)) {
                $variations[$test] = Session::get($key);
            }
        }

        return $variations;
    }

    /**
     * Reset variations (untuk testing)
     */
    public function resetVariations(): void
    {
        $tests = ['hero_primary', 'hero_secondary', 'cta_banner', 'hero_heading'];
        foreach ($tests as $test) {
            Session::forget("ab_test_{$test}");
        }
    }

    /**
     * Weighted random selection
     */
    protected function weightedRandom(array $variations): string
    {
        $totalWeight = array_sum(array_column($variations, 'weight'));
        $random = mt_rand(1, $totalWeight);
        $current = 0;

        foreach ($variations as $key => $config) {
            $current += $config['weight'];
            if ($random <= $current) {
                return $key;
            }
        }

        return array_key_first($variations);
    }

    /**
     * Force variation untuk testing manual
     */
    public function forceVariation(string $testName, string $variation): void
    {
        $config = Config::get("ab_testing.{$testName}");
        if ($config && isset($config['variations'][$variation])) {
            Session::put("ab_test_{$testName}", $variation);
        }
    }

    /**
     * Check apakah user dalam variant tertentu
     */
    public function isInVariant(string $testName, string $variation): bool
    {
        return $this->getVariation($testName) === $variation;
    }

    private function sanitizeVariations(mixed $variations): array
    {
        if (! is_array($variations)) {
            return [];
        }
        $out = [];
        foreach ($variations as $k => $v) {
            $k = (string) Str::of((string) $k)->limit(50);
            if (! preg_match('/^[a-z0-9_\-]+$/i', $k)) {
                continue;
            }
            if (is_string($v)) {
                $out[$k] = Str::limit(str_replace(["\n", "\r", "\0"], '', $v), 50, '');
            } elseif (is_scalar($v)) {
                $out[$k] = $v;
            }
        }
        return array_slice($out, 0, 10, true);
    }
}