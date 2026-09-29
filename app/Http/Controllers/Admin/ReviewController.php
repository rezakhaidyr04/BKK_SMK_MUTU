<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Admin hanya melihat HASIL ulasan pengguna (read-only).
     * Ulasan langsung tampil tanpa ijin admin — tanpa filter cari & tanpa status.
     */
    public function index(Request $request)
    {
        $query = Review::with('user')->latest();

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        $reviews = $query->paginate(15)->withQueryString();

        $total = Review::count();
        $average = $total > 0 ? round((float) Review::avg('rating'), 1) : 0;
        $satisfied = Review::whereIn('rating', [4, 5])->count();
        $satisfaction = $total > 0 ? (int) round(($satisfied / $total) * 100) : 0;

        $stats = [
            'total' => $total,
            'average' => $average,
            'satisfaction' => $satisfaction,
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }
}
