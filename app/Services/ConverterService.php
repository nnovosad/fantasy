<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ConverterInterface;
use Illuminate\Contracts\Filesystem\Factory as StorageFactory;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemAdapter;

class ConverterService implements ConverterInterface
{
    private const DIRECTORY_NAME = 'fantasy-data';

    public function __construct(private readonly StorageFactory $storageFactory, private FilesystemAdapter $storage)
    {
        $this->storage = $this->storageFactory->disk('local');
    }

    public function handler(string $league): void
    {
        $completedDataFile = $this->getFile($league, 'completed/24-25');
        $startingDataFile = $this->getFile($league, 'starting/25-26');

        $completedDataFile = json_decode($completedDataFile, true);
        $completedDataFile = end($completedDataFile['data']);
        $completedDataFile = $completedDataFile['season']['players']['list'];

        $startingDataFile = json_decode($startingDataFile, true);
        $startingDataFile = end($startingDataFile['data']);
        $startingDataFile = $startingDataFile['season']['players']['list'];

        $preparedData = [];

        foreach ($completedDataFile as $data) {
            $playerName = $data['player']['name'];

            $searchedPlayerData = $this->searchPlayer($startingDataFile, $playerName);
            if (!$searchedPlayerData) {
                continue;
            }

            $newPrice = $searchedPlayerData['player']['price'];
            $data['player']['price'] = $newPrice;

            $data['player']['team']['name'] = $searchedPlayerData['player']['team']['name'];
            $data['player']['role'] = $searchedPlayerData['player']['role'];

            $preparedData[] = $data;
        }

        $this->putData($preparedData, $league);
    }

    private function getFile(string $league, string $path): string
    {
        return $this->storage->get($this->preparePath($league, $path));
    }

    private function preparePath(string $league, string $path): string
    {
        return sprintf('%s/%s/%s.json', self::DIRECTORY_NAME, $path, strtolower($league));
    }

    private function searchPlayer(array $data, string $value): ?array
    {
            return collect($data)->firstWhere('player.name', $value);
    }

    private function putData(array $data, string $league): void
    {
        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
        $file = sprintf('%s/%s/%s.json', self::DIRECTORY_NAME, 'converted/25-26', strtolower($league));

        $this->storage->put($file, $jsonData);
    }
}
