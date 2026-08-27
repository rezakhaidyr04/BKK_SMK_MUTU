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
        $jobText = strtolower(($job->title ?? '') . ' ' . ($job->qualifications ?? '') . ' ' . ($job->description ?? '') . ' ' . ($job->requirements ?? ''));

        // 1) Skills dari relasi user_skills.
        $userSkillNames = $user->skills()->pluck('name')->map(fn($s) => $this->normalize($s))->filter()->unique()->values()->toArray();

        $jobSkillTokens = [];
        if (isset($job->skills) && !empty($job->skills)) {
            $jobSkillTokens = $this->tokens($job->skills);
        }
        if (empty($jobSkillTokens)) {
            $jobSkillTokens = $this->tokens($jobText);
        }

        $skillScore = $this->jaccardSimilarity($userSkillNames, $jobSkillTokens);
        $score += $skillScore * $weights['skills'];

        // 2) Education: bandingkan riwayat pendidikan dengan teks lowongan.
        $eduScore = 0;
        if (!empty($user->education_history)) {
            $educationTokens = array_slice($this->tokens($user->education_history), 0, 10);
            foreach ($educationTokens as $token) {
                if (strlen($token) > 3 && str_contains($jobText, $token)) {
                    $eduScore = 1;
                    break;
                }
            }
        }
        $score += $eduScore * $weights['education'];

        // 3) Experience: compare numeric years if present
        $expScore = 0;
        $jobYears = $this->extractYearsRequirement($jobText);
        $userYears = $this->extractYearsRequirement(strtolower($user->experience_organization ?? ''));
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
        $userLoc = $this->normalize($user->address ?? '');
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

        // 5) Industry: cocokkan industri perusahaan dengan posisi yang diinginkan user.
        $indScore = 0;
        $industry = '';
        if ($job->company_id && $job->company?->industry) {
            $industry = $job->company->industry;
        } elseif (!empty($job->company_name)) {
            $industry = (string) $job->company_name;
        }
        $preferred = $this->normalize($user->preferred_position ?? '');
        if ($industry && $preferred && (str_contains($this->normalize($industry), $preferred) || str_contains($preferred, $this->normalize($industry)))) {
            $indScore = 1;
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
