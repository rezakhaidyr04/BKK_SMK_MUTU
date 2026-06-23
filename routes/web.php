<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CvBuilderController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get("/", function () {
    $jobs = App\Models\Job::with("company.user")
        ->where("status", "active")
        ->where("deadline", ">=", now())
        ->latest()
        ->take(12)
        ->get();
    $activeJobsCount = App\Models\Job::where("status", "active")
        ->where("deadline", ">=", now())
        ->count();

    $studentsCount = App\Models\User::whereIn("role", [
        "student",
        "alumni",
    ])->count();

    $companiesCount = App\Models\Company::count();
    return view(
        "welcome",
        compact("jobs", "activeJobsCount", "studentsCount", "companiesCount"),
    );
})->name("home");

// Job Routes (Public)
Route::get("/jobs", [JobController::class, "index"])->name("jobs.index");
Route::get("/jobs/{job}", [JobController::class, "show"])->name("jobs.show");

// Events (Public)
Route::get("/events", [EventController::class, "index"])->name("events.index");
Route::get("/events/{event}", [EventController::class, "show"])->name(
    "events.show",
);

// News (Public)
Route::get("/news", [NewsController::class, "index"])->name("news.index");
Route::get("/news/{news}", [NewsController::class, "show"])->name("news.show");

// Auth Routes
require __DIR__ . "/auth.php";

// Authenticated Routes
Route::middleware(["auth", "verified"])->group(function () {
    // Dashboard
    Route::get("/dashboard", [DashboardController::class, "index"])->name(
        "dashboard",
    );

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

    // Job Applications
    Route::post("/jobs/{job}/apply", [JobController::class, "apply"])->name(
        "jobs.apply",
    );
    Route::post("/jobs/{job}/bookmark", [
        JobController::class,
        "bookmark",
    ])->name("jobs.bookmark");

    // Applications Management
    Route::get("/applications", [ApplicationController::class, "index"])->name(
        "applications.index",
    );
    Route::get("/applications/{application}", [
        ApplicationController::class,
        "show",
    ])->name("applications.show");
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
    Route::post("/cv/generate", [CvBuilderController::class, "generate"])->name(
        "cv.generate",
    );
    Route::get("/cv/download/{cvFile}", [
        CvBuilderController::class,
        "download",
    ])->name("cv.download");

    // Certificates
    Route::get("/certificates", [CertificateController::class, "index"])->name(
        "certificates.index",
    );
    Route::post("/certificates", [CertificateController::class, "store"])->name(
        "certificates.store",
    );
    Route::delete("/certificates/{certificate}", [
        CertificateController::class,
        "destroy",
    ])->name("certificates.destroy");

    // Messages
    Route::get("/messages", [MessageController::class, "index"])->name(
        "messages.index",
    );
    Route::get("/messages/{conversation}", [
        MessageController::class,
        "show",
    ])->name("messages.show");
    Route::post("/messages/{conversation}", [
        MessageController::class,
        "send",
    ])->name("messages.send");

    // Company Routes
    Route::middleware("role:company")
        ->prefix("company")
        ->name("company.")
        ->group(function () {
            Route::resource(
                "jobs",
                App\Http\Controllers\Company\JobController::class,
            )->except(["show"]);
            Route::post("applications/{application}/status", [
                App\Http\Controllers\Company\ApplicantController::class,
                "updateStatus",
            ])->name("applicants.updateStatus");
            Route::post("applications/{application}/offer", [
                App\Http\Controllers\Company\OfferController::class,
                "send",
            ])->name("applicants.sendOffer");
            Route::get("/applicants", [
                App\Http\Controllers\Company\ApplicantController::class,
                "index",
            ])->name("applicants.index");
            Route::get("/profile", [
                App\Http\Controllers\Company\ProfileController::class,
                "edit",
            ])->name("profile.edit");
            Route::put("/profile", [
                App\Http\Controllers\Company\ProfileController::class,
                "update",
            ])->name("profile.update");
        });

    // Admin Routes
    Route::middleware("role:admin")
        ->prefix("admin")
        ->name("admin.")
        ->group(function () {
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
                "companies",
                App\Http\Controllers\Admin\CompanyController::class,
            )->only(["index", "show", "edit", "update", "destroy"]);
            Route::post("companies/{company}/approve", [
                App\Http\Controllers\Admin\CompanyController::class,
                "approve",
            ])->name("companies.approve");
            Route::post("companies/{company}/reject", [
                App\Http\Controllers\Admin\CompanyController::class,
                "reject",
            ])->name("companies.reject");
            Route::resource(
                "jobs",
                App\Http\Controllers\Admin\JobController::class,
            )->only(["index", "show", "edit", "update", "destroy"]);
            Route::resource(
                "news",
                App\Http\Controllers\Admin\NewsController::class,
            )->except(["show"]);
            Route::resource(
                "events",
                App\Http\Controllers\Admin\EventController::class,
            )->except(["show"]);
            Route::get("/reports", [
                App\Http\Controllers\Admin\ReportController::class,
                "index",
            ])->name("reports.index");
            Route::get("/reports/export", [
                App\Http\Controllers\Admin\ReportController::class,
                "export",
            ])->name("reports.export");
        });
});
