<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $company = auth()->user()->company;

        return view('company.profile.edit', compact('company'));
    }

    public function update(Request $request)
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('company.profile.edit')->with('error', 'Profil perusahaan belum tersedia.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $company->update($validated);

        return redirect()->route('company.profile.edit')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    public function verify(Request $request)
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('company.profile.edit')->with('error', 'Profil perusahaan belum tersedia.');
        }

        $validated = $request->validate([
            'tax_number' => ['nullable', 'string', 'max:255'],
            'npwp_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'business_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'operating_license' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        // store uploaded verification documents (if any)
        if ($request->hasFile('npwp_file')) {
            $path_npwp = $request->file('npwp_file')->storePubliclyAs(
                "company_verifications/{$company->id}", 'npwp_' . time() . '.' . $request->file('npwp_file')->getClientOriginalExtension(),
                ['disk' => 'public']
            );
            $company->npwp_path = $path_npwp;
        }

        if ($request->hasFile('business_license')) {
            $path = $request->file('business_license')->storePubliclyAs(
                "company_verifications/{$company->id}", 'business_license_' . time() . '.' . $request->file('business_license')->getClientOriginalExtension(),
                ['disk' => 'public']
            );
            $company->business_license_path = $path;
        }

        if ($request->hasFile('operating_license')) {
            $path2 = $request->file('operating_license')->storePubliclyAs(
                "company_verifications/{$company->id}", 'operating_license_' . time() . '.' . $request->file('operating_license')->getClientOriginalExtension(),
                ['disk' => 'public']
            );
            $company->operating_license_path = $path2;
        }

        $company->tax_number = $validated['tax_number'] ?? null;

        // mark company as pending verification
        $company->verification_status = 'pending';
        $company->is_verified = false;
        $company->save();

        return redirect()->route('company.profile.edit')->with('success', 'Permintaan verifikasi telah dikirim. Tim admin akan meninjaunya.');
    }
}
