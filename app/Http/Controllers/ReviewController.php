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

        return view('reviews.create');
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

        try {
            Review::create([
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'job_title' => $validated['job_title'],
                'company_name' => $validated['company_name'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => 'approved', // Langsung tampil tanpa ijin admin
            ]);

            return back()->with('success', 'Terima kasih! Ulasan Anda langsung ditampilkan.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Review store failed: '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Terjadi kesalahan saat menyimpan review. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Get stats for dashboard
     */
    public function getStats()
    {
        return [
            'average_rating' => Review::getAverageRating(),
            'total_reviews' => Review::getTotalReviews(),
            'satisfaction_percentage' => Review::getSatisfactionPercentage(),
            'rating_distribution' => Review::getRatingDistribution(),
        ];
    }
}
