<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FiltrationDataInterface;
use Illuminate\Support\Collection;

class FiltrationDataService implements FiltrationDataInterface
{
    public function handler(Collection $data, string $team, string $role, float $minPrice, float $maxPrice): Collection
    {
        $data = $this->filterByPrice($data, $minPrice, $maxPrice);

        if (!empty($team)) {
            $data = $this->filterByTeam($data, $team);
        }

        if (!empty($role)) {
            $data = $this->filterByRole($data, $role);
        }

        return $data;
    }

    private function filterByPrice(Collection $data, float $minPrice, float $maxPrice): Collection
    {
        return $data->filter(function ($item) use ($minPrice, $maxPrice) {
            return !isset($item['newPrice']) || ($item['newPrice'] >= $minPrice && $item['newPrice'] <= $maxPrice);
        });
    }

    private function filterByTeam(Collection $data, string $team): Collection
    {
        return $data->filter(function ($item) use ($team) {
            return $item['player']['team']['name'] === $team;
        });
    }

    private function filterByRole(Collection $data, string $role): Collection
    {
        return $data->filter(function ($item) use ($role) {
            return $item['player']['role'] === $role;
        });
    }
}
