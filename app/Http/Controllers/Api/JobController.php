<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * P0 C-01: kolom company dibatasi (select) + serialisasi via Resource
     * agar field sensitif tidak pernah keluar API.
     */
    public function index(Request $request)
    {
        $jobs = Job::where('status', 'active')
            ->with(['company' => function ($query) {
                $query->select('id', 'name', 'industry', 'logo', 'website', 'description');
            }])
            ->latest()
            ->paginate(20);

        return response()->json([
            'data' => JobResource::collection($jobs->items()),
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

        $job->load(['company' => function ($query) {
            $query->select('id', 'name', 'industry', 'logo', 'website', 'description');
        }]);

        return response()->json([
            'data' => new JobResource($job),
        ]);
    }
}
