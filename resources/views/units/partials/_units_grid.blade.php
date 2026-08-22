{{-- ═══════════════════════════════════════════════════════════════
     UNIT MANAGEMENT — 21st.dev INTERACTIVE 3D PERSPECTIVE CARDS
     Based on 21st.dev Card-7 with Interactive 3D Perspective Tilt.
     ═══════════════════════════════════════════════════════════════ --}}

<div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 bg-slate-50/50 items-stretch">
    @forelse($units as $unit)
        @php
            $primary_driver = $unit->primary_driver ?? null;

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
                'active'              => 'bg-emerald-500/10 text-emerald-700 border-emerald-300/60',
                'maintenance'         => 'bg-rose-500/10 text-rose-700 border-rose-300/60',
                'coding'              => 'bg-amber-500/10 text-amber-800 border-amber-300/60',
                'at_risk'             => 'bg-orange-500/10 text-orange-800 border-orange-300/60',
                'vacant', 'available' => 'bg-slate-500/10 text-slate-700 border-slate-300/60',
                default               => 'bg-slate-500/10 text-slate-700 border-slate-300/60',
            };

            $status_dot = match($unit->status) {
                'active'              => 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]',
                'maintenance'         => 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.6)]',
                'coding'              => 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.6)]',
                'at_risk'             => 'bg-orange-500 shadow-[0_0_8px_rgba(249,115,22,0.6)]',
                'vacant', 'available' => 'bg-slate-400',
                default               => 'bg-slate-400',
            };

            $card_border = match($unit->status) {
                'active'              => 'border-emerald-500/70 hover:border-emerald-500',
                'maintenance'         => 'border-rose-500/70 hover:border-rose-500',
                'coding'              => 'border-amber-400/70 hover:border-amber-400',
                'at_risk'             => 'border-orange-500/70 hover:border-orange-500',
                'vacant', 'available' => 'border-slate-300/80 hover:border-slate-400',
                default               => 'border-slate-200 hover:border-slate-300',
            };

            $card_gradient = match($unit->status) {
                'active'              => 'bg-gradient-to-b from-white via-emerald-50/20 to-white',
                'maintenance'         => 'bg-gradient-to-b from-white via-rose-50/20 to-white',
                'coding'              => 'bg-gradient-to-b from-white via-amber-50/20 to-white',
                'at_risk'             => 'bg-gradient-to-b from-white via-orange-50/20 to-white',
                'vacant', 'available' => 'bg-gradient-to-b from-white via-slate-50/40 to-white',
                default               => 'bg-white',
            };

            $has_maintenance_data = (int)($unit->gps_device_count ?? 0) > 0 || !empty($unit->imei);
        @endphp

        <div class="unit-perspective-card h-full flex flex-col justify-between rounded-[2rem] border-2 {{ $card_border }} {{ $card_gradient }} p-5 shadow-xs transition-all cursor-pointer relative"
             style="transform-style: preserve-3d; will-change: transform;"
             onmousemove="handleUnitCardTilt(event, this)"
             onmouseleave="resetUnitCardTilt(this)"
             onclick="viewUnitDetails({{ $unit->uuid }})">
            
            <div>
                {{-- ── Top Glassmorphic Header: Plate & Status ── --}}
                <div class="flex justify-between items-center mb-4">
                    <div class="bg-slate-950 text-white px-3.5 py-1.5 rounded-xl text-xs sm:text-sm font-black tracking-widest shadow-sm">
                        {{ $unit->plate_number }}
                    </div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $status_badge }} backdrop-blur-xs">
                        <span class="w-2 h-2 rounded-full {{ $status_dot }} animate-pulse"></span>
                        <span>{{ $unit->status === 'at_risk' ? 'At Risk' : ucfirst($unit->status === 'available' ? 'vacant' : $unit->status) }}</span>
                    </div>
                </div>

                {{-- ── Vehicle Title & 3D Illustration Row ── --}}
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="min-w-0 flex-1">
                        <h4 class="text-base sm:text-lg font-black text-slate-900 leading-tight truncate">
                            {{ $unit->make }} {{ $unit->model }}
                        </h4>
                        <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">
                            {{ $unit->year }} • {{ strtoupper($unit->unit_type ?? 'NEW') }}
                        </p>
                        
                        {{-- Boundary Rate Badge --}}
                        <div class="mt-2.5 inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200/80">
                            <i data-lucide="banknote" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span class="text-xs sm:text-sm font-black text-emerald-700">₱{{ number_format($unit->current_rate ?? $unit->boundary_rate, 2) }}</span>
                        </div>
                    </div>

                    {{-- 3D SVG Taxi with depth --}}
                    <div class="w-18 h-18 sm:w-20 sm:h-20 shrink-0">
                        <img src="{{ asset('image/kpi/' . $status_svg) }}" alt="{{ $unit->status }} taxi" class="w-full h-full object-contain filter drop-shadow-md">
                    </div>
                </div>

                {{-- ── Driver Section ── --}}
                <div class="bg-slate-50/90 rounded-2xl p-3 flex items-center gap-3 mb-3 border border-slate-100">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center border border-slate-200 shrink-0 shadow-xs">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Primary Driver</p>
                        <p class="text-xs font-bold text-slate-700 truncate">
                            @if($unit->driver_id && $primary_driver)
                                @php $d1 = explode('|', $primary_driver); @endphp
                                {{ $d1[0] }}
                            @else
                                <span class="text-slate-400 font-semibold italic">Unassigned</span>
                            @endif
                        </p>
                    </div>
                    @if($unit->driver_id)
                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.5)]"></div>
                    @endif
                </div>

                {{-- ── Maintenance & Telemetry Container (Equal Height Slot) ── --}}
                <div class="min-h-[58px] flex flex-col justify-center mb-2">
                    @if($has_maintenance_data)
                        @include('units.partials._maintenance_health_bar', ['unit' => $unit])
                    @else
                        <div class="w-full bg-slate-50/70 rounded-xl border border-dashed border-slate-200/80 px-3 py-2 flex items-center justify-between">
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">Standard Interval</span>
                            </div>
                            <span class="text-[9px] font-bold text-slate-400">Manual ODO Sync</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── Footer: Serial & Actions ── --}}
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between mt-1">
                <div class="flex flex-col">
                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-0.5">Serial Info</span>
                    <span class="text-[11px] font-bold text-slate-700">{{ $unit->motor_no ? substr($unit->motor_no, -8) : 'N/A' }}</span>
                </div>

                {{-- Three-dots dropdown --}}
                <div class="relative">
                    <button type="button"
                        class="p-1.5 text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-full transition-colors focus:outline-none"
                        onclick="toggleUnitDropdown('grid-unit-dropdown-{{ $unit->uuid }}', event)"
                        title="Actions">
                        <i data-lucide="more-vertical" class="w-4 h-4"></i>
                    </button>

                    <div id="grid-unit-dropdown-{{ $unit->uuid }}"
                        class="unit-action-dropdown hidden absolute right-0 bottom-8 w-40 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                        {{-- Edit --}}
                        <button type="button"
                            class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-blue-50 hover:text-blue-600 transition-colors flex items-center gap-2"
                            onclick="event.stopPropagation(); document.getElementById('grid-unit-dropdown-{{ $unit->uuid }}').classList.add('hidden'); editUnit({{ $unit->uuid }})">
                            <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit Unit
                        </button>
                        {{-- Archive --}}
                        <form method="POST" action="{{ route('units.destroy', $unit->uuid) }}"
                            onsubmit="return confirm('Archive unit {{ $unit->plate_number }}? It will be moved to the Archive page.');"
                            class="m-0 p-0">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="event.stopPropagation()"
                                class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 transition-colors flex items-center gap-2 border-t border-slate-50">
                                <i data-lucide="archive" class="w-3.5 h-3.5"></i> Archive Unit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center">
            <i data-lucide="car" class="w-16 h-16 mx-auto mb-4 text-slate-300"></i>
            <h4 class="text-slate-900 font-black text-xl">No units found</h4>
            <p class="text-slate-500 mt-1 italic text-sm">Try adjusting your filters.</p>
        </div>
    @endforelse
</div>

@if($pagination['total_pages'] > 1)
    <div class="px-8 py-6 bg-white border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">
            Page <span class="text-slate-900">{{ $pagination['page'] }}</span> of <span class="text-slate-900">{{ $pagination['total_pages'] }}</span>
        </div>
        <div class="flex items-center gap-1.5">
            @if($pagination['has_prev'])
                <button onclick="changePage({{ $pagination['prev_page'] }})" class="p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-all">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </button>
            @endif
            @for($i = max(1, $pagination['page'] - 2); $i <= min($pagination['total_pages'], $pagination['page'] + 2); $i++)
                <button onclick="changePage({{ $i }})" class="w-10 h-10 rounded-xl border text-sm font-black transition-all {{ $i === $pagination['page'] ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-white border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                    {{ $i }}
                </button>
            @endfor
            @if($pagination['has_next'])
                <button onclick="changePage({{ $pagination['next_page'] }})" class="p-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-all">
                    <i data-lucide="chevron-right" class="w-5 h-5"></i>
                </button>
            @endif
        </div>
    </div>
@endif

{{-- ── 21st.dev Interactive 3D Perspective Card Script ── --}}
<script>
    function handleUnitCardTilt(e, card) {
        if (window.innerWidth < 768) return; // Disable tilt on mobile for performance
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const rotateX = ((y - rect.height / 2) / (rect.height / 2)) * -6; // Max 6deg
        const rotateY = ((x - rect.width / 2) / (rect.width / 2)) * 6;   // Max 6deg

        card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) scale3d(1.02, 1.02, 1.02)`;
        card.style.boxShadow = '0 20px 25px -5px rgba(15, 23, 42, 0.1), 0 8px 10px -6px rgba(15, 23, 42, 0.05)';
        card.style.transition = 'transform 0.08s ease-out, box-shadow 0.08s ease-out';
    }

    function resetUnitCardTilt(card) {
        card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
        card.style.boxShadow = '';
        card.style.transition = 'transform 0.45s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.45s ease-out';
    }
</script>
