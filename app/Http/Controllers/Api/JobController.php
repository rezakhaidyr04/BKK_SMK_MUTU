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
     * P5.6: gunakan scopeActive() (H1: status active + deadline
     * hari-inklusif/NULL) agar konsisten dengan business rule web.
     * belum expired yang publik. Filter di query/database level.
     */
    public function index(Request $request)
    {
        $query = Job::active()
            ->with(['company' => function ($query) {
                $query->select('id', 'name', 'industry', 'logo', 'website', 'description');
            }]);

        // Filter lokasi nasional (opsional, backward-compatible: diabaikan bila kosong).
        if ($request->filled('province')) {
            $province = $request->query('province');
            $query->where(function ($q) use ($province) {
                $q->where('province', $province)
                    ->orWhere(function ($qq) use ($province) {
                        $qq->whereNull('province')->where('location', 'like', "%{$province}%");
                    });
            });
        }

        if ($request->filled('city')) {
            $city = $request->query('city');
            $short = \App\Support\IndonesiaRegions::shortName($city);
            $query->where(function ($q) use ($city, $short) {
                $q->where('city', $city)
                    ->orWhere(function ($qq) use ($city, $short) {
                        $qq->whereNull('city')->where(function ($qqq) use ($city, $short) {
                            $qqq->where('location', 'like', "%{$city}%");
                            if ($short !== $city) {
                                $qqq->orWhere('location', 'like', "%{$short}%");
                            }
                        });
                    });
            });
        }

        if ($request->filled('district')) {
            $district = $request->query('district');
            $query->where(function ($q) use ($district) {
                $q->where('district', $district)
                    ->orWhere(function ($qq) use ($district) {
                        $qq->whereNull('district')->where('location', 'like', "%{$district}%");
                    });
            });
        }

        $jobs = $query->latest()
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
