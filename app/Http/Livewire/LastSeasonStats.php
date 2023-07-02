<?php

namespace App\Http\Livewire;

use App\Contracts\LeagueInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class LastSeasonStats extends Component
{
    public string $league = '';

    public string $data = '';

    private LeagueInterface $leagueService;

    public function boot(LeagueInterface $leagueService): void
    {
        $this->leagueService = $leagueService;
    }

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view(
            'livewire.last-season-stats',
            [
                'leagues' => $this->leagueService->getCountries(),
                'data' => $this->data,
            ]
        );
    }

    public function changeLeague(): void
    {
        $this->data = $this->leagueService->getFileByLeague($this->league);
    }
}
