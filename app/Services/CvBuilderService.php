<?php

namespace App\Services;

use App\Jobs\GenerateCvJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CvBuilderService
{
    public const GENERATING_TTL = 600; // 10 menit: lebih lama dari timeout job (120 dtk) + antrean.

    public static function generatingKey(int $userId): string
    {
        return "cv:generating:{$userId}";
    }

    public static function failedKey(int $userId): string
    {
        return "cv:failed:{$userId}";
    }

    /**
     * M9: dispatch async — request TIDAK menunggu DomPDF.
     * @return 'queued'|'duplicate' (duplicate = masih diproses, jangan dispatch lagi)
     */
    public function generateCv(array $validatedData): string
    {
        $user = Auth::user();
        $user->load(['skills', 'cvFiles', 'certificates']);

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
        // M9: nama file deterministik dari isi — refresh/double-click dengan
        // input sama = nama sama = cek idempotency job menolak duplikat.
        // Signature mencakup SEMUA yang dirender template (opsi + relasi +
        // kolom profil user) agar ubah bio/pendidikan/pengalaman/HP tidak
        // menghasilkan PDF basi.
        $signature = md5($template . '|' . json_encode([
            $data['include_photo'], $data['include_skills'], $data['include_certificates'],
            $data['custom_headline'], $data['custom_summary'], $data['custom_experience'],
            $data['custom_achievement'], $data['target_position'], $data['ats_keywords'],
            $user->skills->pluck('name')->sort()->values()->all(),
            $user->certificates->pluck('title')->sort()->values()->all(),
            $user->name, $user->phone, $user->address, $user->bio,
            $user->preferred_position, $user->education_history,
            $user->experience_organization, $user->portfolio_url,
            $user->portfolio_type, $user->linkedin_url,
        ]));
        $fileName = 'cv-files/generated-cv-' . $user->id . '-' . $signature . '.pdf';

        // Atomik: klaim sekali jalan (double-click/paralel tidak dispatch ganda).
        if (! Cache::add(self::generatingKey($user->id), $fileName, self::GENERATING_TTL)) {
            return 'duplicate';
        }
        Cache::forget(self::failedKey($user->id));

        // Async: worker yang menjalankan DomPDF (bukan HTTP request).
        GenerateCvJob::dispatch($user->id, $data, $template, $fileName);

        return 'queued';
    }
}
