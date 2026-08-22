<div class="overflow-x-auto w-full">
    <table class="w-full divide-y divide-slate-100">
        <thead class="bg-slate-50/70">
            <tr>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Name</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Role</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Email / Contact</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Date Archived</th>
                <th class="px-6 sm:px-8 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @forelse($items as $u)
            <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs shadow-2xs shrink-0">
                            {{ strtoupper(substr($u->full_name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-900">{{ $u->full_name }}</div>
                            @if($u->role === 'driver' && $u->driver)
                                <div class="text-[10px] text-blue-600 font-bold italic">Linked to Driver: {{ $u->driver->full_name }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <span class="inline-flex text-[10px] font-black text-slate-700 bg-slate-100 border border-slate-200 px-2.5 py-1 rounded-md uppercase tracking-wider">{{ $u->role }}</span>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <div class="text-xs font-bold text-slate-700">{{ $u->email }}</div>
                    <div class="text-[10px] text-slate-400 font-medium">{{ $u->phone_number ?: 'No phone' }}</div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-xl">
                        <i data-lucide="clock" class="w-3 h-3 text-rose-500"></i>
                        {{ $u->deleted_at->format('M d, Y h:i A') }}
                    </span>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button"
                            onclick="archiveRestore('{{ route('archive.restore', ['type' => 'user', 'id' => $u->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore
                        </button>
                        <button type="button"
                            onclick="archiveForceDelete('{{ route('archive.forceDelete', ['type' => 'user', 'id' => $u->id]) }}')"
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
                            <i data-lucide="shield" class="w-8 h-8 opacity-40"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700">No archived user accounts found</p>
                        <p class="text-xs text-slate-400">Deactivated or deleted accounts will appear here.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
