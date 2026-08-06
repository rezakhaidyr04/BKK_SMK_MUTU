<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;

        if (! $company) {
            abort(404, 'Profil perusahaan tidak ditemukan.');
        }

        $applications = Application::with(['job', 'user'])
            ->whereHas('job', function ($query) use ($company) {
                $query->where('company_id', $company->id)
                    ->orWhere('company_name', $company->name);
            })
            ->latest()
            ->paginate(10);

        return view('company.applicants.index', compact('applications', 'company'));
    }
}
