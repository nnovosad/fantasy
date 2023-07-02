<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\JsonDataInterface;
use Illuminate\Support\Collection;

class JsonDataService implements JsonDataInterface
{

    public function getData(?string $file): Collection
    {
        $dataFromFile = json_decode($file, true );

        $seasonData = end($dataFromFile['data']);

        $data = $seasonData['season']['players']['list'];

        return collect($data);
    }
}
