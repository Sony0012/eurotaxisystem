<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($items as $m)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $m->unit->plate_number ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ Str::limit($m->description, 30) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency($m->cost) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $m->deleted_at->format('M d, Y H:i') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <form action="{{ route('archive.restore', ['type' => 'maintenance', 'id' => $m->id]) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-3">Restore</button>
                    </form>
                    <form action="{{ route('archive.forceDelete', ['type' => 'maintenance', 'id' => $m->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to permanently delete this maintenance record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900">Delete Permanently</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-500">No archived maintenance records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
