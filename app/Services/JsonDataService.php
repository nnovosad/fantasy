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

    public function getData(string $file): Collection
    {
        return collect($this->getDecodedJsonData($file));
    }

    private function getDecodedJsonData(?string $file): array
    {
        $dataFromFile = json_decode($file, true);
        $seasonData = end($dataFromFile['data']);
        return $seasonData['season']['players']['list'];
    }

    public function getTeams(string $file): array
    {
        $teams = array_map(fn ($player) => $player['player']['team']['name'], $this->getDecodedJsonData($file));

        return $this->sortAndUniqueArray($teams);
    }

    public function getRoles(string $file): array
    {
        $roles = array_map(fn ($player) => $player['player']['role'], $this->getDecodedJsonData($file));

        return $this->uniqueAndSortRoles($roles);
    }

    public function getPrices(?string $file): array
    {
        if (is_null($file)) {
            return [];
        }

        $prices = array_map(fn ($player) => $player['player']['price'], $this->getDecodedJsonData($file));

        return $this->sortAndUniqueArray($prices);
    }

    private function uniqueAndSortRoles(array $roles): array
    {
        $roles = array_unique($roles);

        usort($roles, fn($a, $b) => array_search($a, static::PATTERN_ROLES) <=> array_search($b, static::PATTERN_ROLES));

        return $roles;
    }

    private function sortAndUniqueArray(array $data): array
    {
        $data = array_unique($data);

        sort($data);

        return $data;
    }
}
