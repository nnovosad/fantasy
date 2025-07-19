<?php

namespace App\Livewire;

use App\Contracts\AssistantNewSeasonInterface;
use App\Contracts\LeagueInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class AssistantNewSeason extends Component
{
    public string $league = '';

    private LeagueInterface $leagueService;
    private AssistantNewSeasonInterface $assistantNewSeason;

    public function boot(
        LeagueInterface $leagueService,
        AssistantNewSeasonInterface $assistantNewSeason
    ): void {
        $this->leagueService = $leagueService;
        $this->assistantNewSeason = $assistantNewSeason;
    }

    public function mount(): void
    {
        $this->league = request()->query('league', '');
    }

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $players = $this->assistantNewSeason->watson($this->league);

        return view(
            'livewire.assistant-new-season',
            [
                'leagues' => $this->leagueService->getCountries(),
                'selected_league' => ucfirst($this->league),
                'players' => $players,
            ]
        );
    }

    public function changeLeague(): void
    {
        $this->redirect(route('assistant-new-season', ['league' => $this->league]));
    }
}
