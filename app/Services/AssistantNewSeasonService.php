<?php

namespace App\Services;

use App\Contracts\AssistantNewSeasonInterface;
use App\Contracts\LeagueInterface;
use Illuminate\Support\Collection;

class AssistantNewSeasonService implements AssistantNewSeasonInterface
{
    /** Ролевые лимиты */
    private const array ROLE_LIMITS = [
        'GOALKEEPER' => 2,
        'FORWARD' => 3,
        'DEFENDER' => 5,
        'MIDFIELDER' => 5,
    ];

    private Collection $allPlayers;
    private Collection $team;            // выбранные игроки
    private array $roleCount = [];
    private array $teamCount = [];
    private float $budget = 0;

    public function __construct(private readonly LeagueInterface $leagueService)
    {
    }

    public function watson(string $league): array
    {
        $dataFile = $this->leagueService->getFileByLeague($league);

        $this->allPlayers = collect(json_decode($dataFile, true))
            ->pluck('player');

        $this->team = collect();
        $this->roleCount = array_fill_keys(array_keys(self::ROLE_LIMITS), 0);

        /**  Шаги по описанному алгоритму  **/
        $this->pick('FORWARD');
        $this->pick('MIDFIELDER');
        $this->pick('DEFENDER');
        $this->pick('GOALKEEPER');

        $this->pick('FORWARD', 1);   // next best
        $this->pick('MIDFIELDER', 1);
        $this->pick('DEFENDER', 1);

        $this->pickBestCheap('MIDFIELDER');
        $this->pickBestCheap('DEFENDER');
        $this->pickBestCheap('GOALKEEPER');

        $this->pick('FORWARD', null, fn($p) => $p['price'] >= 6.5 &&
            $p['price'] <= 7.5 &&
            $p['price'] <= (100 - $this->budget));

        // Defender <=5 €
        $this->pick('DEFENDER', null, fn($p) => $p['price'] <= 5);

        $cheapestDf = $this->filteredByRole('DEFENDER')->min('price') ?? 0;

        $this->pickRemaining('MIDFIELDER', 1, function ($p) use ($cheapestDf) {
            return $p['price'] <= (100 - $this->budget - $cheapestDf);
        });

        $this->pickRemaining('DEFENDER', 1);
        $this->pickRemaining('MIDFIELDER', 1);

        $roleOrder = [
            'GOALKEEPER' => 1,   // сначала в списке
            'DEFENDER' => 2,
            'MIDFIELDER' => 3,
            'FORWARD' => 4,   // в конце
        ];

        $this->team = $this->team
            ->sort(function ($a, $b) use ($roleOrder) {
                // сначала сравниваем роли
                $roleCmp = $roleOrder[$a['role']] <=> $roleOrder[$b['role']];
                if ($roleCmp !== 0) {
                    return $roleCmp; // разные роли — этого достаточно
                }
                // если роли одинаковые — сортируем по очкам (desc)
                return $b['seasonScoreInfo']['score'] <=> $a['seasonScoreInfo']['score'];
            })
            ->values();

        $additionalInfo = sprintf(
            "Итого: %d игроков   |   Бюджет: %.1f / 100   |   Баллы: %d",
            $this->team->count(),
            $this->budget,
            $this->team->sum('seasonScoreInfo.score')
        );

        return [
            'team' => $this->team->toArray(),
            'additionalInfo' => $additionalInfo,
        ];
    }

    /* ---------------------------------------------------
     |       ВСПОМОГАТЕЛЬНЫЕ МЕТОДЫ
     * --------------------------------------------------*/

    /** Берёт игрока указанной роли.
     *  $skip ‑ сколько лучших уже пропустить (0 по‑умолчанию) */
    private function pick(string $role, ?int $skip = 0, callable $filter = null): void
    {
        if ($this->roleCount[$role] >= self::ROLE_LIMITS[$role]) {
            return; // лимит по роли исчерпан
        }

        $candidate = $this->filteredByRole($role, $filter)
            ->sortByDesc('seasonScoreInfo.score')
            ->skip($skip ?? 0)
            ->first();

        $this->add($candidate);
    }

    /** Затычка: при прочих равных берём подешевле, потом по счёту */
    private function pickBestCheap(string $role): void
    {
        $candidate = $this->filteredByRole($role)
            ->sortBy([
                ['price', 'asc'],
                ['seasonScoreInfo.score', 'desc'],
            ])->first();

        $this->add($candidate);
    }

    /** Выбор оставшихся позиций ролью (top‑score, но следим за бюджетом) */
    private function pickRemaining(string $role, int $needed, callable $filter = null): void
    {
        $candidates = $this->filteredByRole($role, $filter)
            ->sortByDesc('seasonScoreInfo.score');

        foreach ($candidates as $p) {
            if ($needed === 0) {
                break;
            }
            if ($this->add($p)) {
                $needed--;
            }
        }
    }

    /** Фильтр: ещё не взят, подходит по роли, команде ≤2, бюджет ≤100, extra‑условие */
    private function filteredByRole(string $role, callable $extra = null): Collection
    {
        return $this->allPlayers->filter(function ($p) use ($role, $extra) {
            if ($p['role'] !== $role) {
                return false;
            }
            if ($this->team->contains('name', $p['name'])) {
                return false;
            }
            if (($this->teamCount[$p['team']['name']] ?? 0) >= 2) {
                return false;
            }
            if ($this->budget + $p['price'] > 100) {
                return false;
            }
            return $extra ? $extra($p) : true;
        });
    }

    /** Пытается добавить игрока в команду, возвращает true/false */
    private function add(?array $p): bool
    {
        if (!$p) {
            return false;
        }
        if ($this->budget + $p['price'] > 100) {
            return false;
        }

        if (($this->teamCount[$p['team']['name']] ?? 0) >= 2) {
            return false;
        }
        if ($this->team->contains('name', $p['name'])) {
            return false;
        }

        $this->team->push($p);
        $this->roleCount[$p['role']]++;
        $this->teamCount[$p['team']['name']] = ($this->teamCount[$p['team']['name']] ?? 0) + 1;
        $this->budget += $p['price'];

        $this->allPlayers = $this->allPlayers->reject(fn($x) => $x['name'] === $p['name']);

        return true;
    }
}
