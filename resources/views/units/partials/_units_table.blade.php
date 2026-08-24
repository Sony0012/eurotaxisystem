{{-- ═══════════════════════════════════════════════════════════════
     UNIT MANAGEMENT — PRECISE TABLE FORMAT
     Matching the user-provided screenshot aesthetic.
     ═══════════════════════════════════════════════════════════════ --}}

<div id="unitsTableScrollContainer" class="overflow-x-auto bg-gray-50/50 px-2 md:px-4 py-3">
    <table class="min-w-full text-sm modern-table-sep">
        <thead>
            <tr>
                <th class="px-2 md:px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Plate Number Info</th>
                <th class="px-2 md:px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Vehicle Details</th>
                <th class="px-2 md:px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest hidden md:table-cell">Assigned Drivers</th>
                <th class="px-2 md:px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                <th class="px-2 md:px-6 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Boundary Rate</th>
                <th class="px-2 md:px-6 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $unit)
                @php
                    $primary_driver = $unit->primary_driver ?? null;
                    $secondary_driver = $unit->secondary_driver ?? null;
                    
                    $dotClass = match($unit->status) {
                        'active'       => 'bg-green-500',
                        'maintenance'  => 'bg-red-500',
                        'coding'       => 'bg-yellow-500',
                        'at_risk'      => 'bg-orange-500',
                        'vacant', 'available' => 'bg-gray-400',
                        default        => 'bg-gray-400',
                    };
                    $statusColor = match($unit->status) {
                        'active'       => 'text-green-600',
                        'maintenance'  => 'text-red-600',
                        'coding'       => 'text-yellow-600',
                        'at_risk'      => 'text-orange-600',
                        default        => 'text-gray-500',
                    };
                    
                    // Maintenance check for the sub-row bar
                    $has_maintenance_data = (int)($unit->gps_device_count ?? 0) > 0 || !empty($unit->imei);
                @endphp

                {{-- Grouped card: main row + health bar share one tbody --}}
                <tbody class="modern-card-tbody">
                {{-- Main Data Row --}}
                <tr class="{{ $has_maintenance_data ? 'modern-row-has-sub' : 'modern-row' }} cursor-pointer group" onclick="viewUnitDetails({{ $unit->uuid }})">
                    {{-- Plate Number Info --}}
                    <td class="px-2 md:px-6 py-3 md:py-5 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-xs md:text-sm font-black text-gray-900 tracking-tight">{{ $unit->plate_number }}</span>
                            <div class="mt-1 flex flex-col gap-0.5">
                                <span class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-tighter">M: {{ $unit->motor_no ?? '—' }}</span>
                                <span class="text-[8px] md:text-[9px] font-bold text-gray-400 uppercase tracking-tighter">C: {{ $unit->chassis_no ?? '—' }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Vehicle Details --}}
                    <td class="px-2 md:px-6 py-3 md:py-5 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-xs md:text-sm font-black text-gray-900">{{ $unit->make }} {{ $unit->model }}</span>
                            <span class="text-[10px] md:text-xs font-bold text-gray-400">{{ $unit->year }}</span>
                            <div class="mt-1.5">
                                <span class="px-1.5 md:px-2 py-0.5 bg-blue-50 text-blue-600 text-[8px] md:text-[9px] font-black uppercase rounded border border-blue-100">New</span>
                            </div>
                        </div>
                    </td>

                    {{-- Assigned Drivers --}}
                    <td class="px-2 md:px-6 py-3 md:py-5 whitespace-nowrap hidden md:table-cell align-middle">
                        @php
                            $d1Name = '';
                            $d1Contact = '';
                            if ($unit->driver_id && !empty($primary_driver)) {
                                $d1Parts = explode('|', $primary_driver);
                                $d1Name = trim($d1Parts[0] ?? '');
                                $d1Contact = trim($d1Parts[1] ?? '');
                            }

                            $d2Name = '';
                            $d2Contact = '';
                            if ($unit->secondary_driver_id && !empty($secondary_driver)) {
                                $d2Parts = explode('|', $secondary_driver);
                                $d2Name = trim($d2Parts[0] ?? '');
                                $d2Contact = trim($d2Parts[1] ?? '');
                            }

                            $d1Photo = $unit->primary_driver_photo_url ?? asset('image/avatars/driver.svg');
                            $d2Photo = $unit->secondary_driver_photo_url ?? asset('image/avatars/driver.svg');
                        @endphp
                        <div class="flex items-center justify-between gap-3 max-w-[260px] my-auto">
                            {{-- Driver Text Names on the LEFT --}}
                            <div class="flex flex-col gap-0.5 min-w-0 flex-1 justify-center">
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-tight">D1:</span>
                                    <span class="text-xs font-bold {{ $unit->driver_id ? 'text-gray-900' : 'text-gray-400 italic' }} truncate">
                                        {{ $unit->driver_id && $d1Name ? $d1Name : 'No D1' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 truncate">
                                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-tight">D2:</span>
                                    <span class="text-xs font-bold {{ $unit->secondary_driver_id ? 'text-gray-900' : 'text-gray-400 italic' }} truncate">
                                        {{ $unit->secondary_driver_id && $d2Name ? $d2Name : 'No D2' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Avatar stack on the RIGHT side (larger avatars with overlapping ring) --}}
                            @if($unit->driver_id || $unit->secondary_driver_id)
                                <div class="flex -space-x-3.5 overflow-hidden shrink-0 items-center justify-center">
                                    @if($unit->driver_id)
                                        <div class="relative inline-block w-10 h-10 sm:w-11 sm:h-11 rounded-full overflow-hidden ring-2 ring-white border-2 border-amber-400 bg-slate-100 shadow-sm cursor-pointer group hover:z-10 transition-all"
                                             onclick="event.stopPropagation(); if(typeof openImageModal==='function'){ openImageModal('{{ $d1Photo }}'); }"
                                             title="D1: {{ $d1Name ?: 'Primary Driver' }} (Click to view photo)">
                                            <img src="{{ $d1Photo }}" alt="{{ $d1Name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-150" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                                        </div>
                                    @endif
                                    @if($unit->secondary_driver_id)
                                        <div class="relative inline-block w-10 h-10 sm:w-11 sm:h-11 rounded-full overflow-hidden ring-2 ring-white border-2 border-amber-400 bg-slate-100 shadow-sm cursor-pointer group hover:z-10 transition-all"
                                             onclick="event.stopPropagation(); if(typeof openImageModal==='function'){ openImageModal('{{ $d2Photo }}'); }"
                                             title="D2: {{ $d2Name ?: 'Secondary Driver' }} (Click to view photo)">
                                            <img src="{{ $d2Photo }}" alt="{{ $d2Name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-150" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                                        </div>
                                    @elseif($unit->driver_id)
                                        <div class="relative inline-flex items-center justify-center w-10 h-10 sm:w-11 sm:h-11 rounded-full ring-2 ring-white bg-slate-50 border-2 border-dashed border-slate-300 text-[10px] font-black text-slate-400 shadow-2xs" title="No D2 assigned">
                                            <span>D2</span>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-slate-50/90 border-2 border-dashed border-slate-300 flex items-center justify-center text-slate-400 shrink-0 shadow-2xs" title="No drivers assigned">
                                    <i data-lucide="user-x" class="w-5 h-5 text-slate-400"></i>
                                </div>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-2 md:px-6 py-3 md:py-5 whitespace-nowrap">
                        <div class="flex items-center gap-1.5 md:gap-2">
                            <div class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full {{ $dotClass }} animate-pulse {{ $unit->status === 'active' ? 'shadow-[0_0_8px_rgba(34,197,94,0.5)]' : '' }}"></div>
                            <span class="text-[9px] md:text-[11px] font-black uppercase tracking-wider md:tracking-widest {{ $statusColor }}">
                                {{ $unit->status === 'at_risk' ? 'At Risk' : ucfirst($unit->status === 'available' ? 'vacant' : $unit->status) }}
                            </span>
                        </div>
                    </td>

                    {{-- Boundary Rate --}}
                    <td class="px-2 md:px-6 py-3 md:py-5 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-xs md:text-sm font-black text-gray-900">₱{{ number_format($unit->current_rate ?? $unit->boundary_rate, 2) }}</span>
                            <div class="mt-1.5">
                                <span class="px-1 md:px-2 py-0.5 md:py-1 bg-blue-600 text-white text-[8px] md:text-[9px] font-black uppercase rounded shadow-sm">
                                    {{ $unit->rate_label ?? 'Standard Rate' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td class="px-2 md:px-6 py-3 md:py-5 whitespace-nowrap text-center relative" onclick="event.stopPropagation()">
                        <button type="button"
                            class="p-1 md:p-2 text-gray-400 hover:text-gray-800 hover:bg-gray-200 rounded-full transition-colors focus:outline-none inline-flex items-center justify-center"
                            onclick="toggleUnitDropdown('unit-dropdown-{{ $unit->uuid }}', event)"
                            title="Actions">
                            <i data-lucide="more-vertical" class="w-4 h-4 md:w-5 md:h-5"></i>
                        </button>
                    </td>
                </tr>

                {{-- Maintenance Bar Row (Sub-Row — stays inside same tbody card) --}}
                @if($has_maintenance_data)
                    <tr class="modern-sub-row cursor-pointer" onclick="viewUnitDetails({{ $unit->uuid }})">
                        <td colspan="6" class="px-2 md:px-6 pb-4 pt-0">
                            @include('units.partials._maintenance_health_bar', ['unit' => $unit])
                        </td>
                    </tr>
                @endif
                {{-- Transparent spacer row to keep gaps between cards --}}
                <tr class="h-2 bg-transparent"><td colspan="6" class="p-0"></td></tr>
                </tbody>

            @empty
                <tbody>
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <i data-lucide="car" class="w-16 h-16 mx-auto mb-4 text-gray-100"></i>
                        <h4 class="text-gray-900 font-black text-xl">No units found</h4>
                        <p class="text-gray-400 italic">Try adjusting your search criteria.</p>
                    </td>
                </tr>
                </tbody>
            @endforelse
        </table>
</div>

{{-- Floating Action Dropdowns rendered outside table to escape stacking contexts, transforms, and overflow --}}
@foreach($units as $unit)
    @php
        $has_maintenance_data = (int)($unit->gps_device_count ?? 0) > 0 || !empty($unit->imei);
    @endphp
    <div id="unit-dropdown-{{ $unit->uuid }}"
        class="unit-action-dropdown hidden fixed w-48 bg-white rounded-xl shadow-2xl border border-gray-200 py-1.5 z-[999999]">
        {{-- Edit --}}
        <button type="button"
            class="w-full text-left px-4 py-2.5 text-xs font-bold text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center gap-2.5"
            onclick="event.stopPropagation(); closeAllUnitDropdowns(); editUnit({{ $unit->uuid }})">
            <i data-lucide="edit-2" class="w-4 h-4 text-gray-500"></i> Edit Unit
        </button>
        {{-- Reset Service Overdue --}}
        @if($has_maintenance_data)
        <form method="POST" action="{{ route('units.reset-health', $unit->uuid) }}"
            onsubmit="return confirm('Reset service overdue for unit {{ $unit->plate_number }}? This will reset the maintenance counter to zero based on current GPS odometer.');"
            class="m-0 p-0">
            @csrf
            <button type="submit"
                onclick="event.stopPropagation()"
                class="w-full text-left px-4 py-2.5 text-xs font-bold text-green-600 hover:bg-green-50 transition-colors flex items-center gap-2.5 border-t border-gray-100">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-green-600"></i> Reset Service
            </button>
        </form>
        @endif

        {{-- Archive --}}
        <form method="POST" action="{{ route('units.destroy', $unit->uuid) }}"
            onsubmit="return confirm('Archive unit {{ $unit->plate_number }}? It will be moved to the Archive page.');"
            class="m-0 p-0">
            @csrf @method('DELETE')
            <button type="submit"
                onclick="event.stopPropagation()"
                class="w-full text-left px-4 py-2.5 text-xs font-bold text-amber-600 hover:bg-amber-50 transition-colors flex items-center gap-2.5 border-t border-gray-100">
                <i data-lucide="archive" class="w-4 h-4 text-amber-600"></i> Archive Unit
            </button>
        </form>
    </div>
@endforeach

{{-- Modern Pagination --}}
@if($pagination['total_pages'] > 1)
    <div class="px-8 py-6 bg-gray-50/50 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
            Showing <span class="text-gray-900">{{ count($units) }}</span> of <span class="text-gray-900">{{ number_format($pagination['total_items']) }}</span> Units
        </div>
        <div class="flex items-center gap-1.5">
            @if($pagination['has_prev'])
                <button onclick="changePage({{ $pagination['prev_page'] }})" class="p-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all active:scale-90 shadow-sm">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
            @endif
            @for($i = max(1, $pagination['page'] - 2); $i <= min($pagination['total_pages'], $pagination['page'] + 2); $i++)
                <button onclick="changePage({{ $i }})" class="w-10 h-10 rounded-xl border text-[11px] font-black transition-all {{ $i === $pagination['page'] ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                    {{ $i }}
                </button>
            @endfor
            @if($pagination['has_next'])
                <button onclick="changePage({{ $pagination['next_page'] }})" class="p-2.5 rounded-xl border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 transition-all active:scale-90 shadow-sm">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            @endif
        </div>
    </div>
@endif



