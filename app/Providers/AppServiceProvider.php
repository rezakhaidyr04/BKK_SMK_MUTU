<?php

namespace App\Providers;

use App\Services\ABTestingService;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\ApplicationRepositoryInterface::class,
            \App\Repositories\ApplicationRepository::class
        );

        $this->app->singleton(ABTestingService::class, function () {
            return new ABTestingService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        App::setLocale('id');
        Carbon::setLocale('id');

        Paginator::defaultView('vendor.pagination.bkk');
        Paginator::defaultSimpleView('vendor.pagination.bkk');
    }
}
