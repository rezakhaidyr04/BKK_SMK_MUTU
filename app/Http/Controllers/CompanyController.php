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

        return view('companies.show', compact('company', 'activeJobs', 'stats'));
    }
}
