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
    public function edit(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->role === 'company') {
            return Redirect::route('company.profile.edit');
        }

        return view("profile.edit", [
            "user" => $user->load("student", "skills", "cvFiles", "documents"),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->role === 'company') {
            return Redirect::route('company.profile.edit')->with('error', 'Silakan kelola profil perusahaan melalui halaman profil perusahaan.');
        }

        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile("avatar")) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk("public")->delete($user->avatar);
            }

            $processor = new ImageProcessor(quality: 82, maxWidth: 320, maxHeight: 320);
            $avatarName = 'avatar-' . $user->id . '-' . time();
            $path = $processor->store($request->file("avatar"), 'avatars', $avatarName);

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

        if ($user->isDirty("email")) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Sync skills
        $submittedSkills = $request->input("skills", []);
        $skillIds = [];
        foreach ($submittedSkills as $skillName) {
            $skillName = trim($skillName);
            if ($skillName !== "") {
                $skill = \App\Models\Skill::firstOrCreate([
                    "name" => $skillName,
                ]);
                $skillIds[$skill->id] = ["proficiency" => 3];
            }
        }
        $user->skills()->sync($skillIds);

        // Simpan data akademik (student/alumni)
        if (in_array($user->role, ["student", "alumni"])) {
            $studentData = array_filter(
                [
                    "major" => $request->input("major"),
                    "graduation_year" =>
                        $request->input("graduation_year") ?: null,
                    "address" => $request->input("address"),
                    "linkedin_url" => $request->input("linkedin_url"),
                    "portfolio_url" => $request->input("portfolio_url"),
                    "preferred_position" => $request->input("preferred_position"),
                    "education_history" => $request->input("education_history"),
                    "experience_organization" => $request->input("experience_organization"),
                    "birth_place" => $request->input("birth_place"),
                    "birth_date" => $request->input("birth_date"),
                    "gender" => $request->input("gender"),
                ],
                fn($v) => $v !== null && $v !== "",
            );

            if ($user->student) {
                $user->student->update($studentData);
            } else {
                $user->student()->create($studentData);
            }
        }

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

        $user->forceDelete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to("/");
    }
}
