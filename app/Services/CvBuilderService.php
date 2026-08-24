<?php

namespace App\Services;

use App\Jobs\GenerateCvJob;
use Illuminate\Support\Facades\Auth;

class CvBuilderService
{
    public function generateCv(array $validatedData)
    {
        $user = Auth::user();
        $user->load(['student', 'skills', 'cvFiles', 'certificates']);

        $data = [
            'user' => $user,
            'include_photo' => (bool) ($validatedData['include_photo'] ?? false),
            'include_skills' => (bool) ($validatedData['include_skills'] ?? true),
            'include_certificates' => (bool) ($validatedData['include_certificates'] ?? false),
            'custom_headline' => trim((string) ($validatedData['custom_headline'] ?? '')),
            'custom_summary' => trim((string) ($validatedData['custom_summary'] ?? '')),
            'custom_experience' => trim((string) ($validatedData['custom_experience'] ?? '')),
            'custom_achievement' => trim((string) ($validatedData['custom_achievement'] ?? '')),
            'target_position' => trim((string) ($validatedData['target_position'] ?? '')),
            'ats_keywords' => trim((string) ($validatedData['ats_keywords'] ?? '')),
        ];

        $template = 'modern';
        $fileName = 'cv-files/generated-cv-' . $user->id . '-' . time() . '.pdf';

        // Dispatch job to queue
        GenerateCvJob::dispatch($user->id, $data, $template, $fileName);
    }
}
