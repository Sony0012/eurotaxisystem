<div id="boundariesTableContainer" class="bg-white rounded-2xl shadow-xs overflow-hidden border border-slate-200/80">
    <div class="overflow-x-auto overflow-y-visible">
        <table class="min-w-full divide-y divide-slate-100">
            <thead class="bg-slate-50/90 border-b border-slate-200/70">
                <tr>
                    <th class="px-5 py-3.5 text-left text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">Date & Time</th>
                    <th class="px-5 py-3.5 text-left text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">Unit Plate</th>
                    <th class="px-5 py-3.5 text-left text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">Driver</th>
                    <th class="px-5 py-3.5 text-left text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">Target</th>
                    <th class="px-5 py-3.5 text-left text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">Actual Paid</th>
                    <th class="px-5 py-3.5 text-left text-[10px] sm:text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-100">
                @if (empty($boundaries) || count($boundaries) === 0)
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="inline-flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-4 shadow-xs">
                                    <i data-lucide="receipt" class="w-8 h-8 text-amber-600"></i>
                                </div>
                                <h4 class="text-base font-black text-slate-800 tracking-tight mb-1">No Boundary Records Found</h4>
                                <p class="text-xs font-medium text-slate-400 text-center leading-relaxed mb-4">No collections match your filter criteria or date. Try selecting another date or record a new boundary payment.</p>
                                <button type="button" onclick="addBoundary()" class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-500/20 transition-all flex items-center gap-1.5 cursor-pointer">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    Add New Boundary
                                </button>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach ($boundaries as $boundary)
                        <tr class="hover:bg-amber-50/40 transition-colors cursor-pointer group" onclick="openViewBoundary({{ $boundary['id'] }})">
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="text-xs font-black text-slate-800 tracking-tight">{{ \Carbon\Carbon::parse($boundary['date'])->format('M d, Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ \Carbon\Carbon::parse($boundary['created_at'])->format('h:i A') }}</div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-700 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                                        <i data-lucide="car" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="text-xs font-black text-slate-900 group-hover:text-amber-700 transition-colors uppercase tracking-tight">{{ $boundary['plate_number'] }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="text-xs font-bold text-slate-800 leading-tight flex items-center gap-1.5">
                                    <span>{{ $boundary['driver_name'] ?? 'Unassigned' }}</span>
                                    @if(!empty($boundary['is_extra_driver']))
                                        <span class="px-1.5 py-0.5 bg-orange-100 text-orange-800 text-[8px] font-black rounded border border-orange-200 uppercase tracking-wider">Extra</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 mt-0.5 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                    <span title="Input by {{ $boundary['creator_name'] ?? 'System' }}">In: {{ explode(' ', $boundary['creator_name'] ?? 'System')[0] }}</span>
                                    @if(isset($boundary['editor_name']) && $boundary['editor_name'])
                                        <span class="text-slate-300">•</span>
                                        <span title="Last edit by {{ $boundary['editor_name'] }}">Ed: {{ explode(' ', $boundary['editor_name'])[0] }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-xs text-slate-900 font-black tracking-tight">{{ formatCurrency($boundary['boundary_amount']) }}</span>
                                    @if(isset($boundary['rate_label']) && ($boundary['rate_type'] ?? 'regular') !== 'regular')
                                        <span class="text-[9px] font-extrabold uppercase tracking-wider px-1.5 py-0.5 rounded mt-0.5 w-fit
                                            @if($boundary['rate_type'] === 'coding') bg-rose-50 text-rose-700 border border-rose-200
                                            @elseif($boundary['rate_type'] === 'discount') bg-sky-50 text-sky-700 border border-sky-200
                                            @else bg-slate-100 text-slate-600 border border-slate-200 @endif">
                                            {{ $boundary['rate_label'] }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-xs text-slate-900 font-black tracking-tight">
                                {{ formatCurrency($boundary['actual_boundary'] ?? 0) }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                @php
                                    $statusClass = 'bg-amber-50 text-amber-700 border-amber-200/80';
                                    if ($boundary['status'] === 'paid') $statusClass = 'bg-emerald-50 text-emerald-700 border-emerald-200/80';
                                    if ($boundary['status'] === 'shortage') $statusClass = 'bg-rose-50 text-rose-700 border-rose-200/80';
                                    if ($boundary['status'] === 'excess') $statusClass = 'bg-sky-50 text-sky-700 border-sky-200/80';
                                @endphp
                                <div class="flex flex-col gap-1">
                                    <span class="px-2 py-0.5 inline-flex text-[9px] leading-tight font-black rounded-md border w-fit uppercase tracking-wider {{ $statusClass }}">
                                        {{ $boundary['status'] }}
                                    </span>
                                    @if (isset($boundary['has_incentive']))
                                        @if ($boundary['has_incentive'])
                                            <span class="px-1.5 py-0.5 bg-emerald-500/10 text-emerald-700 text-[8px] font-black rounded border border-emerald-200/80 uppercase tracking-wider w-fit">Incentive ✅</span>
                                        @else
                                            @php
                                                $notes_lc = strtolower($boundary['notes'] ?? '');
                                                $is_damage_case = str_contains($notes_lc, 'vehicle damaged') || str_contains($notes_lc, 'maintenance') || str_contains($notes_lc, 'breakdown');
                                            @endphp
                                            <span class="px-1.5 py-0.5 bg-rose-500/10 text-rose-700 text-[8px] font-black rounded border border-rose-200/80 uppercase tracking-wider w-fit">
                                                {{ $is_damage_case ? 'Damaged / B-Down' : 'Late Turn ⏰' }}
                                            </span>
                                        @endif
                                    @endif
                                    @if ($boundary['shortage'] > 0)
                                        <div class="text-[10px] font-black text-rose-600 tracking-tight">-{{ formatCurrency($boundary['shortage']) }}</div>
                                    @elseif ($boundary['excess'] > 0)
                                        <div class="text-[10px] font-black text-sky-600 tracking-tight">+{{ formatCurrency($boundary['excess']) }}</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if ($pagination['total_pages'] > 1)
        <div class="bg-slate-50/70 px-5 py-3.5 flex items-center justify-between border-t border-slate-200/80 sm:px-6 boundaries-pagination">
            <div class="flex-1 flex justify-between sm:hidden">
                @if ($pagination['has_prev'])
                    <a href="?page={{ $pagination['prev_page'] }}&search={{ urlencode($search) }}&date={{ urlencode($date_filter) }}&status={{ urlencode($status_filter) }}" class="relative inline-flex items-center px-3.5 py-1.5 border border-slate-300 text-xs font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50">Previous</a>
                @endif
                @if ($pagination['has_next'])
                    <a href="?page={{ $pagination['next_page'] }}&search={{ urlencode($search) }}&date={{ urlencode($date_filter) }}&status={{ urlencode($status_filter) }}" class="ml-3 relative inline-flex items-center px-3.5 py-1.5 border border-slate-300 text-xs font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50">Next</a>
                @endif
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500">
                        Showing <span class="font-black text-slate-800">{{ $pagination['offset'] + 1 }}</span> to 
                        <span class="font-black text-slate-800">{{ min($pagination['offset'] + $pagination['items_per_page'], $pagination['total_items']) }}</span> of 
                        <span class="font-black text-slate-800">{{ $pagination['total_items'] }}</span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-xl shadow-2xs -space-x-px overflow-hidden border border-slate-200">
                        @if ($pagination['has_prev'])
                            <a href="?page={{ $pagination['prev_page'] }}&search={{ urlencode($search) }}&date={{ urlencode($date_filter) }}&status={{ urlencode($status_filter) }}" class="relative inline-flex items-center px-2.5 py-1.5 bg-white text-xs font-medium text-slate-500 hover:bg-slate-50 border-r border-slate-200">
                                <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            </a>
                        @endif
                        
                        @php
                        $start_page = max(1, $pagination['page'] - 2);
                        $end_page = min($pagination['total_pages'], $pagination['page'] + 2);
                        @endphp
                        
                        @for ($i = $start_page; $i <= $end_page; $i++)
                            <a href="?page={{ $i }}&search={{ urlencode($search) }}&date={{ urlencode($date_filter) }}&status={{ urlencode($status_filter) }}" class="relative inline-flex items-center px-3.5 py-1.5 text-xs font-bold border-r border-slate-200 last:border-r-0 {{ $i === $pagination['page'] ? 'z-10 bg-amber-500 text-white font-black' : 'bg-white text-slate-600 hover:bg-slate-50' }}">
                                {{ $i }}
                            </a>
                        @endfor
                        
                        @if ($pagination['has_next'])
                            <a href="?page={{ $pagination['next_page'] }}&search={{ urlencode($search) }}&date={{ urlencode($date_filter) }}&status={{ urlencode($status_filter) }}" class="relative inline-flex items-center px-2.5 py-1.5 bg-white text-xs font-medium text-slate-500 hover:bg-slate-50">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        @endif
                    </nav>
                </div>
            </div>
        </div>
    @endif
</div>
