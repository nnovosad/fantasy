<?php

namespace App\Http\Livewire;

use App\Contracts\JsonDataInterface;
use App\Contracts\LeagueInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class LastSeasonStats extends Component
{
    private const PAGINATION_COUNT = 15;

    public string $league = '';

    private LeagueInterface $leagueService;

    private JsonDataInterface $jsonData;

    public function boot(LeagueInterface $leagueService, JsonDataInterface $jsonData): void
    {
        $this->leagueService = $leagueService;
        $this->jsonData = $jsonData;
    }

    public function mount(): void
    {
        $this->league = request()->query('league', '');
    }

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $data = null;

        if (!empty($this->league)) {
            $file = $this->leagueService->getFileByLeague($this->league);
            $data = $this->jsonData
                ->getData($file)
                ->paginate(static::PAGINATION_COUNT)
                ->withQueryString();
        }

        return view(
            'livewire.last-season-stats',
            [
                'leagues' => $this->leagueService->getCountries(),
                'selected_league' => ucfirst($this->league),
                'players' => $data,
            ]
        );
    }

    public function changeLeague(): void
    {
        $this->redirect(route('stats', ['league' => $this->league]));
    }
}
