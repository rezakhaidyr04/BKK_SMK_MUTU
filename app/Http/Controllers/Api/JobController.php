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
     * P5.6: gunakan scopeActive() (status active + deadline >= now())
     * agar konsisten dengan business rule web: hanya job aktif yang
     * belum expired yang publik. Filter di query/database level.
     */
    public function index(Request $request)
    {
        $jobs = Job::active()
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
        // P5.6: rule identik dengan index — reuse scopeActive() agar
        // konsisten by construction (tanpa duplikasi logika deadline).
        // Expired/non-active → 404 dengan kontrak response yang sama.
        $isPublic = Job::active()->whereKey($job->id)->exists();

        if (! $isPublic) {
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
