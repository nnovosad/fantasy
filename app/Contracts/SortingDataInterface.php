<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface SortingDataInterface
{
    public function sorting(Collection $data, string $orderColumn, string $sortOrder): Collection;
}
