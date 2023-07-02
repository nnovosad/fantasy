<?php

namespace App\Http\Livewire;

use App\Contracts\JsonDataInterface;
use App\Contracts\LeagueInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Livewire\Component;

class LastSeasonStats extends Component
{
    public string $league = '';

    public ?Collection $data = null;

    private LeagueInterface $leagueService;

    private JsonDataInterface $jsonData;

    public function boot(LeagueInterface $leagueService, JsonDataInterface $jsonData): void
    {
        $this->leagueService = $leagueService;
        $this->jsonData = $jsonData;
    }

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view(
            'livewire.last-season-stats',
            [
                'leagues' => $this->leagueService->getCountries(),
                'selected_league' => $this->league,
                'data' => !is_null($this->data) ? $this->data->paginate(15) : collect(),
            ]
        );
    }

    public function changeLeague(): void
    {
        $file = $this->leagueService->getFileByLeague($this->league);

        $this->data = $this->jsonData->getData($file);
    }
}
