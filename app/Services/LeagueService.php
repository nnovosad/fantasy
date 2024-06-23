<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\LeagueInterface;
use Illuminate\Contracts\Filesystem\Factory as StorageFactory;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemAdapter;

class LeagueService implements LeagueInterface
{
    private const DIRECTORY_NAME = 'fantasy-data/completed/22-23';

    private FilesystemAdapter $storage;

    public function __construct(StorageFactory $storageFactory)
    {
        $this->storage = $storageFactory->disk('local');
    }

    public function getCountries(): array
    {
        $files = $this->getFiles();
        $countries = array_map([$this, 'prepareCountryName'], $files);
        sort($countries);

        return $countries;
    }

    private function prepareCountryName(string $file): string
    {
        return ucfirst(pathinfo(basename($file), PATHINFO_FILENAME));
    }

    private function getFiles(): array
    {
        return $this->storage->files(self::DIRECTORY_NAME);
    }

    public function getFileByLeague(string $league): ?string
    {
        return $this->storage->get($this->preparePath($league));
    }

    private function preparePath(string $league): string
    {
        return sprintf('%s/%s.json', self::DIRECTORY_NAME, strtolower($league));
    }
}
