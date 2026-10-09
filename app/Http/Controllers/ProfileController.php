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
                return Redirect::route("profile.edit")->withErrors([
                    "avatar" => "Foto gagal diproses. Coba file JPG/PNG/WebP lain.",
                ])->withInput();
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

        // Sync skills — implementasi tunggal di User::syncSkillsFromNames
        // (dipakai juga CareerController; aturan identik).
        $user->syncSkillsFromNames($request->input("skills", []));

        return Redirect::route("profile.edit")->with(
            "status",
            "profile-updated",
        );
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isCompany()) {
            return Redirect::route('company.profile.edit')->with('error', 'Silakan kelola profil perusahaan melalui halaman profil perusahaan.');
        }

        $request->validate(
            [
                'avatar' => [
                    'required',
                    'image',
                    'max:3072',
                    'mimes:jpg,jpeg,png,webp',
                    'mimetypes:image/jpeg,image/png,image/webp',
                ],
            ],
            [
                'avatar.required' => 'Pilih foto dulu.',
                'avatar.image' => 'File harus berupa gambar.',
                'avatar.max' => 'Ukuran foto maksimal 3MB.',
                'avatar.mimes' => 'Format foto harus JPG, PNG, atau WebP.',
            ]
        );

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $processor = new ImageProcessor(quality: 82, maxWidth: 320, maxHeight: 320);
        $path = $processor->store($request->file('avatar'), 'profile-photos', 'avatar-' . $user->id . '-' . time());

        if (! $path) {
            return Redirect::route('profile.edit')->withErrors([
                'avatar' => 'Foto gagal diproses. Coba file JPG/PNG/WebP lain.',
            ]);
        }

        $user->avatar = $path;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
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
        // surat lamaran, lampiran, SKCK) agar tidak yatim di disk setelah
        // forceDelete me-cascade baris DB-nya. Pola sama seperti destroy per-resource.
        $privatePaths = $user->certificates()->pluck('file_path')
            ->merge($user->cvFiles()->pluck('file_path'))
            ->merge($user->documents()->pluck('file_path'))
            ->merge(
                $user->applications()->whereNotNull('attachment_path')->pluck('attachment_path')
            )
            ->merge(
                $user->applications()->whereNotNull('cover_letter_path')->pluck('cover_letter_path')
            )
            ->merge(
                $user->applications()->whereNotNull('skck_path')->pluck('skck_path')
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
