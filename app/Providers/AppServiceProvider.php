<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\JsonDataInterface;
use App\Contracts\LeagueInterface;
use App\Services\JsonDataService;
use App\Services\LeagueService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LeagueInterface::class, LeagueService::class);
        $this->app->singleton(JsonDataInterface::class, JsonDataService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!Collection::hasMacro('paginate')) {
            Collection::macro(
                'paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage)->values()->all(), $this->count(), $perPage, $page, $options
                    ))
                        ->withPath('');
                }
            );
        }
    }
}
