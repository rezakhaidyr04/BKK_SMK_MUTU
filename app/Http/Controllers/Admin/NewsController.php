<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminNewsStoreRequest;
use App\Http\Requests\AdminNewsUpdateRequest;
use App\Http\Requests\AdminNewsUploadImageRequest;
use App\Models\News;
use App\Services\ImageProcessor;
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

    public function store(AdminNewsStoreRequest $request)
    {
        $validated = $request->validated();

        $slug = Str::slug($validated['title']);
        $baseSlug = $slug;
        $count = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $processor = new ImageProcessor(quality: 82, maxWidth: 1200, maxHeight: 800);
            $thumbnailPath = $processor->store(
                $request->file('thumbnail'),
                'news-thumbnails',
                'thumb-' . Str::slug($validated['title']) . '-' . time()
            );
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

    public function update(AdminNewsUpdateRequest $request, News $news)
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $processor = new ImageProcessor(quality: 82, maxWidth: 1200, maxHeight: 800);
            $validated['thumbnail'] = $processor->store(
                $request->file('thumbnail'),
                'news-thumbnails',
                'thumb-' . Str::slug($validated['title']) . '-' . time()
            );
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

    public function uploadImage(AdminNewsUploadImageRequest $request)
    {
        $request->validated();

        $processor = new ImageProcessor(quality: 80, maxWidth: 1200, maxHeight: 1200);
        $path = $processor->store(
            $request->file('image'),
            'news-images',
            'img-' . time() . '-' . Str::random(6)
        );

        if (! $path) {
            return response()->json(['error' => 'Gagal memproses gambar.'], 422);
        }

        return response()->json([
            'url' => asset('storage/' . $path),
        ]);
    }
}
