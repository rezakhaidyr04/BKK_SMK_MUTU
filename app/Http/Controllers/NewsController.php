<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        // published(): is_published + published_at (berita terjadwal tidak muncul sebelum waktunya).
        $query = News::query()->published();

        // Filter by category
        if ($request->filled('category')) {
            $query->category($request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->latest()->paginate(9)->withQueryString();

        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        // Draft / terjadwal hanya bisa dipratinjau admin.
        // Publik tetap 404 agar tidak membocorkan konten belum terbit.
        $isScheduled = $news->published_at !== null && $news->published_at->isFuture();
        if (!$news->is_published || $isScheduled) {
            $isAdmin = auth()->check() && auth()->user()->role === 'admin';
            abort_unless($isAdmin, 404);
        }

        // Get related news
        $relatedNews = News::query()->published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
