<?php

namespace App\Http\Livewire;

use App\Contracts\FiltrationDataInterface;
use App\Contracts\JsonDataInterface;
use App\Contracts\LeagueInterface;
use App\Contracts\SortingDataInterface;
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

    public string $orderColumn = '';
    public string $sortOrder = 'desc';
    public string $sortLink = '<i class="sorticon fa-solid fa-caret-up"></i>';

    private LeagueInterface $leagueService;

    private JsonDataInterface $jsonData;

    private FiltrationDataInterface $filtrationData;

    private SortingDataInterface $sortingData;

    public function boot(
        LeagueInterface $leagueService,
        JsonDataInterface $jsonData,
        FiltrationDataInterface $filtrationData,
        SortingDataInterface $sortingData
    ): void
    {
        $this->leagueService = $leagueService;
        $this->jsonData = $jsonData;
        $this->filtrationData = $filtrationData;
        $this->sortingData = $sortingData;
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

                if ($this->orderColumn !== "") {
                    $playersData = $this->sortingData
                        ->sorting($playersData, $this->orderColumn, $this->sortOrder);
                }

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

    public function sortOrder($columnName = ""): void
    {
        if ($this->sortOrder == 'asc') {
            $this->sortOrder = 'desc';
            $caretOrder = "down";
        } else {
            $this->sortOrder = 'asc';
            $caretOrder = "up";
        }

        $this->sortLink = '<i class="sorticon fa-solid fa-caret-'.$caretOrder.'"></i>';

        $this->orderColumn = $columnName;
    }
}
