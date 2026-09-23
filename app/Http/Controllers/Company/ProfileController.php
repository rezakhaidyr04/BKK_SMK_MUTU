<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyProfileRequest;
use App\Http\Requests\VerifyCompanyRequest;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $company = auth()->user()->company;

        // P6: user role company tanpa baris company (mis. role diubah admin
        // via admin.users.update yang tidak membuat Company) → 404 seperti
        // ApplicantController@index, bukan 500 di blade. update()/verify()
        // sudah guard; edit() satu-satunya yang belum.
        abort_unless($company, 404, 'Profil perusahaan tidak ditemukan.');

        return view('company.profile.edit', compact('company'));
    }

    public function update(UpdateCompanyProfileRequest $request)
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('company.profile.edit')->with('error', 'Profil perusahaan belum tersedia.');
        }

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $processor = new ImageProcessor(quality: 85, maxWidth: 400, maxHeight: 400);
            $logoPath = $processor->store(
                $request->file('logo'),
                'company-logos',
                'logo-' . $company->id . '-' . time()
            );
            if ($logoPath) {
                $validated['logo'] = $logoPath;
            }
        } else {
            unset($validated['logo']);
        }

        $company->update($validated);

        return redirect()->route('company.profile.edit')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    public function verify(VerifyCompanyRequest $request)
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('company.profile.edit')->with('error', 'Profil perusahaan belum tersedia.');
        }

        $validated = $request->validated();

        if (isset($validated['tax_number'])) {
            $company->tax_number = $validated['tax_number'];
        }

        if ($request->hasFile('business_license')) {
            if ($company->business_license_path && Storage::disk('private')->exists($company->business_license_path)) {
                Storage::disk('private')->delete($company->business_license_path);
            }
            $file = $request->file('business_license');
            $ext = $file->extension() ?: strtolower($file->getClientOriginalExtension());
            $path = $file->storeAs(
                "company_verifications/{$company->id}", 'business_license_' . time() . '.' . $ext,
                'private'
            );
            $company->business_license_path = $path;
        }

        if ($request->hasFile('operating_license')) {
            if ($company->operating_license_path && Storage::disk('private')->exists($company->operating_license_path)) {
                Storage::disk('private')->delete($company->operating_license_path);
            }
            $file2 = $request->file('operating_license');
            $ext2 = $file2->extension() ?: strtolower($file2->getClientOriginalExtension());
            $path2 = $file2->storeAs(
                "company_verifications/{$company->id}", 'operating_license_' . time() . '.' . $ext2,
                'private'
            );
            $company->operating_license_path = $path2;
        }

        // mark company as pending verification
        $company->verification_status = 'pending';
        $company->is_verified = false;
        $company->save();

        return redirect()->route('company.profile.edit')->with('success', 'Permintaan verifikasi telah dikirim. Tim admin akan meninjaunya.');
    }

    public function downloadMou(Request $request)
    {
        $company = auth()->user()->company;
        abort_unless($company, 404, 'Profil perusahaan tidak ditemukan.');
        $this->authorize('downloadMou', $company);

        abort_unless($company->mou_path, 404, 'File MoU tidak ditemukan.');

        $disk = Storage::disk('private')->exists($company->mou_path) ? 'private' : 'local';
        abort_unless(Storage::disk($disk)->exists($company->mou_path), 404, 'File MoU tidak ditemukan di storage.');

        $fileName = 'MoU_' . Str::slug($company->name) . '_' .
                    ($company->mou_number ? Str::slug($company->mou_number) . '_' : '') .
                    now()->format('Ymd') . '.' .
                    pathinfo($company->mou_path, PATHINFO_EXTENSION);

        return response()->file(Storage::disk($disk)->path($company->mou_path), [
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
