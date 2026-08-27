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

    private function makeUser(array $attributes = []): User
    {
        return new class($attributes) extends User {
            public array $testAttributes;

            public function __construct(array $attributes = [])
            {
                parent::__construct();
                $this->testAttributes = $attributes;
            }

            public function __get($key)
            {
                if ($key === 'skills') {
                    return collect();
                }
                return $this->testAttributes[$key] ?? null;
            }

            public function __isset($key)
            {
                return isset($this->testAttributes[$key]);
            }

            public function skills()
            {
                $names = $this->testAttributes['skill_names'] ?? [];
                return new class($names) {
                    public function __construct(private array $names) {}
                    public function pluck($col)
                    {
                        return \Illuminate\Support\Collection::make($this->names);
                    }
                    public function map($cb)
                    {
                        return \Illuminate\Support\Collection::make($this->names)->map($cb);
                    }
                };
            }
        };
    }

    public function test_skill_heavy_match_returns_high_score()
    {
        $service = new JobMatchingService();

        $job = new Job();
        $job->title = 'Backend Developer';
        $job->description = 'We need PHP, Laravel, MySQL developer with 2 years experience.';
        $job->skills = 'php, laravel, mysql';
        $job->location = 'Jakarta';

        $user = $this->makeUser([
            'skill_names' => ['PHP', 'Laravel', 'HTML'],
            'education_history' => 'SMK Teknik Informatika',
            'experience_organization' => 'Magang 3 years di startup',
            'address' => 'Jakarta',
        ]);

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

        $user = $this->makeUser([
            'skill_names' => ['PHP', 'Laravel'],
            'education_history' => 'SMK Teknik Informatika',
            'experience_organization' => 'Belum ada pengalaman kerja',
            'address' => 'Jakarta',
        ]);

        $score = $service->score($job, $user);

        $this->assertIsInt($score);
        $this->assertLessThanOrEqual(40, $score, 'Expected low score for mismatched skills and location');
    }
}
