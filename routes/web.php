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
use App\Http\Controllers\ReviewController;
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

// Reviews — P0 H-01: store wajib auth (defense-in-depth: route + controller).
Route::get("/reviews/create", [ReviewController::class, "create"])->name("reviews.create");
Route::post("/reviews", [ReviewController::class, "store"])->middleware(['auth', 'throttle:submit-review'])->name("reviews.store");

// SEO: Sitemap (cached 1 hour, chunked + select id only)
Route::get("/sitemap.xml", [\App\Http\Controllers\SitemapController::class, "index"])->name("sitemap");

// Auth Routes
require __DIR__ . "/auth.php";

// Authenticated Routes with rate limiting
Route::middleware(["auth", "throttle:60,1"])->group(function () {
    // Dashboard
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );

    // Event Registration — P0 H-02: tulis sensitif wajib verified.
    Route::post("/events/{event}/register", [EventController::class, "register"])->middleware('verified')->name("events.register");
    Route::post("/events/{event}/payment-proof", [EventController::class, "uploadPaymentProof"])->middleware('verified')->name("events.payment-proof");
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

    // Documents — P0 H-02: upload sensitif wajib verified.
    Route::post("/documents", [\App\Http\Controllers\UserDocumentController::class, "store"])->middleware('verified')->name("documents.store");
    Route::get("/documents/{document}/download", [\App\Http\Controllers\UserDocumentController::class, "download"])->name("documents.download");
    Route::delete("/documents/{document}", [\App\Http\Controllers\UserDocumentController::class, "destroy"])->name("documents.destroy");

    Route::get('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markAllRead');

    // Job Applications — P0 H-02: melamar wajib verified.
    Route::post("/jobs/{job}/apply", [JobController::class, "apply"])->middleware('verified')->name(
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
        // P1 H-11/H-12: management milik sendiri (policy owner di controller).
        Route::get("/jobs/{job}/edit", [App\Http\Controllers\Company\JobController::class, "edit"])->name("jobs.edit");
        Route::put("/jobs/{job}", [App\Http\Controllers\Company\JobController::class, "update"])->name("jobs.update");
        Route::post("/jobs/{job}/close", [App\Http\Controllers\Company\JobController::class, "close"])->name("jobs.close");
        Route::delete("/jobs/{job}", [App\Http\Controllers\Company\JobController::class, "destroy"])->name("jobs.destroy");
        Route::get("/applicants", [App\Http\Controllers\Company\ApplicantController::class, "index"])->name("applicants.index");
        Route::get("/applicants/{application}", [App\Http\Controllers\Company\ApplicantController::class, "show"])->name("applicants.show");
        Route::patch("/applications/{application}", [App\Http\Controllers\Company\ApplicantController::class, "update"])->name("applications.update");
        Route::get("/profile", [App\Http\Controllers\Company\ProfileController::class, "edit"])->name("profile.edit");
        Route::put("/profile", [App\Http\Controllers\Company\ProfileController::class, "update"])->name("profile.update");
        Route::post("/profile/verify", [App\Http\Controllers\Company\ProfileController::class, "verify"])->name("profile.verify");
        Route::get("/mou/download", [App\Http\Controllers\Company\ProfileController::class, "downloadMou"])->name("mou.download");
    });

    // Applications Management — P0 H-02: baca/tulis lamaran wajib verified.
    Route::middleware('verified')->group(function () {
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
    });

    // Bookmarks
    Route::get("/bookmarks", [BookmarkController::class, "index"])->name(
        "bookmarks.index",
    );
    Route::delete("/bookmarks/{bookmark}", [
        BookmarkController::class,
        "destroy",
    ])->name("bookmarks.destroy");

    // CV Builder — P0 H-02: generate wajib verified.
    Route::get("/cv/builder", [CvBuilderController::class, "index"])->name(
        "cv.builder",
    );
    Route::post("/cv/generate", [CvBuilderController::class, "generate"])
        ->middleware(['verified', 'throttle:cv-generate'])
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
    Route::post("/certificates", [CertificateController::class, "store"])->middleware('verified')->name(
        "certificates.store",
    );
    Route::get("/certificates/{certificate}/download", [CertificateController::class, "download"])->name("certificates.download");
    Route::delete("/certificates/{certificate}", [
        CertificateController::class,
        "destroy",
    ])->name("certificates.destroy");

    // Messages — P0 H-02: chat sensitif wajib verified.
    Route::middleware('verified')->group(function () {
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
    });

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
            Route::post("events/{event}/registrants/{registration}/verify", [
                App\Http\Controllers\Admin\EventController::class,
                "verifyPayment",
            ])->name("events.verify-payment");
            Route::post("events/{event}/registrants/{registration}/reject", [
                App\Http\Controllers\Admin\EventController::class,
                "rejectPayment",
            ])->name("events.reject-payment");
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

            // Personal Access Token (Sanctum) untuk admin — P0 H-08: + revoke.
            Route::get("/api-tokens", [
                App\Http\Controllers\Admin\ApiTokenController::class,
                "index",
            ])->name("api-tokens.index");
            Route::post("/api-tokens", [
                App\Http\Controllers\Admin\ApiTokenController::class,
                "store",
            ])->name("api-tokens.store");
            Route::delete("/api-tokens/{tokenId}", [
                App\Http\Controllers\Admin\ApiTokenController::class,
                "destroy",
            ])->name("api-tokens.destroy");
        });
});

// A/B Testing Tracking
Route::post('/ab-test/track', [\App\Http\Controllers\AbTestController::class, 'track'])->name('ab-test.track');

// Debug playground: preview status badges for different status values
// Only registered in local environment — not accessible in production or staging
if (app()->environment('local')) {
    Route::get('/_debug/status-playground', [\App\Http\Controllers\DebugController::class, 'statusPlayground']);
}
