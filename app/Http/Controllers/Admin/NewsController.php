<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with('author')->latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $news     = $query->paginate(15)->withQueryString();
        $categories = News::select('category')->distinct()->whereNotNull('category')->pluck('category');

        return view('admin.news.index', compact('news', 'categories'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['nullable', 'string', 'max:100'],
            'content'      => ['required', 'string'],
            'thumbnail'    => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $baseSlug = $slug;
        $count = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('news-thumbnails', 'public');
        }

        News::create([
            'user_id'      => Auth::id(),
            'title'        => $validated['title'],
            'slug'         => $slug,
            'category'     => $validated['category'],
            'content'      => $validated['content'],
            'thumbnail'    => $thumbnailPath,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'category'     => ['nullable', 'string', 'max:100'],
            'content'      => ['required', 'string'],
            'thumbnail'    => ['nullable', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('news-thumbnails', 'public');
        } else {
            unset($validated['thumbnail']);
        }

        $news->update([
            'title'        => $validated['title'],
            'category'     => $validated['category'],
            'content'      => $validated['content'],
            'thumbnail'    => $validated['thumbnail'] ?? $news->thumbnail,
            'is_published' => $request->boolean('is_published'),
        ]);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp,gif'],
        ]);

        $path = $request->file('image')->store('news-images', 'public');

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
