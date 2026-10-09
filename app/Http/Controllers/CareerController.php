<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCareerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

/**
 * Data karier (posisi, bio, skill, portofolio, pendidikan, pengalaman)
 * diedit dari halaman Pembuat CV — tersimpan ke kolom users yang SAMA
 * dengan form profil (satu sumber, tanpa duplikasi).
 */
class CareerController extends Controller
{
    public function update(StoreCareerRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->isCompany()) {
            return Redirect::route('company.profile.edit')->with('error', 'Silakan kelola profil perusahaan melalui halaman profil perusahaan.');
        }

        $user->updateCareer($request->validated(), $request->input("skills", []));

        return Redirect::route("cv.builder")->with("status", "career-updated");
    }
}
