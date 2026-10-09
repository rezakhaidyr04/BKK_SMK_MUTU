<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Bookmark;
use App\Models\Application;
use App\Http\Requests\ApplicationRequest;
use App\Notifications\ApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobController extends Controller
{
    public function index(Request $request)
    {
        // H1: satu definisi aktif via scopeActive() (status + deadline
        // hari-inklusif + NULL). Jangan tulis ulang logika deadline di sini.
        $query = Job::with('company')->active();

        // Search
        if ($request->filled("search")) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where("title", "like", "%{$search}%")
                    ->orWhere("position", "like", "%{$search}%")
                    ->orWhere("description", "like", "%{$search}%")
                    ->orWhere("location", "like", "%{$search}%")
                    ->orWhere("company_name", "like", "%{$search}%");
            });
        }

        // Filter by job type
        if ($request->filled("job_type")) {
            $query->where("job_type", $request->job_type);
        }

        // Filter by location (legacy: substring pada kolom location).
        if ($request->filled("location")) {
            $query->where("location", "like", "%{$request->location}%");
        }

        // Filter nasional: province dan/atau city. Baris legacy
        // (province/city NULL) tetap terjangkau via fallback location.
        if ($request->filled("province")) {
            $province = $request->province;
            $query->where(function ($q) use ($province) {
                $q->where("province", $province)
                    ->orWhere(function ($qq) use ($province) {
                        $qq->whereNull("province")->where("location", "like", "%{$province}%");
                    });
            });
        }

        if ($request->filled("city")) {
            $city = $request->city;
            $short = \App\Support\IndonesiaRegions::shortName($city);
            $query->where(function ($q) use ($city, $short) {
                $q->where("city", $city)
                    ->orWhere(function ($qq) use ($city, $short) {
                        $qq->whereNull("city")->where(function ($qqq) use ($city, $short) {
                            $qqq->where("location", "like", "%{$city}%");
                            if ($short !== $city) {
                                $qqq->orWhere("location", "like", "%{$short}%");
                            }
                        });
                    });
            });
        }

        if ($request->filled("district")) {
            $district = $request->district;
            $query->where(function ($q) use ($district) {
                $q->where("district", $district)
                    ->orWhere(function ($qq) use ($district) {
                        $qq->whereNull("district")->where("location", "like", "%{$district}%");
                    });
            });
        }

        // Filter by salary range
        if ($request->filled("salary_min")) {
            $query->where("salary_min", ">=", $request->salary_min);
        }

        // Sort
        $sortBy = $request->get("sort", "latest");
        switch ($sortBy) {
            case "salary_high":
                $query->orderByDesc("salary_max");
                break;
            case "salary_low":
                $query->orderBy("salary_min");
                break;
            case "deadline":
                $query->orderBy("deadline");
                break;
            default:
                $query->latest();
        }

        $jobs = $query->paginate(12);

        // Get filter options
        $jobTypes = Job::select("job_type")
            ->distinct()
            ->pluck("job_type");

        // Use predefined locations from config
        $locations = collect(config('locations.locations', []));

        // Master wilayah nasional untuk filter province → city.
        $provinces = \App\Support\IndonesiaRegions::provinces();

        $activeJobsCount = Job::active()
            ->count();
        $locationsCount = Job::active()
            ->distinct("location")
            ->count("location");
        $companiesCount = Job::active()
            ->distinct("company_name")
            ->count("company_name");

        return view("jobs.index", compact("jobs", "jobTypes", "locations", "provinces", "activeJobsCount", "locationsCount", "companiesCount"));
    }

    public function show(Job $job)
    {
        // H1: satu definisi aktif via $job->isActive() (mirror scopeActive:
        // status active + deadline hari-inklusif + NULL). Guest hanya
        // boleh melihat job active. Konsisten dengan index() dan API.
        abort_unless($job->isActive(), 404);

        // P0 C-04: JANGAN load seluruh applications di halaman publik.
        // Hanya hitung + cek existence milik user login.
        // Eager company (+user pemilik) karena blade memakai $job->company->... berulang.
        $job->loadMissing(['company.user']);
        $job->loadCount(['applications']);
        $applicationsCount = $job->applications_count ?? 0;

        // Check if user has already applied
        $hasApplied = false;
        $isBookmarked = false;
        $hasReported = false;

        if (Auth::check()) {
            $hasApplied = Application::where("job_id", $job->id)
                ->where("user_id", Auth::id())
                ->exists();

            $isBookmarked = Bookmark::where("job_id", $job->id)
                ->where("user_id", Auth::id())
                ->exists();

            // C1: status laporan milik user login (1 query exists, murah).
            $hasReported = \App\Models\JobReport::where("job_id", $job->id)
                ->where("user_id", Auth::id())
                ->exists();
        }

        $savedCount = Bookmark::where("job_id", $job->id)->count();

        // Stat owner (Ditinjau/Diterima) hanya dihitung untuk pemilik lowongan,
        // agar tidak membocorkan pipeline rekrutmen ke publik.
        $reviewedCount = null;
        $acceptedCount = null;
        $ownerApplicationsCount = null;
        $isOwner = Auth::check()
            && Auth::user()->isCompany()
            && $job->company_id
            && Auth::user()->company?->id === $job->company_id;

        if ($isOwner) {
            $ownerApplicationsCount = $applicationsCount;
            $reviewedCount = Application::where('job_id', $job->id)
                ->where('status', 'under_review')
                ->count();
            $acceptedCount = Application::where('job_id', $job->id)
                ->where('status', 'accepted')
                ->count();
        }

        // M8: agregat di database (COUNT+AVG), bukan get() lalu hitung di PHP.
        // Kueri terpisah tanpa limit agar mencakup SELURUH review valid.
        $companyRating = ['count' => 0, 'average' => 0];
        if ($job->company) {
            $agg = \App\Models\Review::approved()
                ->where(function ($q) use ($job) {
                    $q->where('company_id', $job->company->id)
                        ->orWhere(function ($qq) use ($job) {
                            $qq->whereNull('company_id')->where('company_name', $job->company->name);
                        });
                })
                ->selectRaw('COUNT(*) as c, AVG(rating) as a')
                ->first();
            $companyRating['count'] = (int) ($agg->c ?? 0);
            $companyRating['average'] = $companyRating['count'] > 0
                ? round((float) $agg->a, 1)
                : 0;
        }

        // Similar jobs (eager company: blade memakai logo/nama perusahaan).
        // H1: wajib scopeActive() agar tidak merekomendasikan job expired.
        // Lokasi nasional: samakan city dulu, lalu location legacy, lalu job_type.
        $similarJobs = Job::with('company')
            ->where("id", "!=", $job->id)
            ->active()
            ->where(function ($query) use ($job) {
                $query
                    ->where("job_type", $job->job_type)
                    ->orWhere("location", $job->location);

                if ($job->city) {
                    $query->orWhere("city", $job->city);
                }
            })
            ->take(4)
            ->get();

        return view(
            "jobs.show",
            compact("job", "hasApplied", "isBookmarked", "hasReported", "savedCount", "similarJobs", "applicationsCount", "reviewedCount", "acceptedCount", "ownerApplicationsCount", "isOwner", "companyRating"),
        );
    }

    public function apply(ApplicationRequest $request, Job $job)
    {
        abort_unless(Auth::user()?->role === "umum", 403);

        // H1: satu definisi aktif via $job->isActive().
        if (! $job->isActive()) {
            return back()->with('error', 'Lowongan sudah ditutup atau masa berlaku telah berakhir.');
        }

        // H2/H7: cek lamaran AKTIF dulu SEBELUM upload (hemat + cegah yatim).
        // Baris ter-withdraw (trashed) TIDAK menghalangi — ditangani via
        // restore di bawah agar re-apply tidak menabrak unique.
        $existingApplication = Application::where("job_id", $job->id)
            ->where("user_id", Auth::id())
            ->first();

        if ($existingApplication) {
            return back()->with("error", "Anda sudah melamar lowongan ini.");
        }

        // Surat lamaran wajib PDF (ganti ketik manual)
        $coverFile = $request->file("cover_letter_file");
        $coverPath = $coverFile->store("cover_letters", "private");
        $coverName = basename($coverFile->getClientOriginalName());
        $coverMime = $coverFile->getMimeType() ?: $coverFile->getClientMimeType();
        $coverSize = $coverFile->getSize();

        $attachment = $request->file("attachment");
        $attachmentPath = null;
        $attachmentName = null;
        $attachmentMime = null;
        $attachmentSize = null;

        if ($attachment) {
            $attachmentPath = $attachment->store("applications", "private");
            $attachmentName = basename($attachment->getClientOriginalName());
            // P1-H05: server-side MIME detection, jangan percaya client
            $attachmentMime = $attachment->getMimeType() ?: $attachment->getClientMimeType();
            $attachmentSize = $attachment->getSize();
        }

        // SKCK opsional (PDF) — boleh diisi, boleh dikosongkan
        $skck = $request->file("skck_file");
        $skckPath = null;
        $skckName = null;
        $skckMime = null;
        $skckSize = null;

        if ($skck) {
            $skckPath = $skck->store("skck", "private");
            $skckName = basename($skck->getClientOriginalName());
            $skckMime = $skck->getMimeType() ?: $skck->getClientMimeType();
            $skckSize = $skck->getSize();
        }

        // H2/H7: INSERT bisa tetap menabrak unique(job_id,user_id) pada
        // race (double-submit paralel) atau baris ter-withdraw. Tangkap
        // 23000 → pulihkan alur yang benar, JANGAN pernah 500.
        try {
            $application = Application::create([
                "job_id" => $job->id,
                "user_id" => Auth::id(),
                // teks lama dikosongkan untuk data baru (file jadi sumber utama)
                "cover_letter" => $request->filled('cover_letter')
                    ? str_replace(["\r\n", "\n", "\r"], "\n", $request->cover_letter)
                    : null,
                "cover_letter_path" => $coverPath,
                "cover_letter_name" => $coverName,
                "cover_letter_mime" => $coverMime,
                "cover_letter_size" => $coverSize,
                "attachment_path" => $attachmentPath,
                "attachment_name" => $attachmentName,
                "attachment_mime" => $attachmentMime,
                "attachment_size" => $attachmentSize,
                "skck_path" => $skckPath,
                "skck_name" => $skckName,
                "skck_mime" => $skckMime,
                "skck_size" => $skckSize,
                "status" => "submitted",
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            // Re-apply setelah withdraw: pulihkan baris lama + arahkan ke
            // file BARU yang baru di-upload. File lama withdraw baru dihapus
            // SETELAH restore sukses (dan hanya bila berbeda path).
            $trashed = Application::withTrashed()
                ->where("job_id", $job->id)
                ->where("user_id", Auth::id())
                ->first();

            if ($trashed && $trashed->trashed()) {
                $oldPaths = [$trashed->cover_letter_path, $trashed->attachment_path, $trashed->skck_path];
                $trashed->restore();
                $trashed->fill([
                    "cover_letter" => $request->filled('cover_letter')
                        ? str_replace(['\\r\\n', '\\n', '\\r'], "\n", $request->cover_letter)
                        : null,
                    "cover_letter_path" => $coverPath,
                    "cover_letter_name" => $coverName,
                    "cover_letter_mime" => $coverMime,
                    "cover_letter_size" => $coverSize,
                    "attachment_path" => $attachmentPath,
                    "attachment_name" => $attachmentName,
                    "attachment_mime" => $attachmentMime,
                    "attachment_size" => $attachmentSize,
                    "skck_path" => $skckPath,
                    "skck_name" => $skckName,
                    "skck_mime" => $skckMime,
                    "skck_size" => $skckSize,
                    "status" => "submitted",
                ]);
                $trashed->save();
                foreach (array_diff(array_filter($oldPaths), [$coverPath, $attachmentPath, $skckPath]) as $old) {
                    if (Storage::disk('private')->exists($old)) {
                        Storage::disk('private')->delete($old);
                    }
                }
                $application = $trashed;
            } else {
                // Duplikat murni (race/double-submit): file request INI yatim
                // (lamaran tidak tersimpan) → hapus agar tidak menumpuk.
                // File milik request lain tidak disentuh (path unik per upload).
                foreach ([$coverPath, $attachmentPath, $skckPath] as $p) {
                    if ($p && Storage::disk('private')->exists($p)) {
                        Storage::disk('private')->delete($p);
                    }
                }

                \Illuminate\Support\Facades\Log::warning('Lamaran duplikat ditolak dengan ramah', [
                    'job_id' => $job->id, 'user_id' => Auth::id(), 'error' => $e->getMessage(),
                ]);

                return back()->with("error", "Anda sudah melamar lowongan ini.");
            }
        }

        // Beri tahu perusahaan pemilik lowongan (sinkron; kegagalan
        // email tidak boleh menggagalkan lamaran yang sudah tersimpan).
        if ($job->company?->user) {
            try {
                $job->company->user->notify(new ApplicationReceived($application));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Notifikasi lamaran #' . $application->id . ' gagal dikirim: ' . $e->getMessage());
            }
        }

        return redirect()
            ->route("jobs.show", $job)
            ->with("success", "Lamaran Anda berhasil dikirim!");
    }

    public function bookmark(Job $job)
    {
        // Bookmark adalah fitur pencari kerja — selaras dengan apply().
        abort_unless(Auth::user()?->role === "umum", 403);

        $bookmark = Bookmark::where("job_id", $job->id)
            ->where("user_id", Auth::id())
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                "bookmarked" => false,
                "message" => "Lowongan tersimpan dihapus",
            ]);
        } else {
            Bookmark::create([
                "job_id" => $job->id,
                "user_id" => Auth::id(),
            ]);
            return response()->json([
                "bookmarked" => true,
                "message" => "Lowongan berhasil disimpan",
            ]);
        }
    }
}
