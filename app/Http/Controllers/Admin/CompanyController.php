<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with("user");

        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%{$search}%")
                    ->orWhere("industry", "like", "%{$search}%")
                    ->orWhere("address", "like", "%{$search}%");
            });
        }

        if ($request->filled("status")) {
            $query->where("verification_status", $request->status);
        }

        $companies = $query
            ->orderByRaw(
                "FIELD(verification_status, 'pending', 'rejected', 'verified')",
            )
            ->orderByDesc("created_at")
            ->paginate(15)
            ->withQueryString();

        // Jumlah yang menunggu verifikasi (untuk badge)
        $pendingCount = Company::where(
            "verification_status",
            "pending",
        )->count();

        return view(
            "admin.companies.index",
            compact("companies", "pendingCount"),
        );
    }

    public function show(Company $company)
    {
        $company->load("user", "jobs");
        return view("admin.companies.show", compact("company"));
    }

    public function edit(Company $company)
    {
        return view("admin.companies.edit", compact("company"));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            "name" => ["required", "string", "max:255"],
            "industry" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "string"],
            "website" => ["nullable", "url", "max:255"],
            "address" => ["nullable", "string", "max:255"],
            "is_verified" => ["nullable", "boolean"],
        ]);

        $isVerified = $validated["is_verified"] ?? false;

        $company->update([
            "name" => $validated["name"],
            "industry" => $validated["industry"],
            "description" => $validated["description"],
            "website" => $validated["website"],
            "address" => $validated["address"],
            "is_verified" => $isVerified,
            "verification_status" => $isVerified
                ? "verified"
                : $company->verification_status,
        ]);

        return redirect()
            ->route("admin.companies.index")
            ->with("success", "Perusahaan berhasil diperbarui.");
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()
            ->route("admin.companies.index")
            ->with("success", "Perusahaan berhasil dihapus.");
    }

    /**
     * Setujui verifikasi perusahaan.
     */
    public function approve(Company $company)
    {
        $company->update([
            "is_verified" => true,
            "verification_status" => "verified",
            "rejection_reason" => null,
        ]);

        return redirect()
            ->back()
            ->with(
                "success",
                "Perusahaan \"{$company->name}\" berhasil diverifikasi.",
            );
    }

    /**
     * Tolak verifikasi perusahaan dengan alasan.
     */
    public function reject(Request $request, Company $company)
    {
        $request->validate([
            "rejection_reason" => ["required", "string", "max:500"],
        ]);

        $company->update([
            "is_verified" => false,
            "verification_status" => "rejected",
            "rejection_reason" => $request->rejection_reason,
        ]);

        return redirect()
            ->back()
            ->with("success", "Verifikasi \"{$company->name}\" ditolak.");
    }
}
