<?php

declare(strict_types=1);

namespace App\Contracts;

interface LeagueInterface
{
    public function getCountries(): array;

    public function getFileByLeague(string $league): ?string;
}
