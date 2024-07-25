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
        $completedDataFile = $this->getFile($league, 'completed/23-24');
        $startingDataFile = $this->getFile($league, 'starting/24-25');
    }

    private function getFile(string $league, string $path): string
    {
        return $this->storage->get($this->preparePath($league, $path));
    }

    private function preparePath(string $league, string $path): string
    {
        return sprintf('%s/%s/%s.json', self::DIRECTORY_NAME, $path, strtolower($league));
    }
}
