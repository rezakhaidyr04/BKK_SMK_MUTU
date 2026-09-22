<?php

namespace Tests\Unit;

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * P2.7: verifikasi helper Application (5 scope + 5 is*) merepresentasikan
 * behavior existing — tanpa mengubah status flow.
 */
class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_helpers_match_status(): void
    {
        $cases = [
            'submitted' => 'isSubmitted',
            'under_review' => 'isUnderReview',
            'interviewed' => 'isInterviewed',
            'accepted' => 'isAccepted',
            'rejected' => 'isRejected',
        ];

        foreach ($cases as $status => $method) {
            $application = Application::factory()->create(['status' => $status]);

            $this->assertTrue($application->$method(), "{$method} seharusnya true untuk {$status}");

            foreach ($cases as $otherStatus => $otherMethod) {
                if ($otherStatus === $status) {
                    continue;
                }

                $this->assertFalse($application->$otherMethod(), "{$otherMethod} seharusnya false untuk {$status}");
            }
        }
    }

    public function test_scopes_filter_by_status(): void
    {
        $submitted = Application::factory()->create(['status' => 'submitted']);
        $underReview = Application::factory()->create(['status' => 'under_review']);
        $interviewed = Application::factory()->create(['status' => 'interviewed']);
        $accepted = Application::factory()->create(['status' => 'accepted']);
        $rejected = Application::factory()->create(['status' => 'rejected']);

        $this->assertSame([$submitted->id], Application::submitted()->pluck('id')->all());
        $this->assertSame([$underReview->id], Application::underReview()->pluck('id')->all());
        $this->assertSame([$interviewed->id], Application::interviewed()->pluck('id')->all());
        $this->assertSame([$accepted->id], Application::accepted()->pluck('id')->all());
        $this->assertSame([$rejected->id], Application::rejected()->pluck('id')->all());
    }
}
