<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface FiltrationDataInterface
{
    public function handler(Collection $data, string $team, string $role): Collection;
}
