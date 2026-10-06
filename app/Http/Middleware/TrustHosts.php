<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        // M10: tunnel testing (ngrok/CF) HANYA di non-production.
        // Production: hanya domain aplikasi sendiri.
        if (app()->environment('production')) {
            return [$this->allSubdomainsOfApplicationUrl()];
        }

        return array_merge([$this->allSubdomainsOfApplicationUrl()], $this->tunnelHosts());
    }

    /**
     * M10: pola host tunnel untuk testing lokal (terpisah agar bisa dites).
     *
     * @return array<int, string>
     */
    public function tunnelHosts(): array
    {
        return [
            '.*\.trycloudflare\.com',
            '.*\.cfargotunnel\.com',
            '.*\.ngrok\.io',
            '.*\.ngrok-free\.app',
        ];
    }
}
