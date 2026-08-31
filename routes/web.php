<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CvBuilderController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SuratPengantarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Job Routes (Public)
Route::get("/jobs", [JobController::class, "index"])->name("jobs.index");
Route::get("/jobs/{job}", [JobController::class, "show"])->name("jobs.show");

// Events (Public)
Route::get("/events", [EventController::class, "index"])->name("events.index");
Route::get("/events/{event}", [EventController::class, "show"])->name("events.show");

// News (Public)
Route::get("/news", [NewsController::class, "index"])->name("news.index");
Route::get("/news/{news}", [NewsController::class, "show"])->name("news.show");

// SEO: Sitemap
Route::get("/sitemap.xml", function () {
    $urls = collect([
        url("/"),
        route("jobs.index"),
        route("events.index"),
        route("news.index"),
    ]);

    foreach (\App\Models\Job::where("status", "active")->latest()->get() as $job) {
        $urls->push(route("jobs.show", $job));
    }
    foreach (\App\Models\News::where("is_published", true)->latest()->get() as $news) {
        $urls->push(route("news.show", $news));
    }
    foreach (\App\Models\Event::latest()->get() as $event) {
        $urls->push(route("events.show", $event));
    }

    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach ($urls->unique() as $url) {
        $xml .= '  <url><loc>' . e($url) . '</loc></url>' . "\n";
    }
    $xml .= '</urlset>';

    return response($xml, 200, ["Content-Type" => "application/xml"]);
});

// Auth Routes
require __DIR__ . "/auth.php";

// Authenticated Routes with rate limiting
Route::middleware(["auth", "throttle:60,1"])->group(function () {
    // Dashboard
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );

    // Event Registration (auth required)
    Route::post("/events/{event}/register", [EventController::class, "register"])->name("events.register");
    Route::delete("/events/{event}/register", [EventController::class, "cancel"])->name("events.cancel");
    Route::get("/my-events", [EventController::class, "myEvents"])->name("events.my");

    // Profile
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    // Documents
    Route::post("/documents", [\App\Http\Controllers\UserDocumentController::class, "store"])->name("documents.store");
    Route::get("/documents/{document}/download", [\App\Http\Controllers\UserDocumentController::class, "download"])->name("documents.download");
    Route::delete("/documents/{document}", [\App\Http\Controllers\UserDocumentController::class, "destroy"])->name("documents.destroy");

    Route::get('/notifications/mark-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

    // Job Applications
    Route::post("/jobs/{job}/apply", [JobController::class, "apply"])->name(
        "jobs.apply",
    );
    Route::post("/jobs/{job}/bookmark", [
        JobController::class,
        "bookmark",
    ])->name("jobs.bookmark");

    // Company Management
    Route::middleware(["role:company"])->prefix("company")->name("company.")->group(function () {
        Route::get("/jobs", [App\Http\Controllers\Company\JobController::class, "index"])->name("jobs.index");
        Route::get("/jobs/create", [App\Http\Controllers\Company\JobController::class, "create"])->name("jobs.create");
        Route::post("/jobs", [App\Http\Controllers\Company\JobController::class, "store"])->name("jobs.store");
        Route::get("/applicants", [App\Http\Controllers\Company\ApplicantController::class, "index"])->name("applicants.index");
        Route::get("/applicants/{application}", [App\Http\Controllers\Company\ApplicantController::class, "show"])->name("applicants.show");
        Route::patch("/applications/{application}", [App\Http\Controllers\Company\ApplicantController::class, "update"])->name("applications.update");
        Route::get("/profile", [App\Http\Controllers\Company\ProfileController::class, "edit"])->name("profile.edit");
        Route::put("/profile", [App\Http\Controllers\Company\ProfileController::class, "update"])->name("profile.update");
        Route::post("/profile/verify", [App\Http\Controllers\Company\ProfileController::class, "verify"])->name("profile.verify");
    });

    // Applications Management
    Route::get("/applications", [ApplicationController::class, "index"])->name(
        "applications.index",
    );
    Route::get("/applications/{application}", [
        ApplicationController::class,
        "show",
    ])->name("applications.show");
    
    Route::get("/applications/{application}/surat-pengantar", [
        SuratPengantarController::class,
        "download",
    ])->name("applications.surat-pengantar");
    Route::get("/applications/{application}/attachment", [
        ApplicationController::class,
        "downloadAttachment",
    ])->name("applications.attachment.download");

    Route::delete("/applications/{application}", [
        ApplicationController::class,
        "destroy",
    ])->name("applications.destroy");

    // Bookmarks
    Route::get("/bookmarks", [BookmarkController::class, "index"])->name(
        "bookmarks.index",
    );
    Route::delete("/bookmarks/{bookmark}", [
        BookmarkController::class,
        "destroy",
    ])->name("bookmarks.destroy");

    // CV Builder
    Route::get("/cv/builder", [CvBuilderController::class, "index"])->name(
        "cv.builder",
    );
    Route::post("/cv/generate", [CvBuilderController::class, "generate"])
        ->middleware('throttle:cv-generate')
        ->name("cv.generate");
    Route::get("/cv/download/{cvFile}", [
        CvBuilderController::class,
        "download",
    ])->name("cv.download");
    Route::delete("/cv/{cvFile}", [
        CvBuilderController::class,
        "destroy",
    ])->name("cv.destroy");

    // Certificates
    Route::get("/certificates", [CertificateController::class, "index"])->name(
        "certificates.index",
    );
    Route::post("/certificates", [CertificateController::class, "store"])->name(
        "certificates.store",
    );
    Route::get("/certificates/{certificate}/download", [CertificateController::class, "download"])->name("certificates.download");
    Route::delete("/certificates/{certificate}", [
        CertificateController::class,
        "destroy",
    ])->name("certificates.destroy");

    // Messages
    Route::get("/messages", [MessageController::class, "index"])->name(
        "messages.index",
    );
    Route::post("/messages/start", [MessageController::class, "start"])
        ->middleware('throttle:send-message')
        ->name("messages.start");
    Route::get("/messages/{conversation}", [
        MessageController::class,
        "show",
    ])->name("messages.show");
    Route::get("/messages/{conversation}/fetch", [
        MessageController::class,
        "fetch",
    ])->name("messages.fetch");
    Route::post("/messages/{conversation}", [
        MessageController::class,
        "send",
    ])->middleware('throttle:send-message')->name("messages.send");

    // Admin Routes
    Route::middleware(["role:admin", "log.activity"])
        ->prefix("admin")
        ->name("admin.")
        ->group(function () {
            // Company Management — FULL CRUD (admin yang buat perusahaan)
            Route::resource(
                "companies",
                App\Http\Controllers\Admin\CompanyController::class,
            )->only(["index", "create", "store", "show", "edit", "update"]);

            Route::post("companies/{company}/approve", [
                App\Http\Controllers\Admin\CompanyController::class,
                "approve",
            ])->name("companies.approve");

            Route::post("companies/{company}/reject", [
                App\Http\Controllers\Admin\CompanyController::class,
                "reject",
            ])->name("companies.reject");

            // Private MoU download — hanya admin
            Route::get("companies/{company}/mou/download", [
                App\Http\Controllers\Admin\CompanyController::class,
                "downloadMou",
            ])->name("companies.mou.download");

            Route::get("companies/{company}/documents/{document}/download", [
                App\Http\Controllers\Admin\CompanyController::class,
                "downloadLegalDocument",
            ])->name("companies.documents.download");

            // Create account for approved company (Phase 3 — placeholder terdaftar di sini)
            Route::post("companies/{company}/create-account", [
                App\Http\Controllers\Admin\CompanyController::class,
                "createAccount",
            ])->name("companies.create-account");

            Route::resource(
                "users",
                App\Http\Controllers\Admin\UserController::class,
            )->only([
                "index",
                "create",
                "store",
                "show",
                "edit",
                "update",
                "destroy",
            ]);
            Route::resource(
                "jobs",
                App\Http\Controllers\Admin\JobController::class,
            )->only(["index", "create", "store", "show", "edit", "update", "destroy"]);
            Route::post("jobs/{job}/broadcast", [
                App\Http\Controllers\Admin\JobController::class,
                "broadcast",
            ])->name("jobs.broadcast");
            Route::post("jobs/{job}/approve", [
                App\Http\Controllers\Admin\JobController::class,
                "approve",
            ])->name("jobs.approve");
            Route::post("jobs/{job}/reject", [
                App\Http\Controllers\Admin\JobController::class,
                "reject",
            ])->name("jobs.reject");
            Route::resource(
                "news",
                App\Http\Controllers\Admin\NewsController::class,
            )->except(["show"]);
            Route::post("/news/upload-image", [
                App\Http\Controllers\Admin\NewsController::class,
                "uploadImage",
            ])->name("news.upload-image");
            Route::resource(
                "events",
                App\Http\Controllers\Admin\EventController::class,
            )->except(["show"]);
            Route::get("events/{event}/registrants", [
                App\Http\Controllers\Admin\EventController::class,
                "registrants",
            ])->name("events.registrants");
            Route::get("/reports", [
                App\Http\Controllers\Admin\ReportController::class,
                "index",
            ])->name("reports.index");
            Route::get("/reports/export", [
                App\Http\Controllers\Admin\ReportController::class,
                "export",
            ])->name("reports.export");
            Route::get("/reports/export-excel", [
                App\Http\Controllers\Admin\ReportController::class,
                "exportExcel",
            ])->name("reports.export-excel");
            Route::get("/reports/export-pdf", [
                App\Http\Controllers\Admin\ReportController::class,
                "exportPdf",
            ])->name("reports.export-pdf");

            // Audit log aktivitas admin
            Route::get("/activities", [
                App\Http\Controllers\Admin\ActivityController::class,
                "index",
            ])->name("activities.index");

            // Personal Access Token (Sanctum) untuk admin
            Route::get("/api-tokens", [
                App\Http\Controllers\Admin\ApiTokenController::class,
                "index",
            ])->name("api-tokens.index");
            Route::post("/api-tokens", [
                App\Http\Controllers\Admin\ApiTokenController::class,
                "store",
            ])->name("api-tokens.store");
        });
});

// A/B Testing Tracking
Route::post('/ab-test/track', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'event' => 'required|string',
        'variations' => 'array',
        'variant' => 'string',
    ]);

    $abTest = app(\App\Services\ABTestingService::class);
    $abTest->trackEvent($validated['event'], [
        'variant' => $validated['variant'] ?? null,
        'variations' => $validated['variations'] ?? [],
        'url' => $request->header('referer'),
        'user_agent' => $request->userAgent(),
    ]);

    return response()->json(['success' => true]);
})->name('ab-test.track');

// Debug playground: preview status badges for different status values
// Only registered in local environment — not accessible in production or staging
if (app()->environment('local')) {
    Route::get('/_debug/status-playground', function () {
        $statuses = [
            'not_submitted', 'submitted', 'under_review', 'interviewed',
            'accepted', 'rejected', 'pending', 'verified', 'draft', 'closed',
            'unknown_status'
        ];

        return view('debug.status-playground', compact('statuses'));
    });
}
