<?php

declare(strict_types=1);

namespace App\Contracts;

interface StartingDataInterface
{
    public function getPriceByPlayer(string $player, string $league): int;

    public function getFileByLeague(string $league): ?string;
}
