<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        // Require authentication for create method
        $this->middleware('auth')->only(['create']);
    }

    /**
     * Show review submission form (if needed)
     */
    public function create()
    {
        return view('reviews.create');
    }

    /**
     * Store a newly created review
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000|min:10',
            'job_title' => 'nullable|string|max:100',
            'company_name' => 'nullable|string|max:150',
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            Review::create([
                'user_id' => auth()->id() ?? null,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'job_title' => $validated['job_title'],
                'company_name' => $validated['company_name'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'status' => 'pending', // Requires approval
            ]);

            return back()->with('success', 'Terima kasih! Review Anda sedang ditinjau dan akan ditampilkan segera.');
        } catch (\Exception $e) {
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
