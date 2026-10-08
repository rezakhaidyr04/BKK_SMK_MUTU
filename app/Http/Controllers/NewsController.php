<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('author')
            ->where('is_published', true);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $news = $query->latest()->paginate(9);

        return view('news.index', compact('news'));
    }

    public function show(News $news)
    {
        // Draft hanya bisa dipratinjau admin (tombol "Lihat" di admin).
        // Publik tetap 404 agar tidak membocorkan konten belum terbit.
        if (!$news->is_published) {
            $isAdmin = auth()->check() && auth()->user()->role === 'admin';
            abort_unless($isAdmin, 404);
        }

        $news->load('author');

        // Get related news
        $relatedNews = News::where('is_published', true)
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }
}
