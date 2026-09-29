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
        return [
            $this->allSubdomainsOfApplicationUrl(),
            // Testing dari laptop via Cloudflare Tunnel / LAN:
            '.*\.trycloudflare\.com',
            '.*\.cfargotunnel\.com',
            '.*\.ngrok\.io',
            '.*\.ngrok-free\.app',
        ];
    }
}
