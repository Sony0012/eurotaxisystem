<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="w-10 px-4 sm:px-6 py-3 text-center">
                    <input type="checkbox" class="archive-select-all rounded border-slate-300 text-amber-500 focus:ring-amber-400 h-4 w-4 cursor-pointer" title="Select All">
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email/Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Archived</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($items as $u)
            <tr class="hover:bg-gray-50 transition-colors" data-id="{{ $u->id ?? $u->uuid }}">
                <td class="w-10 px-4 sm:px-6 py-4 text-center">
                    <input type="checkbox" class="archive-row-cb rounded border-slate-300 text-amber-500 focus:ring-amber-400 h-4 w-4 cursor-pointer" value="{{ $u->id ?? $u->uuid }}" data-id="{{ $u->id ?? $u->uuid }}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $u->full_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs font-medium text-gray-600">{{ $u->email }}</div>
                    <div class="text-[10px] text-gray-400">{{ $u->phone_number }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        {{ $u->deleted_at->format('M d, Y h:i A') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button"
                        onclick="archiveRestore('{{ route('archive.restore', ['type' => 'user', 'id' => $u->id ?? $u->uuid]) }}', this)"
                        class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg mr-2 transition-all cursor-pointer">
                        <i data-lucide="undo-2" class="w-3 h-3"></i> Restore
                    </button>
                    <button type="button"
                        onclick="archiveForceDelete('{{ route('archive.forceDelete', ['type' => 'user', 'id' => $u->id ?? $u->uuid]) }}', this)"
                        class="inline-flex items-center gap-1 text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all cursor-pointer">
                        <i data-lucide="trash-2" class="w-3 h-3"></i> Delete Permanently
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center gap-3 text-gray-400">
                        <i data-lucide="users" class="w-12 h-12 opacity-30"></i>
                        <p class="text-sm font-medium">No archived driver accounts found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

