<?php

namespace App\Http\Livewire;

use App\Contracts\FiltrationDataInterface;
use App\Contracts\JsonDataInterface;
use App\Contracts\LeagueInterface;
use App\Contracts\SearchDataInterface;
use App\Contracts\SortingDataInterface;
use App\Contracts\StartingDataInterface;
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

    public string $search = '';

    private LeagueInterface $leagueService;

    private JsonDataInterface $jsonData;

    private FiltrationDataInterface $filtrationData;

    private SortingDataInterface $sortingData;

    private SearchDataInterface $searchService;

    private StartingDataInterface $startingData;

    public function boot(
        LeagueInterface $leagueService,
        JsonDataInterface $jsonData,
        FiltrationDataInterface $filtrationData,
        SortingDataInterface $sortingData,
        SearchDataInterface $searchService,
        StartingDataInterface $startingData
    ): void
    {
        $this->leagueService = $leagueService;
        $this->jsonData = $jsonData;
        $this->filtrationData = $filtrationData;
        $this->sortingData = $sortingData;
        $this->searchService = $searchService;
        $this->startingData = $startingData;
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
        $pricesData = null;

        if (!empty($this->league)) {
            $leagueFile = $this->leagueService->getFileByLeague($this->league);

            if ($leagueFile !== null) {
                $playersData = $this->jsonData
                    ->getData($leagueFile);

                $playersData = $this->filtrationData
                    ->handler($playersData, $this->team, $this->role);

                if ($this->search !== "") {
                    $playersData = $this->searchService
                        ->search($playersData, $this->search);
                }

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

                $startingFile = $this->startingData
                    ->getFileByLeague($this->league);

                $pricesData = $this->jsonData
                    ->getPrices($startingFile);
            }
        }

        return $this->buildView($playersData, $teamsData, $rolesData, $pricesData);
    }

    private function buildView($playersData, $teamsData, $rolesData, $pricesData) : View
    {
        return view(
            'livewire.last-season-stats',
            [
                'leagues' => $this->leagueService->getCountries(),
                'selected_league' => ucfirst($this->league),
                'players' => $playersData,
                'teams' => $teamsData,
                'roles' => $rolesData,
                'prices' => $pricesData,
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
                    'team' => '',
                    'role' => '',
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

    public function resetFilters(): void
    {
        $this->redirect(
            route(
                'stats',
                [
                    'league' => $this->league,
                ],
            )
        );
    }
}
