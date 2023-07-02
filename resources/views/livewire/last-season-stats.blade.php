<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Season Statistics 22/23</h1>

    <div class="w-64">
        <select id="leagues" wire:model="league" wire:change="changeLeague" class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            <option>Select League</option>
            @foreach($leagues as $league)
                <option value="{{ $league }}">{{ $league }}</option>
            @endforeach
        </select>
    </div>

    <h1 class="text-2xl font-bold mt-8 mb-4">Table Example</h1>
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
        <tr>
            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">John Doe</td>
            <td class="px-6 py-4 whitespace-nowrap">john.doe@example.com</td>
            <td class="px-6 py-4 whitespace-nowrap">Admin</td>
        </tr>
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">Jane Smith</td>
            <td class="px-6 py-4 whitespace-nowrap">jane.smith@example.com</td>
            <td class="px-6 py-4 whitespace-nowrap">User</td>
        </tr>
        <tr>
            <td class="px-6 py-4 whitespace-nowrap">Mark Johnson</td>
            <td class="px-6 py-4 whitespace-nowrap">mark.johnson@example.com</td>
            <td class="px-6 py-4 whitespace-nowrap">User</td>
        </tr>
        </tbody>
    </table>
</div>
