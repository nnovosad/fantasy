<?php

namespace App\Http\Livewire;

use App\Contracts\FiltrationDataInterface;
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

    public string $team = '';
    public string $role = '';

    private LeagueInterface $leagueService;

    private JsonDataInterface $jsonData;

    private FiltrationDataInterface $filtrationData;

    public function boot(
        LeagueInterface $leagueService,
        JsonDataInterface $jsonData,
        FiltrationDataInterface $filtrationData
    ): void
    {
        $this->leagueService = $leagueService;
        $this->jsonData = $jsonData;
        $this->filtrationData = $filtrationData;
    }

    public function mount(): void
    {
        $this->league = request()->query('league', '');
        $this->team = request()->query('team', '');
        $this->role = request()->query('role', '');
    }

    public function render(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $playersData = null;
        $teamsData = null;
        $rolesData = null;

        if (!empty($this->league)) {
            $leagueFile = $this->leagueService->getFileByLeague($this->league);

            if ($leagueFile !== null) {
                $playersData = $this->jsonData
                    ->getData($leagueFile);

                $playersData = $this->filtrationData
                    ->handler($playersData, $this->team, $this->role);

                $playersData = $playersData->paginate(static::PAGINATION_COUNT)
                    ->withQueryString();

                $teamsData = $this->jsonData
                    ->getTeams($leagueFile);

                $rolesData = $this->jsonData
                    ->getRoles($leagueFile);
            }
        }

        return $this->buildView($playersData, $teamsData, $rolesData);
    }

    private function buildView($playersData, $teamsData, $rolesData) : View
    {
        return view(
            'livewire.last-season-stats',
            [
                'leagues' => $this->leagueService->getCountries(),
                'selected_league' => ucfirst($this->league),
                'players' => $playersData,
                'teams' => $teamsData,
                'roles' => $rolesData,
            ]
        );
    }

    public function changeLeague(): void
    {
        $this->redirect(
            route(
                'stats',
                [
                    'league' => $this->league,
                    'team' => $this->team,
                    'role' => $this->role,
                ],
            )
        );
    }

    public function changeFilter(): void
    {
        $this->redirect(
            route(
                'stats',
                [
                    'team' => $this->team,
                    'league' => $this->league,
                    'role' => $this->role,
                ],
            )
        );
    }
}
