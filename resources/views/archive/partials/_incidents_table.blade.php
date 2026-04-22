<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date / Time</th>
                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver</th>
                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Unit</th>
                <th class="px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($items as $item)
            <tr class="hover:bg-gray-50/50 transition-all">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs font-bold text-gray-800">{{ \Carbon\Carbon::parse($item->timestamp)->format('M d, Y') }}</div>
                    <div class="text-[10px] text-gray-400 font-medium">{{ \Carbon\Carbon::parse($item->timestamp)->format('h:i A') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-xs font-bold text-gray-700">{{ $item->driver_name ?? '—' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-xs font-black text-blue-600 uppercase">
                    {{ $item->plate_number ?? '—' }}
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-wrap gap-1 mb-2">
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[9px] font-black uppercase rounded">{{ $item->incident_type }}</span>
                        @if($item->total_charge_to_driver > 0)
                            <span class="px-2 py-0.5 bg-purple-100 text-purple-700 text-[9px] font-black uppercase rounded">Charge: ₱{{ number_format($item->total_charge_to_driver, 2) }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-600 line-clamp-1 italic">{{ $item->description }}</p>
                    <div class="text-[9px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Deleted on {{ $item->deleted_at->format('M d, Y') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <div class="flex justify-end gap-2">
                        <form action="{{ route('archive.restore', ['type' => 'incident', 'id' => $item->id]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-green-500 hover:bg-green-50 rounded-lg transition-colors" title="Restore Incident">
                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            </button>
                        </form>
                        <form action="{{ route('archive.forceDelete', ['type' => 'incident', 'id' => $item->id]) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this incident record? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-400 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" title="Delete Permanently">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-10 text-center">
                    <div class="flex flex-col items-center justify-center opacity-40">
                        <i data-lucide="clipboard-list" class="w-12 h-12 text-gray-400 mb-3"></i>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No archived incidents found</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
