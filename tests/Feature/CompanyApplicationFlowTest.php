<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyApplicationFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function company_can_accept_and_reject_application()
    {
        $this->markTestIncomplete('Add factories and implement test data for company, job, application, and user.');
    }
}
