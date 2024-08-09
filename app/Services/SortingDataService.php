<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SortingDataInterface;
use Illuminate\Support\Collection;

class SortingDataService implements SortingDataInterface
{
    private const DEFAULT_SORT_VALUE = 0;

    private const SCORE_INFO_COLUMN = [
        'score',
        'averageScore',
    ];

    public function sorting(Collection $data, string $orderColumn, string $sortOrder): Collection
    {
        $nestingColumn = 'gameStat';

        if (in_array($orderColumn, static::SCORE_INFO_COLUMN)) {
            $nestingColumn = 'seasonScoreInfo';
        }

        return $data->{$sortOrder === 'desc' ? 'sortByDesc' : 'sortBy'}(function ($item) use ($orderColumn, $nestingColumn) {
            return $item['player'][$nestingColumn][$orderColumn] ?? static::DEFAULT_SORT_VALUE;
        });
    }
}
