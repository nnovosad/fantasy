<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SortingDataInterface;
use Illuminate\Support\Collection;

class SortingDataService implements SortingDataInterface
{
    public function sorting(Collection $data, string $orderColumn, string $sortOrder): Collection
    {
        return $data->{$sortOrder === 'desc' ? 'sortByDesc' : 'sort'}(function ($item) use ($orderColumn) {
            return $item['player']['gameStat'][$orderColumn] ?? 0;
        });
    }
}
