<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Season Statistics 25/26</h1>
    <div class="w-64">
        <select id="leagues" wire:model.live="league" wire:change="changeLeague" wire:key="league"
                class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option>Select League</option>
            @foreach($leagues as $league)
                <option value="{{ strtolower($league) }}">{{ $league }}</option>
            @endforeach
        </select>
    </div>

    @if(!empty($players))
        <h1 class="text-2xl font-bold mt-8 mb-4">{{ $selected_league }}</h1>

        <div class="mb-4 flex space-x-4">

            <select id="teams" wire:model.live="team" wire:key="team"
                    class="block w-48 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Teams</option>
                @foreach($teams as $team)
                    <option>{{ $team }}</option>
                @endforeach
            </select>

            <select id="roles" wire:model.live="role" wire:key="role"
                    class="block w-48 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}">{{ ucfirst(strtolower($role)) }}</option>
                @endforeach
            </select>

            <label>
                <input
                    type="text"
                    placeholder="Search Players"
                    wire:model.live.debounce.450ms="search"
                    class="block w-48 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                />
            </label>

            <button
                wire:click="resetFilters"
                class="block w-48 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            >
                Reset Filters
            </button>
        </div>

        @if(count($prices) > 0)
            <div class="mb-4 space-x-4 flex flex-wrap items-center">
                <div class="flex items-center mr-4">
                    <label for="min-price" class="text-gray-700 text-sm font-bold mr-2">From</label>
                    <select id="min-price" wire:model.live="minPrice"
                            class="block w-48 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($prices as $price)
                            <option value="{{ $price }}">{{ $price }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center">
                    <label for="max-price" class="text-gray-700 text-sm font-bold mr-2">To</label>
                    <select id="max-price" wire:model.live="maxPrice"
                            class="block w-48 px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($pricesDesc as $price)
                            <option value="{{ $price }}">{{ $price }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <div wire:loading wire:target="minPrice,maxPrice,changeLeague,resetFilters">
            <div class="w-full mb-4 p-5 text-center text-gray-600 bg-green-50 mt-4 shadow-lg rounded-lg">
                Data processing...
            </div>
        </div>

        @if($players->count() > 0)
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Team
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Role
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    wire:click="applySortOrder('score')">
                    Total Score {!! $sortLink !!}
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    wire:click="applySortOrder('averageScore')">
                    Av Score {!! $sortLink !!}
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    wire:click="applySortOrder('goals')">
                    Goal {!! $sortLink !!}
                </th>
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    wire:click="applySortOrder('assists')">
                    Assists {!! $sortLink !!}
                </th>
                @if($selected_role === 'goalkeeper')
                    <th scope="col"
                        class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                        wire:click="applySortOrder('saves')">
                        Saves {!! $sortLink !!}
                    </th>
                @endif
                <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Price
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($players as $key => $player)
                @php
                    $playerInfo=$player['player'];
                    $rowNumber = ($players->currentPage() - 1) * $players->perPage() + $loop->iteration;
                @endphp
                <tr class="{{ $loop->even ? 'bg-gray-100' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $rowNumber }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['name'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['team']['name'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(strtolower($playerInfo['role'])) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['seasonScoreInfo']['score'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['seasonScoreInfo']['averageScore'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ isset($playerInfo['gameStat']) ? $playerInfo['gameStat']['goals'] : 0 }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ isset($playerInfo['gameStat']) ? $playerInfo['gameStat']['assists'] : 0 }}</td>
                    @if($selected_role === 'goalkeeper')
                        <td class="px-6 py-4 whitespace-nowrap">{{ isset($playerInfo['gameStat']) ? $playerInfo['gameStat']['saves'] : 0 }}</td>
                    @endif
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['price'] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

            @else
            <div class="w-full p-5 text-center text-gray-600 bg-red-100 mt-4 shadow-lg rounded-lg">
                <h2 class="font-bold text-2xl">No players data available.</h2>
            </div>
        @endif
    @endif
</div>
