<?php

namespace App\Contracts;

interface AssistantNewSeasonInterface
{
    public function watson(string $league): array;
}
