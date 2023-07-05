<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FiltrationDataInterface;
use Illuminate\Support\Collection;

class FiltrationDataService implements FiltrationDataInterface
{
    public function handler(Collection $data, string $team, string $role): Collection
    {
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
