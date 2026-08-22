{{-- ═══════════════════════════════════════════════════════════════
     UNIT MANAGEMENT — 21st.dev MODERN LUXURY TABLE VIEW
     ═══════════════════════════════════════════════════════════════ --}}

<div id="unitsTableScrollContainer" class="overflow-x-auto bg-slate-50/50 p-4 md:p-6">
    <table class="min-w-full text-sm border-separate border-spacing-y-3">
        <thead>
            <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                <th class="px-5 py-3 text-left">Plate & Serial Info</th>
                <th class="px-5 py-3 text-left">Vehicle Details</th>
                <th class="px-5 py-3 text-left hidden md:table-cell">Assigned Drivers</th>
                <th class="px-5 py-3 text-left">Status</th>
                <th class="px-5 py-3 text-left">Daily Boundary</th>
                <th class="px-5 py-3 text-center">Actions</th>
            </tr>
        </thead>
        @forelse($units as $unit)
            @php
                $primary_driver = $unit->primary_driver ?? null;
                $secondary_driver = $unit->secondary_driver ?? null;
                
                // 3D SVG based on status
                $status_svg = match($unit->status) {
                    'active'              => 'unit_active_3d.svg',
                    'maintenance'         => 'unit_maint_3d.svg',
                    'coding'              => 'unit_coding_3d.svg',
                    'at_risk'             => 'unit_atrisk_3d.svg',
                    'vacant', 'available' => 'unit_vacant_3d.svg',
                    default               => 'unit_vacant_3d.svg',
                };

                // Badge styling per status
                $status_badge = match($unit->status) {
                    'active'              => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
                    'maintenance'         => 'bg-rose-50 text-rose-700 border-rose-200/80',
                    'coding'              => 'bg-amber-50 text-amber-800 border-amber-200/80',
                    'at_risk'             => 'bg-orange-50 text-orange-800 border-orange-200/80',
                    'vacant', 'available' => 'bg-slate-100 text-slate-700 border-slate-200',
                    default               => 'bg-slate-100 text-slate-700 border-slate-200',
                };

                $status_dot = match($unit->status) {
                    'active'              => 'bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.5)]',
                    'maintenance'         => 'bg-rose-500 shadow-[0_0_6px_rgba(244,63,94,0.5)]',
                    'coding'              => 'bg-amber-500 shadow-[0_0_6px_rgba(245,158,11,0.5)]',
                    'at_risk'             => 'bg-orange-500 shadow-[0_0_6px_rgba(249,115,22,0.5)]',
                    'vacant', 'available' => 'bg-slate-400',
                    default               => 'bg-slate-400',
                };

                $has_maintenance_data = (int)($unit->gps_device_count ?? 0) > 0 || !empty($unit->imei);
            @endphp

            <tbody class="modern-card-tbody group cursor-pointer" onclick="viewUnitDetails({{ $unit->uuid }})">
                {{-- Main Data Row --}}
                <tr class="bg-white border border-slate-200/80 hover:border-slate-300 shadow-xs hover:shadow-md transition-all rounded-2xl">
                    {{-- Plate Number Info --}}
                    <td class="px-5 py-4 whitespace-nowrap {{ $has_maintenance_data ? 'rounded-tl-2xl' : 'rounded-l-2xl' }}">
                        <div class="flex flex-col gap-1.5">
                            <div class="inline-flex items-center">
                                <span class="bg-slate-950 text-white px-3 py-1 rounded-xl text-xs font-black tracking-widest shadow-xs">
                                    {{ $unit->plate_number }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-[9px] font-bold text-slate-400">
                                <span>M: {{ $unit->motor_no ? substr($unit->motor_no, -8) : '—' }}</span>
                                <span>•</span>
                                <span>C: {{ $unit->chassis_no ? substr($unit->chassis_no, -8) : '—' }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- Vehicle Details --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 shrink-0 bg-slate-50 rounded-xl p-1 border border-slate-100 flex items-center justify-center">
                                <img src="{{ asset('image/kpi/' . $status_svg) }}" alt="Taxi" class="w-full h-full object-contain filter drop-shadow-xs">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-900 leading-tight">{{ $unit->make }} {{ $unit->model }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[11px] font-bold text-slate-400">{{ $unit->year }}</span>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black uppercase rounded-md border border-blue-100">
                                        {{ strtoupper($unit->unit_type ?? 'NEW') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Assigned Drivers --}}
                    <td class="px-5 py-4 whitespace-nowrap hidden md:table-cell">
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center gap-2">
                                <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-700 text-[9px] font-black uppercase">D1</span>
                                <span class="text-xs font-bold {{ $unit->driver_id ? 'text-slate-800' : 'text-slate-400 italic' }}">
                                    @if($unit->driver_id && $primary_driver)
                                        @php $d1 = explode('|', $primary_driver); @endphp
                                        {{ $d1[0] }}
                                    @else
                                        Unassigned
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-1.5 py-0.5 rounded bg-purple-50 text-purple-700 text-[9px] font-black uppercase">D2</span>
                                <span class="text-xs font-bold {{ $unit->secondary_driver_id ? 'text-slate-800' : 'text-slate-400 italic' }}">
                                    @if($unit->secondary_driver_id && $secondary_driver)
                                        @php $d2 = explode('|', $secondary_driver); @endphp
                                        {{ $d2[0] }}
                                    @else
                                        Unassigned
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $status_badge }}">
                            <span class="w-2 h-2 rounded-full {{ $status_dot }} animate-pulse"></span>
                            <span>{{ $unit->status === 'at_risk' ? 'At Risk' : ucfirst($unit->status === 'available' ? 'vacant' : $unit->status) }}</span>
                        </div>
                    </td>

                    {{-- Boundary Rate --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-black text-slate-900">₱{{ number_format($unit->current_rate ?? $unit->boundary_rate, 2) }}</span>
                            <div>
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[9px] font-black uppercase rounded-md border border-blue-100">
                                    {{ $unit->rate_label ?? 'Standard Rate' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td class="px-5 py-4 whitespace-nowrap text-center relative z-30 {{ $has_maintenance_data ? 'rounded-tr-2xl' : 'rounded-r-2xl' }}" onclick="event.stopPropagation()">
                        <button type="button"
                            class="p-2 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-full transition-colors focus:outline-none inline-flex items-center justify-center"
                            onclick="toggleUnitDropdown('unit-dropdown-{{ $unit->uuid }}', event)"
                            title="Actions">
                            <i data-lucide="more-vertical" class="w-4 h-4 md:w-5 md:h-5"></i>
                        </button>

                        <div id="unit-dropdown-{{ $unit->uuid }}"
                            class="unit-action-dropdown hidden absolute right-2 top-full mt-1 w-44 bg-white rounded-xl shadow-2xl border border-slate-100 z-[99999] overflow-hidden">
                            {{-- Edit --}}
                            <button type="button"
                                class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center gap-2"
                                onclick="event.stopPropagation(); document.getElementById('unit-dropdown-{{ $unit->uuid }}').classList.add('hidden'); editUnit({{ $unit->uuid }})">
                                <i data-lucide="edit-2" class="w-4 h-4"></i> Edit Unit
                            </button>
                            {{-- Reset Service Overdue --}}
                            @if($has_maintenance_data)
                            <form method="POST" action="{{ route('units.reset-health', $unit->uuid) }}"
                                onsubmit="return confirm('Reset service overdue for unit {{ $unit->plate_number }}? This will reset the maintenance counter to zero based on current GPS odometer.');"
                                class="m-0 p-0">
                                @csrf
                                <button type="submit"
                                    onclick="event.stopPropagation()"
                                    class="w-full text-left px-4 py-2.5 text-xs font-bold text-green-600 hover:bg-green-50 transition-colors flex items-center gap-2 border-t border-slate-50">
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
                                    class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors flex items-center gap-2 border-t border-slate-50">
                                    <i data-lucide="archive" class="w-4 h-4"></i> Archive Unit
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Maintenance Bar Row (Sub-Row inside same card) --}}
                @if($has_maintenance_data)
                    <tr class="bg-white border-x border-b border-slate-200/80 shadow-xs hover:shadow-md transition-all">
                        <td colspan="6" class="px-5 pb-4 pt-1 rounded-b-2xl">
                            @include('units.partials._maintenance_health_bar', ['unit' => $unit])
                        </td>
                    </tr>
                @endif
            </tbody>
        @empty
            <tbody>
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <i data-lucide="car" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
                        <h4 class="text-slate-900 font-black text-xl">No units found</h4>
                        <p class="text-slate-400 italic">Try adjusting your search criteria.</p>
                    </td>
                </tr>
            </tbody>
        @endforelse
    </table>
</div>

{{-- Modern Pagination --}}
@if($pagination['total_pages'] > 1)
    <div class="px-8 py-6 bg-white border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
            Showing <span class="text-slate-900">{{ count($units) }}</span> of <span class="text-slate-900">{{ number_format($pagination['total_items']) }}</span> Units
        </div>
        <div class="flex items-center gap-1.5">
            @if($pagination['has_prev'])
                <button onclick="changePage({{ $pagination['prev_page'] }})" class="p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-all active:scale-90 shadow-sm">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
            @endif
            @for($i = max(1, $pagination['page'] - 2); $i <= min($pagination['total_pages'], $pagination['page'] + 2); $i++)
                <button onclick="changePage({{ $i }})" class="w-10 h-10 rounded-xl border text-[11px] font-black transition-all {{ $i === $pagination['page'] ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                    {{ $i }}
                </button>
            @endfor
            @if($pagination['has_next'])
                <button onclick="changePage({{ $pagination['next_page'] }})" class="p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-all active:scale-90 shadow-sm">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            @endif
        </div>
    </div>
@endif
