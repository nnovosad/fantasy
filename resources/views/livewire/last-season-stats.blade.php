<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Season Statistics 22/23</h1>
    <div class="w-64">
        <select id="leagues" wire:model="league" wire:change="changeLeague" wire:key="league"
                class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option>Select League</option>
            @foreach($leagues as $league)
                <option value="{{ strtolower($league) }}">{{ $league }}</option>
            @endforeach
        </select>
    </div>

    @if(!empty($players))
        <h1 class="text-2xl font-bold mt-8 mb-4">{{ $selected_league }}</h1>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
            <tr>
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
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Total Score
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($players as $player)
                @php $playerInfo=$player['player']; @endphp
{{--                {{ dd($player) }}--}}
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['name'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['team']['name'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst(strtolower($playerInfo['role'])) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $playerInfo['seasonScoreInfo']['score'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $players->links('pagination-links') }}
    @endif
</div>
