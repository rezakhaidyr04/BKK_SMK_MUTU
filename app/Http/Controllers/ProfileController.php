<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\ImageProcessor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    private array $profileFields = [
        "address",
        "preferred_position",
        "education_history",
        "experience_organization",
        "birth_place",
        "birth_date",
        "gender",
        "linkedin_url",
        "portfolio_url",
        "portfolio_type",
    ];

    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->isCompany()) {
            return Redirect::route('company.profile.edit');
        }

        return view("profile.edit", [
            "user" => $user->load("skills", "cvFiles", "documents"),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isCompany()) {
            return Redirect::route('company.profile.edit')->with('error', 'Silakan kelola profil perusahaan melalui halaman profil perusahaan.');
        }

        $validated = $request->validated();

        // Normalisasi: ubah literal "\n" (backslash + n) jadi newline asli.
        // Terjadi kalau data lama / input API menyimpan "\n" sebagai teks.
        foreach (["bio", "address", "education_history", "experience_organization"] as $multilineField) {
            if (! empty($validated[$multilineField]) && is_string($validated[$multilineField])) {
                $validated[$multilineField] = str_replace(['\\r\\n', '\\n', '\\r'], "\n", $validated[$multilineField]);
            }
        }

        // Handle avatar upload
        if ($request->hasFile("avatar")) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk("public")->delete($user->avatar);
            }

            $processor = new ImageProcessor(quality: 82, maxWidth: 320, maxHeight: 320);
            $avatarName = 'avatar-' . $user->id . '-' . time();
            $path = $processor->store($request->file("avatar"), 'profile-photos', $avatarName);

            if ($path) {
                $validated["avatar"] = $path;
            } else {
                unset($validated["avatar"]);
            }
        } else {
            unset($validated["avatar"]); // Jangan overwrite jika tidak ada upload
        }

        $user->fill([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "phone" => $validated["phone"] ?? null,
            "bio" => $validated["bio"] ?? null,
            "avatar" => $validated["avatar"] ?? $user->avatar,
        ]);

        // Data profil pencari kerja tersimpan langsung di tabel users.
        foreach ($this->profileFields as $field) {
            $user->$field = $validated[$field] ?? null;
        }

        if ($user->isDirty("email")) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Sync skills — P0 H-03: validated berupa array max 20, tiap item max 50.
        // Defense-in-depth: potong berlebih + abaikan non-string/kosong.
        $submittedSkills = $request->input("skills", []);
        if (! is_array($submittedSkills)) {
            $submittedSkills = [];
        }
        $submittedSkills = array_slice($submittedSkills, 0, 20);
        $skillIds = [];
        foreach ($submittedSkills as $skillName) {
            if (! is_string($skillName)) {
                continue;
            }
            $skillName = trim($skillName);
            if ($skillName === '' || strlen($skillName) > 50) {
                continue;
            }
            $skill = \App\Models\Skill::firstOrCreate([
                "name" => $skillName,
            ]);
            $skillIds[$skill->id] = ["proficiency" => 3];
        }
        $user->skills()->sync($skillIds);

        return Redirect::route("profile.edit")->with(
            "status",
            "profile-updated",
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag("userDeletion", [
            "password" => ["required", "current_password"],
        ]);

        $user = $request->user();

        Auth::logout();

        if ($user->avatar) {
            Storage::disk("public")->delete($user->avatar);
        }

        // P5.7: hapus file private milik akun (sertifikat, CV, dokumen,
        // lampiran lamaran) agar tidak yatim di disk setelah forceDelete
        // me-cascade baris DB-nya. Pola sama seperti destroy per-resource.
        $privatePaths = $user->certificates()->pluck('file_path')
            ->merge($user->cvFiles()->pluck('file_path'))
            ->merge($user->documents()->pluck('file_path'))
            ->merge(
                $user->applications()->whereNotNull('attachment_path')->pluck('attachment_path')
            )
            ->filter()
            ->unique();

        foreach ($privatePaths as $path) {
            if (Storage::disk('private')->exists($path)) {
                Storage::disk('private')->delete($path);
            }
        }

        $user->forceDelete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to("/");
    }
}
