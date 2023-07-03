<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\LeagueInterface;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

class LeagueService implements LeagueInterface
{
    private const DISK_NAME = 'local';

    private const DIRECTORY_NAME = 'fantasy-data';

    /** @var FilesystemAdapter $storage */
    private FilesystemAdapter $storage;

    public function __construct()
    {
        $this->storage = Storage::disk(static::DISK_NAME);
    }

    public function getCountries(): array
    {
        $countries = array_map(function ($file) {
            return $this->prepareCountryName($file);
        }, $this->getFiles());

        sort($countries);

        return $countries;
    }

    private function prepareCountryName(string $file): string
    {
        return ucfirst(
            pathinfo(basename($file), PATHINFO_FILENAME),
        );
    }

    private function getFiles(): array
    {
        return $this->storage->files(static::DIRECTORY_NAME);
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
