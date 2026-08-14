<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Job;
use App\Models\Application;
use App\Models\Student;
use App\Models\Company;
use App\Models\Certificate;
use App\Models\CvFile;
use App\Models\UserDocument;
use App\Policies\JobPolicy;
use App\Policies\ApplicationPolicy;
use App\Policies\StudentPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CertificatePolicy;
use App\Policies\CvFilePolicy;
use App\Policies\UserDocumentPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Job::class => JobPolicy::class,
        Application::class => ApplicationPolicy::class,
        Student::class => StudentPolicy::class,
        Company::class => CompanyPolicy::class,
        Certificate::class => CertificatePolicy::class,
        CvFile::class => CvFilePolicy::class,
        UserDocument::class => UserDocumentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
