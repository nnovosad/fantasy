<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface JsonDataInterface
{
    public function getData(string $file): Collection;

    public function getTeams(string $file): array;

    public function getRoles(string $file): array;

    public function getPrices(?string $file): array;
}
