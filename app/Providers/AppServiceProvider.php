<?php

namespace App\Providers;

use App\Models\ExamSession;
use App\Observers\ExamSessionObserver;
use App\Services\BracketAdvancementService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(BracketAdvancementService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.custom');
        \Illuminate\Pagination\Paginator::defaultSimpleView('vendor.pagination.simple-custom');
        ExamSession::observe(ExamSessionObserver::class);
    }
}
