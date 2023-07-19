<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface StartingDataInterface
{
    public function getFileByLeague(string $league): ?string;

    public function addNewPrice(Collection $data, string $newFile): Collection;
}
