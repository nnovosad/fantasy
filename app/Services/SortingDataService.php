<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SortingDataInterface;
use Illuminate\Support\Collection;

class SortingDataService implements SortingDataInterface
{
    private const DEFAULT_SORT_VALUE = 0;

    public function sorting(Collection $data, string $orderColumn, string $sortOrder): Collection
    {
        $nestingColumn = 'gameStat';

        if ($orderColumn === 'score') {
            $nestingColumn = 'seasonScoreInfo';
        }

        return $data->{$sortOrder === 'desc' ? 'sortByDesc' : 'sortBy'}(function ($item) use ($orderColumn, $nestingColumn) {
            return $item['player'][$nestingColumn][$orderColumn] ?? static::DEFAULT_SORT_VALUE;
        });
    }
}
