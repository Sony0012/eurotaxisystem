<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Archived</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="building-2" class="w-4 h-4 text-amber-600"></i>
                        </div>
                        <span class="text-sm font-bold text-gray-900">{{ $item->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->contact_person ?? '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $item->phone_number ?? '—' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $item->address ?? '—' }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        {{ $item->deleted_at->format('M d, Y h:i A') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <form action="{{ route('archive.restore', ['type' => 'supplier', 'id' => $item->id]) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg mr-2 transition-all">
                            <i data-lucide="undo-2" class="w-3 h-3"></i> Restore
                        </button>
                    </form>

                    <button type="button"
                        onclick="confirmPermanentDelete('supplier', {{ $item->id }}, '{{ addslashes($item->name) }}')"
                        class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all">
                        <i data-lucide="trash-2" class="w-3 h-3"></i> Delete Permanently
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3 text-gray-400">
                        <i data-lucide="building-2" class="w-12 h-12 opacity-30"></i>
                        <p class="text-sm font-medium">No archived suppliers found.</p>
                        <p class="text-xs">Suppliers you archive from the Manage Suppliers panel will appear here.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Permanent Delete Modal — uses shared modal from archive index --}}
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
