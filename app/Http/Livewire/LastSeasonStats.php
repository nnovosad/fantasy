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
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class LastSeasonStats extends Component
{
    private const PAGINATION_COUNT = 15;

    public string $league = '';
    public string $team = '';
    public string $role = '';
    public string $orderColumn = '';
    public string $sortOrder = 'asc';
    public string $sortLink = '<i class="sorticon fa-solid fa-caret-down"></i>';
    public string $search = '';
    public float $minPrice = 0;
    public float $maxPrice = 100;

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
    ) {
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
        return $this->league ?
            $this->prepareViewWithData() :
            $this->buildView(null, null, null, []);
    }

    private function prepareViewWithData(): View
    {
        $leagueFile = $this->leagueService->getFileByLeague($this->league);
        $startingFile = $this->startingData->getFileByLeague($this->league);

        $playersData = $leagueFile ? $this->jsonData->getData($leagueFile) : null;

        $playersData = $this->preparePlayerData($playersData, $startingFile);
        $teamsData = $leagueFile ? $this->jsonData->getTeams($leagueFile) : null;
        $rolesData = $leagueFile ? $this->jsonData->getRoles($leagueFile) : null;
        $pricesData = $this->jsonData->getPrices($startingFile);

        return $this->buildView($playersData, $teamsData, $rolesData, $pricesData);
    }

    private function preparePlayerData($playersData, $startingFileData)
    {
        if ($playersData && $startingFileData) {
            $playersData = $this->startingData->addNewPrice($playersData, $startingFileData);
        }

        if ($playersData) {
            $playersData = $this->filtrationData->handler($playersData, $this->team, $this->role, $this->minPrice, $this->maxPrice);
        }

        if ($playersData && $this->search !== "") {
            $playersData = $this->searchService->search($playersData, $this->search);
        }

        if ($playersData && $this->orderColumn !== "") {
            $playersData = $this->sortingData->sorting($playersData, $this->orderColumn, $this->sortOrder);
        }

        if ($playersData) {
            return $playersData->paginate(static::PAGINATION_COUNT)->withQueryString();
        }
    }

    private function buildView(?LengthAwarePaginator $playersData, ?array $teamsData, ?array $rolesData, ?array $pricesData) : View
    {
        $pricesDataDesc = $pricesData;
        rsort($pricesDataDesc, SORT_NUMERIC);

        return view(
            'livewire.last-season-stats',
            [
                'leagues' => $this->leagueService->getCountries(),
                'selected_league' => ucfirst($this->league),
                'players' => $playersData,
                'teams' => $teamsData,
                'roles' => $rolesData,
                'prices' => $pricesData,
                'pricesDesc' => $pricesDataDesc,
            ]
        );
    }

    public function changeLeague(): void
    {
        $this->redirect(route('stats', ['league' => $this->league]));
    }

    public function changeFilter(): void
    {
        $routeParameters = ['league' => $this->league];

        if (!empty($this->team)) {
            $routeParameters['team'] = $this->team;
        }

        if (!empty($this->role)) {
            $routeParameters['role'] = $this->role;
        }

        $this->redirect(route('stats', $routeParameters));
    }

    public function sortOrder($columnName = ""): void
    {
        $this->sortOrder = $this->sortOrder == 'desc' ? 'asc' : 'desc';

        $this->sortLink = '<i class="sorticon fa-solid fa-caret-' . ($this->sortOrder == 'asc' ? 'up' : 'down') . '"></i>';
        $this->orderColumn = $columnName;
    }

    public function resetFilters(): void
    {
        $this->redirect(route('stats', ['league' => $this->league,]));
    }
}
