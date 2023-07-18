<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SortingDataInterface;
use Illuminate\Support\Collection;

class SortingDataService implements SortingDataInterface
{
    public function sorting(Collection $data, string $orderColumn, string $sortOrder): Collection
    {
        $prepareSort = 'sort';

        if ($sortOrder === 'desc') {
            $prepareSort = 'sortByDesc';
        }

        return $data->{$prepareSort}(function ($item) use ($orderColumn) {
            return $item['player']['gameStat'][$orderColumn] ?? 0;
        });
    }
}
