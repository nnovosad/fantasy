<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\JsonDataInterface;
use Illuminate\Support\Collection;

class JsonDataService implements JsonDataInterface
{
    private const PATTERN_ROLES  = [
        'GOALKEEPER',
        'DEFENDER',
        'MIDFIELDER',
        'FORWARD',
    ];

    private function getSeasonData(?string $file): array
    {
        $dataFromFile = json_decode($file, true );
        $seasonData = end($dataFromFile['data']);
        return $seasonData['season']['players']['list'];
    }

    public function getData(string $file): Collection
    {
        return collect($this->getSeasonData($file));
    }

    public function getTeams(string $file): array
    {
        $teams = [];
        foreach ($this->getSeasonData($file) as $player) {
            $teams[] = $player['player']['team']['name'];
        }

        $teams = array_unique($teams);

        sort($teams);

        return $teams;
    }

    public function getRoles(string $file): array
    {
        $roles = [];
        foreach ($this->getSeasonData($file) as $player) {
            $roles[] = $player['player']['role'];
        }

        $roles = array_unique($roles);

        usort($roles, fn($a, $b) => array_search($a, static::PATTERN_ROLES) <=> array_search($b, static::PATTERN_ROLES));

        return $roles;
    }

    public function getPrices(string $file): array
    {
        $prices = [];
        foreach ($this->getSeasonData($file) as $player) {
            $prices[] = $player['player']['price'];
        }

        $prices = array_unique($prices);

        sort($prices);

        return $prices;
    }
}
