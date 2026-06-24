<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Http\Requests\JobRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $company = Auth::user()->company;

        if (!$company) {
            return redirect()
                ->route("dashboard")
                ->with(
                    "error",
                    "Profil perusahaan belum lengkap. Silakan lengkapi profil perusahaan Anda.",
                );
        }

        $query = Job::where("company_id", $company->id)->withCount(
            "applications",
        );

        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        if ($request->filled("search")) {
            $query->where(function ($query) use ($request) {
                $query
                    ->where("title", "like", "%" . $request->search . "%")
                    ->orWhere("position", "like", "%" . $request->search . "%");
            });
        }

        $jobs = $query->latest()->paginate(10)->withQueryString();

        return view("company.jobs.index", compact("jobs"));
    }

    public function create()
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()
                ->route("dashboard")
                ->with(
                    "error",
                    "Profil perusahaan belum lengkap. Silakan lengkapi profil perusahaan Anda.",
                );
        }

        if (!$company->is_verified) {
            return redirect()
                ->route("company.profile.edit")
                ->with(
                    "error",
                    "Akun perusahaan Anda belum diverifikasi oleh admin. Lengkapi profil perusahaan dan tunggu verifikasi untuk mulai memposting lowongan.",
                );
        }

        return view("company.jobs.create");
    }

    public function store(JobRequest $request)
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()
                ->route("dashboard")
                ->with("error", "Profil perusahaan belum lengkap.");
        }

        if (!$company->is_verified) {
            return redirect()
                ->route("company.profile.edit")
                ->with(
                    "error",
                    "Akun perusahaan belum diverifikasi. Lengkapi profil dan tunggu verifikasi admin.",
                );
        }

        $validated = $request->validated();

        $validated["company_id"] = $company->id;

        Job::create($validated);

        return redirect()
            ->route("company.jobs.index")
            ->with("success", "Lowongan berhasil dibuat.");
    }

    public function edit(Job $job)
    {
        $company = Auth::user()->company;
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        return view("company.jobs.edit", compact("job"));
    }

    public function update(JobRequest $request, Job $job)
    {
        $company = Auth::user()->company;
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validated();

        $job->update($validated);

        return redirect()
            ->route("company.jobs.index")
            ->with("success", "Lowongan berhasil diperbarui.");
    }

    public function destroy(Job $job)
    {
        $company = Auth::user()->company;
        if (!$company || $job->company_id !== $company->id) {
            abort(403);
        }

        $job->delete();

        return redirect()
            ->route("company.jobs.index")
            ->with("success", "Lowongan berhasil dihapus.");
    }
}
