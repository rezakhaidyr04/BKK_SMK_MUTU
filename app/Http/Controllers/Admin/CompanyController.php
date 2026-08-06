<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('industry', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        $companies = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $pendingCount = Company::where('verification_status', 'pending')->count();

        return view('admin.companies.index', compact('companies', 'pendingCount'));
    }

    public function show(Company $company)
    {
        $company->load(['user', 'jobs']);

        return view('admin.companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'industry' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'is_verified' => ['nullable', 'boolean'],
        ]);

        $company->update([
            'name' => $validated['name'],
            'industry' => $validated['industry'] ?? null,
            'description' => $validated['description'] ?? null,
            'website' => $validated['website'] ?? null,
            'address' => $validated['address'] ?? null,
            'is_verified' => (bool) ($validated['is_verified'] ?? false),
            'verification_status' => (bool) ($validated['is_verified'] ?? false) ? 'verified' : 'pending',
        ]);

        return redirect()->route('admin.companies.index')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }

    public function approve(Company $company)
    {
        $company->update([
            'is_verified' => true,
            'verification_status' => 'verified',
            'rejection_reason' => null,
        ]);

        return redirect()->route('admin.companies.index')->with('success', 'Perusahaan berhasil diverifikasi.');
    }

    public function reject(Request $request, Company $company)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $company->update([
            'is_verified' => false,
            'verification_status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->route('admin.companies.index')->with('success', 'Verifikasi perusahaan ditolak.');
    }
}
