<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Assistant for start season 25/26</h1>
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

        <div> {{ $additional_info }} </div>

        <div class="mb-4 space-x-4">
            <!-- Основное содержимое -->
            <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Имя
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Цена
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Роль
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Команда
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Очки сезона
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($players as $player)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div
                                                    class="text-sm font-medium text-gray-900">{{ $player['name'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ number_format($player['price'], 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $player['role'] === 'admin' ? 'bg-purple-100 text-purple-800' :
                                                   ($player['role'] === 'manager' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ $player['role'] }}
                                            </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $player['team']['name'] ?? 'Нет команды' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                            {{ $player['seasonScoreInfo']['score'] >= 80 ? 'text-green-600' :
                                               ($player['seasonScoreInfo']['score'] >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $player['seasonScoreInfo']['score'] ?? 0 }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
@endif
