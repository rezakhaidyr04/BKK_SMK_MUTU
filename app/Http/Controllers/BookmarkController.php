<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::with(['job.company.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function destroy(Bookmark $bookmark)
    {
        // Authorization check
        if ($bookmark->user_id !== Auth::id()) {
            abort(403);
        }

        $bookmark->delete();

        return back()->with('success', 'Lowongan tersimpan berhasil dihapus.');
    }
}
