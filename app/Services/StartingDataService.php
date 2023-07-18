<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\StartingDataInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class StartingDataService implements StartingDataInterface
{
    private const DISK_NAME = 'local';

    private const DIRECTORY_NAME = 'fantasy-data/starting/23-24';

    private FilesystemAdapter $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(static::DISK_NAME);
    }

    public function getPriceByPlayer(string $player, string $league): int
    {
        // TODO: Implement getPriceByPlayer() method.
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
}
