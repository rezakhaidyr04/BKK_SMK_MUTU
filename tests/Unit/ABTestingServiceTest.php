<?php

namespace Tests\Unit;

use App\Services\ABTestingService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ABTestingServiceTest extends TestCase
{
    public function test_unknown_test_returns_control(): void
    {
        $svc = new ABTestingService();
        $this->assertSame('control', $svc->getVariation('tidak_ada_test_ini'));
    }

    public function test_empty_variations_returns_control_without_error(): void
    {
        Config::set('ab_testing.kosong', ['default' => 'control', 'variations' => []]);
        $svc = new ABTestingService();
        $this->assertSame('control', $svc->getVariation('kosong'));
    }

    public function test_missing_config_returns_safe_defaults(): void
    {
        Config::set('ab_testing.cta_copy', null);
        Config::set('ab_testing.hero_heading', null);
        $svc = new ABTestingService();
        $this->assertSame(['label' => 'CTA'], $svc->getCtaCopy('apapun'));
        $this->assertSame('', $svc->getHeroHeading());
    }

    public function test_zero_weights_do_not_throw(): void
    {
        Config::set('ab_testing.nol', [
            'default' => 'control',
            'variations' => [
                'control' => ['weight' => 0],
                'v2' => ['weight' => 0],
            ],
        ]);
        Session::forget('ab_test_nol');
        $svc = new ABTestingService();
        $this->assertContains($svc->getVariation('nol'), ['control', 'v2']);
    }
}
