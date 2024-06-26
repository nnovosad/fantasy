<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\SearchDataInterface;
use Illuminate\Support\Collection;

class SearchDataService implements SearchDataInterface
{
    public function search(Collection $data, string $search): Collection
    {
        $search = mb_strtolower($search);

        return $data->filter(function ($item) use ($search) {
            $playerName = mb_strtolower($item['player']['name']);
            return str_contains($playerName, $search);
        });
    }
}
