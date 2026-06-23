<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            return redirect()
                ->route("dashboard")
                ->with(
                    "error",
                    "Profil perusahaan tidak ditemukan. Hubungi admin.",
                );
        }

        return view("company.profile.edit", compact("company", "user"));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company) {
            abort(404);
        }

        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "industry" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "string", "max:2000"],
            "website" => ["nullable", "url", "max:255"],
            "address" => ["nullable", "string", "max:500"],
            "logo" => [
                "nullable",
                "image",
                "max:2048",
                "mimes:jpg,jpeg,png,webp",
            ],
        ]);

        if ($request->hasFile("logo")) {
            if ($company->logo) {
                Storage::disk("public")->delete($company->logo);
            }
            $validated["logo"] = $request
                ->file("logo")
                ->store("company-logos", "public");
        } else {
            unset($validated["logo"]);
        }

        // Jika sebelumnya ditolak → reset ke pending supaya admin tinjau ulang
        if ($company->verification_status === "rejected") {
            $validated["verification_status"] = "pending";
            $validated["rejection_reason"] = null;
        }

        $company->update($validated);

        $message =
            $company->verification_status === "pending" &&
            $company->wasChanged("verification_status")
                ? "Profil diperbarui dan diajukan ulang untuk verifikasi admin."
                : "Profil perusahaan berhasil diperbarui.";

        return redirect()
            ->route("company.profile.edit")
            ->with("success", $message);
    }
}
