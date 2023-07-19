<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FiltrationDataInterface;
use Illuminate\Support\Collection;

class FiltrationDataService implements FiltrationDataInterface
{
    public function handler(Collection $data, string $team, string $role, float $minPrice, float $maxPrice): Collection
    {
        $data = $data->filter(function ($item) use ($minPrice, $maxPrice) {
            return !isset($item['newPrice']) || ($item['newPrice'] >= $minPrice && $item['newPrice'] <= $maxPrice);
        });

        if (!$team == "") {
            $data = $data->filter(function ($item) use ($team) {
                return $item['player']['team']['name'] === $team;
            });
        }

        if (!$role == "") {
            $data = $data->filter(function ($item) use ($role) {
                return $item['player']['role'] === $role;
            });
        }

        return $data;
    }
}
