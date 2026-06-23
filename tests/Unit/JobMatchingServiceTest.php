<?php

namespace Tests\Unit;

use App\Services\JobMatchingService;
use App\Models\Job;
use App\Models\User;
use PHPUnit\Framework\TestCase;
use Mockery;

class JobMatchingServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_skill_heavy_match_returns_high_score()
    {
        $service = new JobMatchingService();

        $job = new Job();
        $job->title = 'Backend Developer';
        $job->description = 'We need PHP, Laravel, MySQL developer with 2 years experience.';
        $job->skills = 'php, laravel, mysql';
        $job->location = 'Jakarta';
        $company = new \stdClass();
        $company->industry = 'Software';
        $job->company = $company;

        // Create a lightweight User subclass with skills() and student property
        $user = new class extends User {
            public $student;
            public function skills()
            {
                return new class {
                    public function pluck($col)
                    {
                        return \Illuminate\Support\Collection::make(['PHP', 'Laravel', 'HTML']);
                    }
                };
            }
        };
        $user->student = (object)[
            'major' => 'Teknik Informatika',
            'experience' => '3 years',
            'address' => 'Jakarta'
        ];

        $score = $service->score($job, $user);

        $this->assertIsInt($score);
        $this->assertGreaterThanOrEqual(50, $score, 'Expected fairly high score for strong skill overlap');
    }

    public function test_poor_match_returns_low_score()
    {
        $service = new JobMatchingService();

        $job = new Job();
        $job->title = 'Mechanical Technician';
        $job->description = 'Mechanical assembly, CNC, welding.';
        $job->skills = 'welding, cnc, mechanical';
        $job->location = 'Bandung';
        $company = new \stdClass();
        $company->industry = 'Manufacturing';
        $job->company = $company;

        $user = new class extends User {
            public $student;
            public function skills()
            {
                return new class {
                    public function pluck($col)
                    {
                        return \Illuminate\Support\Collection::make(['PHP', 'Laravel']);
                    }
                };
            }
        };
        $user->student = (object)[
            'major' => 'Teknik Informatika',
            'experience' => '0 years',
            'address' => 'Jakarta'
        ];

        $score = $service->score($job, $user);

        $this->assertIsInt($score);
        $this->assertLessThanOrEqual(40, $score, 'Expected low score for mismatched skills and industry');
    }
}
