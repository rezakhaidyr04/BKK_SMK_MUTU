<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view("profile.edit", [
            "user" => $request->user()->load("student", "skills", "cvFiles"),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile("avatar")) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk("public")->delete($user->avatar);
            }
            $path = $request->file("avatar")->store("avatars", "public");
            $validated["avatar"] = $path;
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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to("/");
    }
}
