<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\StartingDataInterface;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StartingDataService implements StartingDataInterface
{
    private const DISK_NAME = 's3';
    private const DIRECTORY_NAME = 'fantasy-data/starting/23-24';
    private const PRICE_FIELD = 'newPrice';
    private const PLAYER_FIELD = 'player';
    private const NAME_FIELD = 'name';
    private const PRICE_FIELD_D = 'price';

    private FilesystemContract $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(self::DISK_NAME);
    }

    public function getFileByLeague(string $league): ?string
    {
        return $this->storage->get($this->preparePath($league));
    }

    private function preparePath(string $league): string
    {
        return sprintf('%s/%s.json', self::DIRECTORY_NAME, Str::lower($league));
    }

    public function addNewPrice(Collection $data, string $newFile): Collection
    {
        return $data->map(function ($item, $key) use ($newFile) {
            $playerName = $item[self::PLAYER_FIELD][self::NAME_FIELD];
            $newPrice = $this->getNewPriceForPlayer($playerName, $this->getData($newFile));

            return array_merge($item, [self::PRICE_FIELD => $newPrice]);
        });
    }

    private function getNewPriceForPlayer(string $playerName, array $data) : float
    {
        $foundPlayer = $this->findPlayerInData($playerName, $data);

        return $foundPlayer ? $foundPlayer[self::PLAYER_FIELD][self::PRICE_FIELD_D] : 0;
    }

    private function findPlayerInData(string $playerName, array $data): ?array
    {
        $normalizedPlayerName = Str::lower($playerName);

        return collect($data)->first(function ($player) use ($normalizedPlayerName){
            return Str::lower($player[self::PLAYER_FIELD][self::NAME_FIELD]) === $normalizedPlayerName;
        });
    }

    private function getData(string $file): array
    {
        $dataFromFile = json_decode($file, true);

        return end($dataFromFile['data'])['season']['players']['list'];
    }
}
