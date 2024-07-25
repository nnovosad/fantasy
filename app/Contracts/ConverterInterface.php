<?php

declare(strict_types=1);

namespace App\Contracts;

interface ConverterInterface
{
    public function handler(string $league): void;
}
