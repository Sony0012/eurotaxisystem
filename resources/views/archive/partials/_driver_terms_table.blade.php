@if(empty($items))
    <div class="px-6 sm:px-8 py-16 text-center">
        <div class="flex flex-col items-center gap-3 text-slate-400">
            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center">
                <i data-lucide="scroll" class="w-8 h-8 opacity-40"></i>
            </div>
            <p class="text-sm font-bold text-slate-700">No archived terms</p>
            <p class="text-xs text-slate-400">There are no deleted driver terms documents in storage.</p>
        </div>
    </div>
@else
    <div class="p-6 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($items as $filename)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden group flex flex-col hover:border-slate-300 transition-all">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/70 flex justify-between items-center">
                        <h3 class="font-black text-slate-800 text-xs truncate flex items-center gap-1.5" title="{{ $filename }}">
                            <i data-lucide="file-text" class="w-3.5 h-3.5 text-slate-400 shrink-0"></i>
                            <span class="truncate">{{ $filename }}</span>
                        </h3>
                    </div>
                    <div class="p-4 flex-1 flex justify-center items-center bg-slate-50/50">
                        <img src="{{ asset('uploads/archives/terms/' . $filename) }}" alt="Archived Term" 
                             class="w-full h-36 object-contain rounded-2xl shadow-2xs border border-slate-200/80 cursor-zoom-in hover:scale-105 transition-transform select-none"
                             style="-webkit-user-drag: none;" oncontextmenu="return false;" draggable="false"
                             onclick="openLightbox('{{ asset('uploads/archives/terms/' . $filename) }}')">
                    </div>
                    <div class="p-4 border-t border-slate-100 bg-white flex justify-between gap-2">
                        <button type="button" onclick="archiveRestore('{{ route('driver-management.terms.restore', $filename) }}')" class="flex-1 flex justify-center items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 px-3 py-2 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Restore
                        </button>
                        <button type="button" onclick="archiveForceDelete('{{ route('driver-management.terms.force-delete', $filename) }}')" class="flex-1 flex justify-center items-center gap-1.5 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-3 py-2 rounded-xl shadow-2xs active:scale-95 transition-all">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
