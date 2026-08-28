<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::with('user')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.activities.index', compact('activities'));
    }
}
