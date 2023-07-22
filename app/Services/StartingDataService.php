<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\StartingDataInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class StartingDataService implements StartingDataInterface
{
    private const DISK_NAME = 's3';

    private const DIRECTORY_NAME = 'fantasy-data/starting/23-24';

    private FilesystemAdapter $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(static::DISK_NAME);
    }

    public function getFileByLeague(string $league): ?string
    {
        return $this->storage->get($this->preparePath($league));
    }

    private function preparePath(string $league): string
    {
        return sprintf(
            '%s/%s.json',
            static::DIRECTORY_NAME,
            strtolower($league),
        );
    }

    public function addNewPrice(Collection $data, string $newFile): Collection
    {
        $newData = collect($this->getData($newFile));

        foreach ($data as $key => $item) {
            $name = $item['player']['name'];

            $foundData = $newData->filter(function ($player) use ($name) {
                $playerName = mb_strtolower($player['player']['name']);
                $search = mb_strtolower($name);

                return $playerName === $search;
            });

//            if (!$foundData->isEmpty()) {
                $newPrice = $foundData->values()->toArray() ? $foundData->values()->toArray()[0]['player']['price'] : 0;
                $data[$key] = array_merge($data[$key], ['newPrice' => $newPrice]);
//            }
        }

        return $data;
    }

    private function getData(string $file): array
    {
        $dataFromFile = json_decode($file, true );
        $seasonData = end($dataFromFile['data']);

        return $seasonData['season']['players']['list'];
    }
}
