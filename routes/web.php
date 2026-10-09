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
use App\Http\Controllers\ReviewController;
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

// Company Profile (Public — bisa diakses semua role termasuk tamu)
Route::get("/companies/{company}", [\App\Http\Controllers\CompanyController::class, "show"])->name("companies.show");

// Events (Public)
Route::get("/events", [EventController::class, "index"])->name("events.index");
Route::get("/events/{event}", [EventController::class, "show"])->name("events.show");

// News (Public)
Route::get("/news", [NewsController::class, "index"])->name("news.index");
Route::get("/news/{news}", [NewsController::class, "show"])->name("news.show");

// Reviews — P0 H-01: store wajib auth (defense-in-depth: route + controller).
Route::get("/reviews/create", [ReviewController::class, "create"])->name("reviews.create");
Route::post("/reviews", [ReviewController::class, "store"])->middleware(['auth', 'throttle:submit-review'])->name("reviews.store");

// Tracer Study — khusus umum, store wajib verified + throttle (sama seperti review).
Route::get("/tracer", [\App\Http\Controllers\TracerStudyController::class, "index"])->middleware('auth')->name("tracer.index");
Route::post("/tracer", [\App\Http\Controllers\TracerStudyController::class, "store"])->middleware(['auth', 'verified', 'throttle:submit-tracer'])->name("tracer.store");

// SEO: Sitemap (cached 1 hour, chunked + select id only)
Route::get("/sitemap.xml", [\App\Http\Controllers\SitemapController::class, "index"])->name("sitemap");

// Halaman statis publik (B1) — constraint ketat agar tidak menelan route lain.
Route::get("/{slug}", [\App\Http\Controllers\PageController::class, "show"])
    ->where("slug", "tentang|faq|privasi|syarat-ketentuan")
    ->name("pages.show");

// Kontak publik (B2) — form + throttle anti spam.
Route::get("/kontak", [\App\Http\Controllers\ContactController::class, "index"])->name("contact.index");
Route::post("/kontak", [\App\Http\Controllers\ContactController::class, "store"])->middleware("throttle:contact")->name("contact.store");

// Lapor lowongan (C1) — khusus umum, auth + throttle (defense-in-depth: route + controller).
Route::post("/jobs/{job}/report", [\App\Http\Controllers\JobReportController::class, "store"])->middleware(['auth', 'throttle:submit-report'])->name("jobs.report");

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
    Route::get("/event-payments/{registration}", [EventController::class, "downloadPaymentProof"])->name("events.payment-proof.download");

    // Profile
    Route::get("/profile", [ProfileController::class, "edit"])->name(
        "profile.edit",
    );
    Route::patch("/profile", [ProfileController::class, "update"])->name(
        "profile.update",
    );
    Route::post("/profile/avatar", [ProfileController::class, "updateAvatar"])->name(
        "profile.avatar.update",
    );
    Route::delete("/profile", [ProfileController::class, "destroy"])->name(
        "profile.destroy",
    );

    // Documents — P0 H-02: upload sensitif wajib verified.
    Route::post("/documents", [\App\Http\Controllers\UserDocumentController::class, "store"])->middleware('verified')->name("documents.store");
    Route::get("/documents/{document}/download", [\App\Http\Controllers\UserDocumentController::class, "download"])->name("documents.download");
    Route::delete("/documents/{document}", [\App\Http\Controllers\UserDocumentController::class, "destroy"])->name("documents.destroy");

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    // L1: mutasi via POST + CSRF (GET dikecualikan CSRF & bisa terpicu
    // prefetch/crawler). poll tetap GET (read-only).
    Route::post('/notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.markAllRead');
    Route::get('/notifications/poll', [\App\Http\Controllers\NotificationController::class, 'poll'])->name('notifications.poll');
    Route::post('/notifications/{id}/go', [\App\Http\Controllers\NotificationController::class, 'go'])->name('notifications.go');

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
        Route::post("/jobs/{job}/publish", [App\Http\Controllers\Company\JobController::class, "publish"])->name("jobs.publish");
        Route::delete("/jobs/{job}", [App\Http\Controllers\Company\JobController::class, "destroy"])->name("jobs.destroy");
        Route::get("/applicants", [App\Http\Controllers\Company\ApplicantController::class, "index"])->name("applicants.index");
        // Export WAJIB di atas /applicants/{application} agar "export" tidak ditangkap binding.
        Route::get("/applicants/export", [App\Http\Controllers\Company\ApplicantController::class, "export"])->name("applicants.export");
        // WA-masking: di atas show agar "contact" tidak ditangkap binding.
        Route::get("/applicants/{application}/contact", [App\Http\Controllers\Company\ApplicantController::class, "contact"])->name("applicants.contact");
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

        Route::get("/applications/{application}/attachment", [
            ApplicationController::class,
            "downloadAttachment",
        ])->name("applications.attachment.download");
        Route::get("/applications/{application}/surat-lamaran", [
            ApplicationController::class,
            "downloadCoverLetter",
        ])->name("applications.cover-letter.download");
        Route::get("/applications/{application}/skck", [
            ApplicationController::class,
            "downloadSkck",
        ])->name("applications.skck.download");

        // D2: konfirmasi kehadiran wawancara oleh pelamar pemilik.
        Route::post("/applications/{application}/interview-confirm", [
            ApplicationController::class,
            "confirmInterview",
        ])->name("applications.interview-confirm");

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

    // E1: Job Alert — preferensi pencari kerja (pola bookmarks: auth saja).
    Route::get("/job-alerts", [\App\Http\Controllers\JobAlertController::class, "index"])->name("job-alerts.index");
    Route::post("/job-alerts", [\App\Http\Controllers\JobAlertController::class, "store"])->name("job-alerts.store");
    Route::patch("/job-alerts/{jobAlert}", [\App\Http\Controllers\JobAlertController::class, "toggle"])->name("job-alerts.toggle");
    Route::delete("/job-alerts/{jobAlert}", [\App\Http\Controllers\JobAlertController::class, "destroy"])->name("job-alerts.destroy");

    // CV Builder — P0 H-02: generate wajib verified.
    Route::get("/cv/builder", [CvBuilderController::class, "index"])->name(
        "cv.builder",
    );
    // Data karier diedit dari halaman CV (satu sumber dengan profil).
    Route::patch("/profile/career", [\App\Http\Controllers\CareerController::class, "update"])->name(
        "career.update",
    );    Route::post("/cv/generate", [CvBuilderController::class, "generate"])
        ->middleware(['verified', 'throttle:cv-generate'])
        ->name("cv.generate");
    // Satu tombol: simpan karier + generate PDF (proteksi sama seperti generate).
    Route::post("/cv/build", [CvBuilderController::class, "build"])
        ->middleware(['verified', 'throttle:cv-generate'])
        ->name("cv.build");
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
            ])->name("jobs.broadcast")->middleware("throttle:broadcast");
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
            Route::get("/reports/export-pdf", [
                App\Http\Controllers\Admin\ReportController::class,
                "exportPdf",
            ])->name("reports.export-pdf");

            // Audit log aktivitas admin
            Route::get("/activities", [
                App\Http\Controllers\Admin\ActivityController::class,
                "index",
            ])->name("activities.index");

            // Hasil ulasan pengguna — read-only untuk admin (lihat saja)
            Route::get("/reviews", [
                App\Http\Controllers\Admin\ReviewController::class,
                "index",
            ])->name("reviews.index");

            // B2: inbox kontak/helpdesk.
            Route::get("/contacts", [
                App\Http\Controllers\Admin\ContactController::class,
                "index",
            ])->name("contacts.index");
            Route::get("/contacts/{contact}", [
                App\Http\Controllers\Admin\ContactController::class,
                "show",
            ])->name("contacts.show");
            Route::post("/contacts/{contact}/replied", [
                App\Http\Controllers\Admin\ContactController::class,
                "markReplied",
            ])->name("contacts.replied");
            Route::delete("/contacts/{contact}", [
                App\Http\Controllers\Admin\ContactController::class,
                "destroy",
            ])->name("contacts.destroy");

            // C1: moderasi laporan lowongan.
            Route::get("/job-reports", [
                App\Http\Controllers\Admin\JobReportController::class,
                "index",
            ])->name("job-reports.index");
            Route::get("/job-reports/{jobReport}", [
                App\Http\Controllers\Admin\JobReportController::class,
                "show",
            ])->name("job-reports.show");
            Route::post("/job-reports/{jobReport}/close", [
                App\Http\Controllers\Admin\JobReportController::class,
                "close",
            ])->name("job-reports.close");
            Route::post("/job-reports/{jobReport}/dismiss", [
                App\Http\Controllers\Admin\JobReportController::class,
                "dismiss",
            ])->name("job-reports.dismiss");
            Route::delete("/job-reports/{jobReport}", [
                App\Http\Controllers\Admin\JobReportController::class,
                "destroy",
            ])->name("job-reports.destroy");

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

// A/B Testing Tracking (public by design, tapi di-throttle anti log-spam)
Route::post('/ab-test/track', [\App\Http\Controllers\AbTestController::class, 'track'])->middleware('throttle:30,1')->name('ab-test.track');

// Master wilayah nasional (dependent dropdown province → city, publik read-only)
Route::get('/wilayah/kota', [\App\Http\Controllers\WilayahController::class, 'cities'])->name('wilayah.cities');
Route::get('/wilayah/kecamatan', [\App\Http\Controllers\WilayahController::class, 'districts'])->name('wilayah.districts');

// Debug playground: preview status badges for different status values
// Only registered in local environment — not accessible in production or staging
if (app()->environment('local')) {
    Route::get('/_debug/status-playground', [\App\Http\Controllers\DebugController::class, 'statusPlayground']);
}

if (app()->environment('local')) { Route::get('/_dbg-prof', function () { auth()->login(App\Models\User::where('role', 'company')->firstOrFail()); return redirect()->route('company.profile.edit'); }); }
