<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * H5: JANGAN hardcode '*' untuk production. Nilai diambil dari
     * config('app.trusted_proxies'): production = env TRUSTED_PROXIES
     * (kosong = tolak semua proxy, fail-closed), non-production = '*'
     * untuk ngrok/Cloudflare Tunnel saat testing.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    public function __construct()
    {
        $this->proxies = config('app.trusted_proxies', '*');
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
