<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminNewsStoreRequest;
use App\Http\Requests\AdminNewsUpdateRequest;
use App\Models\News;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::latest('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        $news = $query->paginate(15)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(AdminNewsStoreRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $processor = new ImageProcessor(quality: 82, maxWidth: 1200, maxHeight: 900);
            $validated['thumbnail'] = $processor->store(
                $request->file('thumbnail'),
                'news-thumbnails',
                'thumb-' . time()
            );
        }

        if (!$validated['is_published']) {
            $validated['published_at'] = null;
        } elseif (blank($validated['published_at'] ?? null)) {
            $validated['published_at'] = now();
        }

        News::create($validated);

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
            $processor = new ImageProcessor(quality: 82, maxWidth: 1200, maxHeight: 900);
            $stored = $processor->store(
                $request->file('thumbnail'),
                'news-thumbnails',
                'thumb-' . time()
            );
            if ($stored) {
                if ($news->thumbnail) {
                    Storage::disk('public')->delete($news->thumbnail);
                }
                $validated['thumbnail'] = $stored;
            } else {
                unset($validated['thumbnail']);
            }
        } else {
            unset($validated['thumbnail']);
        }

        if (!$validated['is_published']) {
            $validated['published_at'] = null;
        } elseif (blank($validated['published_at'] ?? null)) {
            $validated['published_at'] = now();
        }

        $news->update($validated);

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
}
