<?php

namespace App\Services;

use App\Models\User;

class ProfileCompletionService
{
    /**
     * P4.1: pindahan verbatim DashboardController::calculateProfileCompletion().
     * Field, bobot, dan rumus IDENTIK.
     */
    public function for(User $user): int
    {
        $completed = 0;
        $total = 0;

        // --- Bagian 1: Data dasar (masing-masing 1 poin) ---
        $basicFields = ["name", "email", "phone", "avatar", "bio"];
        foreach ($basicFields as $field) {
            $total++;
            if (!empty($user->$field)) {
                $completed++;
            }
        }

        // --- Bagian 2: Data profil pencari kerja (masing-masing 1 poin) ---
        $profileFields = [
            "address",
            "preferred_position",
            "education_history",
            "experience_organization",
            "linkedin_url",
            "portfolio_url",
        ];
        if ($user->role === "umum") {
            foreach ($profileFields as $field) {
                $total++;
                if (!empty($user->$field)) {
                    $completed++;
                }
            }
        }

        // --- Bagian 3: Keahlian (2 poin jika ada minimal 1 skill) ---
        $total += 2;
        if ($user->skills()->count() > 0) {
            $completed += 2;
        }

        // --- Bagian 4: CV (2 poin jika ada CV) ---
        $total += 2;
        if ($user->cvFiles()->exists()) {
            $completed += 2;
        }

        return $total > 0 ? round(($completed / $total) * 100) : 0;
    }
}
