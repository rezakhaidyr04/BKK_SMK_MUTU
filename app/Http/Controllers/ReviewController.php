<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        // P0 H-01: create DAN store wajib auth — cegah spam anonim via direct POST.
        $this->middleware('auth')->only(['create', 'store']);
    }

    /**
     * Show review submission form (if needed)
     * Khusus pengguna umum — admin/company tidak perlu bagikan ulasan.
     */
    public function create()
    {
        if (auth()->check() && auth()->user()->role !== 'umum') {
            return redirect()->route('dashboard')->with('error', 'Halaman ulasan khusus untuk pengguna umum.');
        }

        // Prefill dari tombol "Beri Ulasan" di profil perusahaan (?company=id).
        $selectedCompany = null;
        if (request()->filled('company')) {
            $selectedCompany = \App\Models\Company::find(request()->query('company'));
        }

        // Daftar nama perusahaan untuk datalist agar ejaan selalu cocok
        // dengan profil (ulasan tampil di profil yang namanya sama persis).
        $companyNames = \App\Models\Company::orderBy('name')->pluck('name');

        return view('reviews.create', compact('selectedCompany', 'companyNames'));
    }

    /**
     * Store a newly created review
     */
    public function store(StoreReviewRequest $request)
    {
        if (auth()->user()->role !== 'umum') {
            return back()->with('error', 'Hanya pengguna umum yang dapat memberikan ulasan.');
        }

        $validated = $request->validated();

        // Samakan ejaan ke nama perusahaan yang terdaftar (case-insensitive)
        // agar ulasan otomatis tampil di profil perusahaan tersebut.
        $companyName = isset($validated['company_name']) ? trim($validated['company_name']) : null;
        if ($companyName) {
            $matched = \App\Models\Company::where('name', $companyName)->first(['name']);
            if ($matched) {
                $companyName = $matched->name;
            }
        }

        // Anti-spam: satu pengguna hanya satu ulasan per perusahaan
        // (desain: ulasan langsung tampil, jadi flood harus dicegah di depan).
        if ($companyName && Review::where('user_id', auth()->id())->where('company_name', $companyName)->exists()) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk perusahaan ini.')->withInput();
        }

        try {
            $companyId = $companyName
                ? \App\Models\Company::where('name', $companyName)->value('id')
                : null;
            Review::create([
                'user_id' => auth()->id(),
                'company_id' => $companyId,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'job_title' => $validated['job_title'] ?? null,
                'company_name' => $companyName,
                'name' => $validated['name'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'status' => 'approved', // Langsung tampil tanpa ijin admin
            ]);

            return back()->with('success', 'Terima kasih! Ulasan Anda langsung ditampilkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Review store failed: '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan review. Silakan coba lagi.')->withInput();
        }
    }

}
