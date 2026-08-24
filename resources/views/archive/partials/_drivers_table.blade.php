<div class="overflow-x-auto w-full">
    <table class="w-full divide-y divide-slate-100">
        <thead class="bg-slate-50/70">
            <tr>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Driver Name</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">License No.</th>
                <th class="px-6 sm:px-8 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Date Archived</th>
                <th class="px-6 sm:px-8 py-4 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-slate-100">
            @forelse($items as $driver)
            <tr class="hover:bg-slate-50/80 transition-colors">
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="relative w-9 h-9 rounded-xl overflow-hidden shrink-0 border border-amber-300 bg-slate-100 shadow-2xs">
                            @if(!empty($driver->profile_photo))
                                <img src="{{ asset($driver->profile_photo) }}" alt="{{ $driver->full_name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                            @else
                                <img src="{{ asset('image/avatars/driver.svg') }}" alt="{{ $driver->full_name }}" class="w-full h-full object-cover bg-amber-50">
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-black text-slate-900">{{ $driver->full_name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-400 font-medium">{{ $driver->phone_number ?? 'No contact' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-xs font-mono font-bold text-slate-600">
                    {{ $driver->license_number ?: 'N/A' }}
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap">
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded-xl">
                        <i data-lucide="clock" class="w-3 h-3 text-rose-500"></i>
                        {{ $driver->deleted_at->format('M d, Y h:i A') }}
                    </span>
                </td>
                <td class="px-6 sm:px-8 py-5 whitespace-nowrap text-right">
                    <div class="flex items-center justify-end gap-2">
                        <button type="button"
                            onclick="archiveRestore('{{ route('archive.restore', ['type' => 'driver', 'id' => $driver->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore
                        </button>
                        <button type="button"
                            onclick="archiveForceDelete('{{ route('archive.forceDelete', ['type' => 'driver', 'id' => $driver->id]) }}')"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-1.5 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete Permanently
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 sm:px-8 py-16 text-center">
                    <div class="flex flex-col items-center gap-3 text-slate-400">
                        <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                            <i data-lucide="users" class="w-8 h-8 opacity-40"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700">No archived drivers found</p>
                        <p class="text-xs text-slate-400">Drivers moved to archive will appear here for restoration or deletion.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
