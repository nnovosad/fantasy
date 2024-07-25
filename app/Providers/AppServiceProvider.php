<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\ConverterInterface;
use App\Contracts\FiltrationDataInterface;
use App\Contracts\JsonDataInterface;
use App\Contracts\LeagueInterface;
use App\Contracts\SearchDataInterface;
use App\Contracts\SortingDataInterface;
use App\Contracts\StartingDataInterface;
use App\Services\ConverterService;
use App\Services\FiltrationDataService;
use App\Services\JsonDataService;
use App\Services\LeagueService;
use App\Services\SearchDataService;
use App\Services\SortingDataService;
use App\Services\StartingDataService;
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
        $this->app->singleton(FiltrationDataInterface::class, FiltrationDataService::class);
        $this->app->singleton(SortingDataInterface::class, SortingDataService::class);
        $this->app->singleton(SearchDataInterface::class, SearchDataService::class);
        $this->app->singleton(StartingDataInterface::class, StartingDataService::class);

        $this->app->singleton(ConverterInterface::class, ConverterService::class);
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
