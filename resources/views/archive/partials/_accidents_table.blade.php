<div class="overflow-x-auto w-full">
    <table class="w-full divide-y divide-slate-100">
        <thead class="bg-slate-50/70">
            <tr>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Date / Time</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Driver</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Unit</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Location / Info</th>
                <th class="px-6 sm:px-8 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @forelse($items as $item)
            <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <div class="text-xs sm:text-sm font-black text-slate-900">{{ \Carbon\Carbon::parse($item->created_at)->format('M d, Y') }}</div>
                    <div class="text-xs text-slate-400 font-mono font-bold">{{ \Carbon\Carbon::parse($item->created_at)->format('h:i A') }}</div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <div class="text-xs sm:text-sm font-black text-slate-800">{{ $item->driver ? $item->driver->first_name . ' ' . $item->driver->last_name : '—' }}</div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-black text-blue-700 bg-blue-50 border border-blue-200">
                        {{ $item->unit ? $item->unit->plate_number : '—' }}
                    </span>
                </td>
                <td class="px-6 sm:px-8 py-5">
                    <div class="flex flex-wrap gap-1.5 mb-1.5">
                        <span class="px-2.5 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 text-[10px] font-black uppercase rounded-md">{{ $item->type }}</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-600 line-clamp-1 leading-relaxed">{{ $item->notes ?: 'No description provided' }}</p>
                    <div class="text-[10px] text-rose-500 font-bold mt-1">Archived on {{ $item->deleted_at->format('M d, Y') }}</div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button"
                            onclick="archiveRestore('{{ route('archive.restore', ['type' => 'accident', 'id' => $item->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore
                        </button>
                        <button type="button"
                            onclick="archiveForceDelete('{{ route('archive.forceDelete', ['type' => 'accident', 'id' => $item->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Permanently
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 sm:px-8 py-16 text-center">
                    <div class="flex flex-col items-center gap-3 text-slate-400">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                            <i data-lucide="ambulance" class="w-8 h-8 opacity-40"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700">No archived accidents found</p>
                        <p class="text-xs text-slate-400">Accident and SOS emergency tickets moved to archive will appear here.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
