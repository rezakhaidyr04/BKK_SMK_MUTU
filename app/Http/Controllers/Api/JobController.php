<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $jobs = Job::where('status', 'active')
            ->with('company')
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => $jobs->items(),
            'meta' => [
                'current_page' => $jobs->currentPage(),
                'last_page' => $jobs->lastPage(),
                'total' => $jobs->total(),
            ],
        ]);
    }

    public function show(Job $job)
    {
        if ($job->status !== 'active') {
            return response()->json(['message' => 'Lowongan tidak ditemukan.'], 404);
        }

        return response()->json([
            'data' => $job->load('company'),
        ]);
    }
}
