<?php

namespace App\Services;

use App\Models\Job;
use App\Models\User;

class JobMatchingService
{
    /**
     * Hitung persentase kecocokan antara user dan job (0-100)
     * Lebih robust: normalisasi, tokenisasi, Jaccard untuk skills.
     */
    public function score(Job $job, User $user): int
    {
        $weights = [
            'skills' => 0.6,      // lebih menekankan skills
            'education' => 0.12,
            'experience' => 0.12,
            'location' => 0.08,
            'industry' => 0.08,
        ];

        $score = 0.0;

        // Prepare text sources
        $jobText = strtolower(($job->title ?? '') . ' ' . ($job->qualifications ?? '') . ' ' . ($job->description ?? '') . ' ' . ($job->requirements ?? '') . ' ' . ($job->skills ?? ''));

        // 1) Skills: try to use relation if exists, otherwise extract tokens from jobText
        $userSkillNames = [];
        if (method_exists($user, 'skills')) {
            $userSkillNames = $user->skills()->pluck('name')->map(fn($s) => $this->normalize($s))->filter()->unique()->values()->toArray();
        }
        // Fallback: try student->skills (comma separated)
        if (empty($userSkillNames) && !empty($user->student->skills ?? '')) {
            $userSkillNames = $this->tokens($user->student->skills);
        }

        $jobSkillTokens = [];
        if (isset($job->skills) && !empty($job->skills)) {
            $jobSkillTokens = $this->tokens($job->skills);
        }
        if (empty($jobSkillTokens)) {
            $jobSkillTokens = $this->tokens($jobText);
        }

        $skillScore = $this->jaccardSimilarity($userSkillNames, $jobSkillTokens);
        $score += $skillScore * $weights['skills'];

        // 2) Education: compare major/degree keywords
        $eduScore = 0;
        if (!empty($user->student->major ?? '') || !empty($user->student->degree ?? '')) {
            $major = $this->normalize($user->student->major ?? $user->student->degree ?? '');
            if ($major && str_contains($jobText, $major)) {
                $eduScore = 1;
            }
        }
        $score += $eduScore * $weights['education'];

        // 3) Experience: compare numeric years if present
        $expScore = 0;
        $jobYears = $this->extractYearsRequirement($jobText);
        $userYears = $this->extractYearsRequirement(strtolower($user->student->experience ?? ''));
        if ($jobYears > 0) {
            if ($userYears >= $jobYears) {
                $expScore = 1;
            } elseif ($userYears > 0) {
                $expScore = max(0, $userYears / $jobYears);
            }
        } else {
            // jika job tidak menulis "x years", berikan partial credit jika user punya pengalaman
            $expScore = $userYears > 0 ? 0.8 : 0;
        }
        $score += $expScore * $weights['experience'];

        // 4) Location
        $locScore = 0;
        $userLoc = $this->normalize($user->student->address ?? '');
        $jobLoc = $this->normalize($job->location ?? '');
        if ($userLoc && $jobLoc) {
            if ($userLoc === $jobLoc || str_contains($userLoc, $jobLoc) || str_contains($jobLoc, $userLoc)) {
                $locScore = 1;
            } else {
                // partial if same city or province tokens overlap
                $userTokens = $this->tokens($userLoc);
                $jobTokens = $this->tokens($jobLoc);
                $locScore = $this->jaccardSimilarity($userTokens, $jobTokens);
            }
        }
        $score += $locScore * $weights['location'];

        // 5) Industry
        $indScore = 0;
        $companyIndustry = $this->normalize($job->company->industry ?? '');
        if ($companyIndustry) {
            $major = $this->normalize($user->student->major ?? '');
            if ($major && (str_contains($companyIndustry, $major) || str_contains($major, $companyIndustry))) {
                $indScore = 1;
            } else {
                $indScore = $this->jaccardSimilarity($this->tokens($companyIndustry), $this->tokens($major));
            }
        }
        $score += $indScore * $weights['industry'];

        return (int) round(min(1, $score) * 100);
    }

    private function normalize(string $text): string
    {
        $t = strtolower($text);
        $t = preg_replace('/[^a-z0-9\s]/', ' ', $t);
        $t = preg_replace('/\s+/', ' ', $t);
        return trim($t);
    }

    private function tokens(string $text): array
    {
        $t = $this->normalize($text);
        if ($t === '') {
            return [];
        }
        $parts = preg_split('/[\s,;]+/', $t);
        $parts = array_map('trim', $parts);
        $parts = array_filter($parts);
        $parts = array_unique($parts);
        return array_values($parts);
    }

    private function jaccardSimilarity(array $a, array $b): float
    {
        if (empty($a) && empty($b)) return 0.0;
        if (empty($a) || empty($b)) return 0.0;
        $ia = array_intersect($a, $b);
        $union = array_unique(array_merge($a, $b));
        return count($union) > 0 ? count($ia) / count($union) : 0.0;
    }

    private function extractYearsRequirement(string $text): int
    {
        if (preg_match('/(\d+)\s*\+?\s*(years|yrs)/', $text, $m)) {
            return (int)$m[1];
        }
        return 0;
    }
}

