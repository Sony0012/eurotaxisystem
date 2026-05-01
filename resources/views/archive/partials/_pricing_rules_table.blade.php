<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bracket Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year Range</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deleted At</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($items as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->start_year }} - {{ $item->end_year }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $item->deleted_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                        <form action="{{ route('archive.restore', ['type' => 'pricing_rule', 'id' => $item->id]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-900 font-bold">Restore</button>
                        </form>

                        <button type="button" 
                            onclick="confirmPermanentDelete('pricing_rule', {{ $item->id }}, '{{ $item->name }}')"
                            class="text-red-600 hover:text-red-900 font-bold">
                            Delete Permanently
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        No archived pricing rules found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Permanent Delete Modal (shared across archive pages) -->
@once
<div id="permanentDeleteModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[150] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-red-600 p-4">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-6 h-6 text-white"></i>
                <h3 class="text-lg font-black text-white">Confirm Permanent Deletion</h3>
            </div>
        </div>
        <form id="permanentDeleteForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('DELETE')
            <p class="text-sm text-gray-600">
                You are about to permanently delete <span id="deleteItemName" class="font-black text-gray-900"></span>. This action cannot be undone.
            </p>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Enter Archive Password</label>
                <input type="password" name="archive_password" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 outline-none">
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closePermanentDeleteModal()" class="px-4 py-2 text-sm font-bold text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm font-black text-white bg-red-600 rounded-lg hover:bg-red-700">Wipe Permanently</button>
            </div>
        </form>
    </div>
</div>

<script>
    function confirmPermanentDelete(type, id, name) {
        const modal = document.getElementById('permanentDeleteModal');
        const form = document.getElementById('permanentDeleteForm');
        const nameSpan = document.getElementById('deleteItemName');
        
        nameSpan.textContent = name;
        form.action = `/archive/force-delete/${type}/${id}`;
        modal.classList.remove('hidden');
    }

    function closePermanentDeleteModal() {
        document.getElementById('permanentDeleteModal').classList.add('hidden');
    }
</script>
@endonce
