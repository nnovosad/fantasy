<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Support\Collection;

interface SearchDataInterface
{
    public function search(Collection $data, string $search): Collection;
}
