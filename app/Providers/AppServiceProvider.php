<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\LeagueInterface;
use App\Services\LeagueService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LeagueInterface::class, LeagueService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
