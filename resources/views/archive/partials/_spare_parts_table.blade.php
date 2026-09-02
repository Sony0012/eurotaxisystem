<div class="overflow-x-auto w-full">
    <table class="w-full divide-y divide-slate-100">
        <thead class="bg-slate-50/70">
            <tr>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Part Name</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Supplier</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Price</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Date Archived</th>
                <th class="px-6 sm:px-8 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @forelse($items as $item)
            <tr class="hover:bg-slate-50/80 transition-colors group">
                <td class="px-6 sm:px-8 py-5">
                    <div class="flex items-center gap-3.5">
                        <div class="archived-part-icon-box relative w-12 h-12 rounded-2xl p-1 flex items-center justify-center shrink-0 border border-slate-200 bg-white shadow-xs cursor-pointer overflow-hidden group-hover:scale-105 transition-transform"
                             data-part-name="{{ $item->name }}"
                             title="Click to view procedural vector">
                            <i data-lucide="package" class="w-5 h-5 text-slate-400"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-slate-900 tracking-tight">{{ $item->name }}</div>
                            <div class="archived-part-meta flex items-center gap-1.5 mt-0.5" data-part-name="{{ $item->name }}">
                                <span class="text-[10px] font-bold text-slate-400 uppercase">Auto Component</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-xs font-bold text-slate-600">
                    {{ $item->supplier ?? 'Unspecified' }}
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-sm font-black text-emerald-600">
                    ₱{{ number_format($item->price, 2) }}
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-xl">
                        <i data-lucide="clock" class="w-3 h-3 text-rose-500"></i>
                        {{ $item->deleted_at->format('M d, Y h:i A') }}
                    </span>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button"
                            onclick="archiveRestore('{{ route('archive.restore', ['type' => 'spare_part', 'id' => $item->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all cursor-pointer">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore
                        </button>
                        <button type="button"
                            onclick="archiveForceDelete('{{ route('archive.forceDelete', ['type' => 'spare_part', 'id' => $item->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all cursor-pointer">
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
                            <i data-lucide="package" class="w-8 h-8 opacity-40"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700">No archived spare parts found</p>
                        <p class="text-xs text-slate-400">Spare parts you archive from Inventory will appear here.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
