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
        // H1: satu definisi aktif via scopeActive(). Jangan tulis ulang
        // logika deadline di sini.
        $activeJobs = $company->jobs()
            ->active()
            ->withCount('applications')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        // Hanya statistik publik: jumlah lowongan aktif (= isi daftar di bawah).
        // DILARANG menambah stat internal (total pelamar, arsip, data akun).
        $stats = [
            'active_jobs' => $activeJobs->total(),
        ];

        // M5: company_id kanonis; fallback nama HANYA untuk data lama
        // tanpa company_id (perusahaan bernama sama tidak tercampur).
        // M8: statistik dari agregat database TANPA limit (bukan dari 10
        // sampel tampilan) — mencakup seluruh review valid.
        $reviewBase = \App\Models\Review::approved()->where(function ($q) use ($company) {
            // company_id kanonis; company_name fallback data lama.
            $q->where('company_id', $company->id)
                ->orWhere(function ($qq) use ($company) {
                    $qq->whereNull('company_id')->where('company_name', $company->name);
                });
        });
        $reviewAgg = (clone $reviewBase)->selectRaw('COUNT(*) as c, AVG(rating) as a')->first();
        $reviews = (clone $reviewBase)
            ->with('user:id,name')
            ->latest()
            ->take(10)
            ->get();
        $reviewStats = [
            'count' => (int) ($reviewAgg->c ?? 0),
            'average' => ((int) ($reviewAgg->c ?? 0)) > 0 ? round((float) $reviewAgg->a, 1) : 0,
        ];

        return view('companies.show', compact('company', 'activeJobs', 'stats', 'reviews', 'reviewStats'));
    }
}
