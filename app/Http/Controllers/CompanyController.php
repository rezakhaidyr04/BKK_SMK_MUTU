<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Halaman profil perusahaan publik — bisa diakses semua role
     * termasuk tamu. Hanya menampilkan data non-sensitif.
     */
    public function show(Request $request, Company $company)
    {
        // Lowongan aktif milik perusahaan (tayang + belum kedaluwarsa)
        $activeJobs = $company->jobs()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhere('deadline', '>=', now()->startOfDay());
            })
            ->withCount('applications')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        // Hanya statistik publik: jumlah lowongan aktif (= isi daftar di bawah).
        // DILARANG menambah stat internal (total pelamar, arsip, data akun).
        $stats = [
            'active_jobs' => $activeJobs->total(),
        ];

        // Ulasan yang sudah disetujui dan ditujukan ke perusahaan ini (cocok nama persis,
        // case-insensitive mengikuti collation DB) + rata-rata & jumlah.
        $reviews = \App\Models\Review::approved()
            ->with('user:id,name')
            ->where(function ($q) use ($company) {
                // company_id kanonis; company_name fallback data lama.
                $q->where('company_id', $company->id)
                    ->orWhere('company_name', $company->name);
            })
            ->latest()
            ->take(10)
            ->get();
        $reviewStats = [
            'count' => $reviews->count(),
            'average' => $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0,
        ];

        return view('companies.show', compact('company', 'activeJobs', 'stats', 'reviews', 'reviewStats'));
    }
}
