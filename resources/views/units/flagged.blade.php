@extends('layouts.app')

@section('title', 'Flagged Units - Euro System')
@section('page-heading', 'Flagged Units')
@section('page-subheading', 'Units reported missing or automatically flagged by the system due to boundary delays')

@section('content')
<style>
    .flag-card {
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .flag-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 30px -8px rgba(0,0,0,0.08);
    }
    .fade-remove {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
        transition: all 0.5s ease;
        pointer-events: none;
    }
    .pulse-ring {
        animation: pulse-ring 1.8s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    @keyframes pulse-ring {
        0%   { box-shadow: 0 0 0 0 rgba(239,68,68,0.45); }
        70%  { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
        100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
    }
    .pulse-ring-amber {
        animation: pulse-ring-amber 1.8s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    @keyframes pulse-ring-amber {
        0%   { box-shadow: 0 0 0 0 rgba(245,158,11,0.45); }
        70%  { box-shadow: 0 0 0 10px rgba(245,158,11,0); }
        100% { box-shadow: 0 0 0 0 rgba(245,158,11,0); }
    }
</style>

<div class="space-y-8">

    {{-- ── Hero Header Panel ──────────────────────────────── --}}
    <div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-zinc-900 rounded-[2.25rem] p-6 sm:p-8 overflow-hidden shadow-2xl border border-slate-800">
        <!-- SVG Decorative Mesh / Glow -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-red-600/15 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-amber-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <svg class="absolute right-0 bottom-0 w-96 h-96 opacity-5 pointer-events-none" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 0C44.7715 0 0 44.7715 0 100C0 155.228 44.7715 200 100 200C155.228 200 200 155.228 200 100C200 44.7715 155.228 0 100 0Z" stroke="white" stroke-width="2" stroke-dasharray="8 8"/>
            <path d="M100 30C61.3401 30 30 61.3401 30 100C30 138.66 61.3401 170 100 170C138.66 170 170 138.66 170 100C170 61.3401 138.66 30 100 30Z" stroke="white" stroke-width="1.5"/>
            <path d="M100 60C77.9086 60 60 77.9086 60 100C60 122.091 77.9086 140 100 140C122.091 140 140 122.091 140 100C140 77.9086 122.091 60 100 60Z" stroke="white" stroke-width="1"/>
        </svg>

        <div class="relative flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-orange-500 via-rose-500 to-red-600 rounded-2xl flex items-center justify-center shadow-xl shadow-red-500/25 pulse-ring shrink-0 border border-white/20">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                        <line x1="4" y1="22" x2="4" y2="15"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight">Flagged Units Registry</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-500/20 text-red-400 border border-red-500/30 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 animate-ping"></span> Live Radar
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium max-w-xl leading-relaxed">
                        Central registry for units manually reported as missing/stolen or automatically flagged by the system due to boundary delays exceeding 48 hours.
                    </p>
                </div>
            </div>

            {{-- Stats row with modern glassmorphism & SVG icons --}}
            <div class="flex flex-wrap sm:flex-nowrap gap-3 shrink-0 w-full lg:w-auto">
                <div class="flex-1 sm:flex-initial flex items-center gap-3 bg-red-500/10 hover:bg-red-500/15 transition-all px-4 sm:px-5 py-3 rounded-2xl border border-red-500/25 backdrop-blur-md shadow-lg shadow-red-500/5 min-w-[130px]">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-400 shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-black text-red-300 uppercase tracking-widest block">Total Flagged</span>
                        <span class="text-2xl sm:text-3xl font-black text-red-400 tracking-tight" id="total-flagged-count">{{ $flaggedCount }}</span>
                    </div>
                </div>

                <div class="flex-1 sm:flex-initial flex items-center gap-3 bg-slate-800/60 hover:bg-slate-800/80 transition-all px-4 sm:px-5 py-3 rounded-2xl border border-slate-700/50 backdrop-blur-md min-w-[130px]">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-700/50 flex items-center justify-center text-slate-300 shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest block">Missing</span>
                        <span class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $stolenCount }}</span>
                    </div>
                </div>

                <div class="flex-1 sm:flex-initial flex items-center gap-3 bg-slate-800/60 hover:bg-slate-800/80 transition-all px-4 sm:px-5 py-3 rounded-2xl border border-slate-700/50 backdrop-blur-md min-w-[130px]">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-700/50 flex items-center justify-center text-amber-400 shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-black text-slate-400 uppercase tracking-widest block">Auto-Detected</span>
                        <span class="text-2xl sm:text-3xl font-black text-white tracking-tight">{{ $autoCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Controls Bar ─────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-4 justify-between items-center bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">

        @php $searchId = 'search_' . Str::random(10); @endphp
        <div class="relative w-full lg:max-w-xs">
            <!-- Ultimate Chrome Autofill Defeater -->
            <input type="email" name="fake_email_1" style="opacity: 0; position: absolute; height: 1px; width: 1px; z-index: -1;" tabindex="-1" autocomplete="username">
            <input type="password" name="fake_pass_1" style="opacity: 0; position: absolute; height: 1px; width: 1px; z-index: -1;" tabindex="-1" autocomplete="current-password">
            
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </span>
            <input type="text" id="{{ $searchId }}" name="{{ $searchId }}" placeholder="Search plate, make, model, driver…"
                   autocomplete="new-password" spellcheck="false" role="presentation"
                   class="js-flag-search w-full pl-11 pr-4 py-3 text-sm border-2 border-gray-100 rounded-xl focus:border-orange-400/40 focus:ring-4 focus:ring-orange-400/8 transition-all outline-none bg-slate-50/50">
        </div>

        {{-- Filter tabs --}}
        <div class="flex items-center gap-2 w-full lg:w-auto overflow-x-auto py-1 scrollbar-none">
            <button type="button" data-filter="all" onclick="setFilter('all')"
                class="filter-tab-btn px-4 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl transition-all shadow-md whitespace-nowrap">
                All Flagged
            </button>
            <button type="button" data-filter="manual_stolen" onclick="setFilter('manual_stolen')"
                class="filter-tab-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all whitespace-nowrap">
                Missing / Stolen
            </button>
            <button type="button" data-filter="auto_boundary" onclick="setFilter('auto_boundary')"
                class="filter-tab-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all whitespace-nowrap">
                Auto-Detected
            </button>
        </div>

        {{-- Back button & Add Flag --}}
        <div class="shrink-0 w-full lg:w-auto flex flex-col sm:flex-row justify-end gap-3">
            <button type="button" onclick="openManualFlagModal()"
                    class="flex items-center justify-center gap-2 px-5 py-3 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-black rounded-xl transition-all border border-red-200 hover:border-red-300 w-full sm:w-auto">
                <i data-lucide="flag" class="w-4 h-4"></i> Flag Unit Manually
            </button>
            <a href="{{ route('units.index') }}"
               class="flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-slate-800 transition-all active:scale-95 shadow-lg w-full sm:w-auto">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Units
            </a>
        </div>
    </div>

    {{-- ── Grid of Cards ──────────────────────────────────── --}}
    <div id="flaggedGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

        @forelse($allFlagged as $unit)
        @php
            $isMissing      = $unit->flag_source === 'manual_stolen';
            $isAuto         = $unit->flag_source === 'auto_boundary';

            $badgeText  = $isMissing ? 'Missing / Stolen' : 'Auto-Flagged';
            $badgeCss   = $isMissing
                ? 'bg-red-50 text-red-700 border-red-200'
                : 'bg-amber-50 text-amber-700 border-amber-200';
            $gradientCss = $isMissing
                ? 'from-red-500 to-rose-600 shadow-red-500/20'
                : 'from-orange-500 to-amber-600 shadow-orange-500/20';
            $pulseClass = $isMissing ? 'pulse-ring' : 'pulse-ring-amber';

            $dPhoto = $unit->suspect_photo ?? asset('image/avatars/driver.svg');
            $dName = $unit->suspect_driver ?? 'Unknown Driver';
            $dContact = $unit->suspect_contact ?? '—';
        @endphp

        <div class="flag-card bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:border-orange-300 transition-all duration-300 overflow-hidden flex flex-col justify-between"
             id="flagcard-{{ $unit->uuid }}"
             data-flag-source="{{ $unit->flag_source }}"
             data-search-terms="{{ strtolower($unit->plate_number . ' ' . $unit->make . ' ' . $unit->model . ' ' . ($unit->suspect_driver ?? '') . ' ' . ($unit->last_known_driver ?? '')) }}">

            {{-- 1. Card Header --}}
            <div class="p-5 sm:p-6 bg-gradient-to-r from-slate-50 via-slate-50/70 to-orange-50/30 border-b border-slate-100 flex items-start justify-between gap-3">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 bg-gradient-to-br {{ $gradientCss }} rounded-2xl flex items-center justify-center text-white text-base font-black shrink-0 shadow-md {{ $pulseClass }}">
                        <i data-lucide="{{ $isMissing ? 'alert-triangle' : 'clock' }}" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="text-lg font-black text-slate-900 tracking-tight">{{ $unit->plate_number }}</h4>
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider border {{ $badgeCss }}">{{ $badgeText }}</span>
                        </div>
                        <p class="text-xs text-slate-500 font-bold mt-0.5">{{ $unit->make }} {{ $unit->model }} @if(!empty($unit->year))({{ $unit->year }})@endif</p>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Unit ID: UNT-{{ str_pad($unit->uuid, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>

                <div class="text-right shrink-0">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Days Inactive</span>
                    <span class="inline-flex items-center gap-1 font-black text-xs px-2.5 py-1 rounded-full {{ ($unit->days_inactive ?? 0) > 7 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-amber-50 text-amber-700 border border-amber-200' }} mt-0.5">
                        <i data-lucide="clock" class="w-3 h-3"></i>
                        {{ $unit->days_inactive !== null ? $unit->days_inactive . ' day(s)' : 'N/A' }}
                    </span>
                </div>
            </div>

            {{-- 2. Card Body --}}
            <div class="p-5 sm:p-6 flex-1 space-y-4">
                
                {{-- 👤 Suspect / Assigned Driver Profile Card with Picture --}}
                <div class="p-3.5 bg-slate-50/80 hover:bg-slate-100/80 transition-colors rounded-2xl border border-slate-200/60 flex items-center gap-3.5 group">
                    <div class="relative w-13 h-13 sm:w-14 sm:h-14 rounded-full overflow-hidden border-2 border-amber-400 bg-slate-200 flex-shrink-0 cursor-pointer shadow-xs"
                         onclick="event.stopPropagation(); if(typeof openImageModal==='function'){ openImageModal('{{ $dPhoto }}'); }"
                         title="Click to view driver photo">
                        <img src="{{ $dPhoto }}" alt="{{ $dName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1">
                            <span class="text-[9px] font-black uppercase tracking-wider text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200/60">
                                {{ $isMissing ? 'Suspect Driver' : 'Assigned Driver' }}
                            </span>
                            @if(!empty($unit->missing_since))
                                <span class="text-[10px] font-bold text-slate-400">Since {{ $unit->missing_since }}</span>
                            @endif
                        </div>
                        <h5 class="text-sm font-extrabold text-slate-900 truncate mt-1 group-hover:text-blue-600 transition-colors">{{ $dName }}</h5>
                        <p class="text-xs text-slate-500 font-medium flex items-center gap-1 mt-0.5">
                            <i data-lucide="phone" class="w-3 h-3 text-slate-400 shrink-0"></i>
                            <span class="truncate">{{ $dContact }}</span>
                        </p>
                    </div>
                </div>

                {{-- Description / Delay Reason Box --}}
                @if(!empty($unit->description))
                <div class="p-3 bg-amber-50/60 rounded-xl border border-amber-200/50 flex items-start gap-2.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-950 font-medium leading-relaxed">{{ $unit->description }}</p>
                </div>
                @endif

                {{-- Last Boundary & Last Known Driver --}}
                <div class="grid grid-cols-2 gap-3 pt-2 border-t border-slate-100 text-xs">
                    <div class="space-y-0.5">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Last Boundary</span>
                        <span class="font-bold text-slate-700 block text-[11px]">{{ $unit->last_boundary_date ?? 'No record' }}</span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block">Last Known Driver</span>
                        <span class="font-bold text-slate-700 block text-[11px] truncate">{{ $unit->last_known_driver ?? 'None' }}</span>
                    </div>
                </div>

                @if(!empty($unit->stolen_driver_license_no))
                <div class="flex items-center gap-2 p-2 bg-red-50 rounded-xl border border-red-100 text-xs">
                    <i data-lucide="credit-card" class="w-3.5 h-3.5 text-red-500 shrink-0"></i>
                    <span class="text-[9px] font-black text-red-500 uppercase tracking-wider">License:</span>
                    <span class="font-bold text-red-800 font-mono">{{ $unit->stolen_driver_license_no }}</span>
                </div>
                @endif
            </div>

            {{-- 3. Card Footer Actions --}}
            <div class="p-4 sm:p-5 border-t border-slate-100 bg-slate-50/80 flex items-center justify-between gap-2">
                <button type="button" onclick="viewUnitDetails({{ $unit->uuid }})"
                   class="flex items-center gap-1.5 px-3.5 py-2 text-xs font-black text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-100 hover:border-slate-300 transition-all shadow-xs cursor-pointer">
                    <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-500"></i> View Details
                </button>
                
                @if($isMissing)
                <button type="button"
                        onclick="recoverUnit({{ $unit->uuid }}, '{{ $unit->plate_number }}')"
                        class="flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl transition-all active:scale-95 shadow-md shadow-emerald-500/20 cursor-pointer">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> Mark Recovered
                </button>
                @else
                <div class="flex items-center gap-2">
                    <button type="button"
                            onclick="ignoreFlag({{ $unit->uuid }}, '{{ $unit->plate_number }}')"
                            class="flex items-center gap-1.5 px-3 py-2 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold rounded-xl transition-all cursor-pointer"
                            title="Postpone this alert for 24 hours">
                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-500"></i> Ignore
                    </button>
                    <button type="button"
                            onclick="openManualFlagModal({{ $unit->uuid }})"
                            class="flex items-center gap-1.5 px-3.5 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-black rounded-xl transition-all active:scale-95 shadow-md shadow-red-500/20 cursor-pointer">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Mark Missing
                    </button>
                </div>
                @endif
            </div>
        </div>
        @empty

        {{-- Empty state --}}
        <div id="empty-flagged-state" class="col-span-1 md:col-span-2 xl:col-span-3 flex flex-col items-center justify-center py-28 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-emerald-100">
                <i data-lucide="check-circle-2" class="w-10 h-10 animate-bounce"></i>
            </div>
            <h4 class="text-xl font-black text-slate-800 mb-2">No Flagged Units</h4>
            <p class="text-sm text-slate-500 max-w-xs text-center font-medium leading-relaxed">
                All units are operating normally with no missing reports or overdue boundaries.
            </p>
        </div>

        @endforelse
    </div>

    {{-- No results from search --}}
    <div id="noSearchResults" class="hidden flex-col items-center justify-center py-20 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
        <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mb-4">
            <i data-lucide="search-x" class="w-8 h-8"></i>
        </div>
        <h4 class="text-base font-black text-slate-800 mb-1">No Matching Units</h4>
        <p class="text-xs text-slate-400">Try a different plate number, make, or model.</p>
    </div>

</div>

{{-- ── Manual Flag Modal ──────────────────────────────── --}}
<div id="manualFlagModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="manualFlagBackdrop" onclick="closeManualFlagModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4 pointer-events-none">
        <div class="bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all pointer-events-auto border border-slate-100 mx-auto" id="manualFlagPanel">
            <form action="{{ route('units.flag-manually') }}" method="POST" class="flex flex-col h-full max-h-[90vh]">
                @csrf
                {{-- Modal Header --}}
                <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-slate-50/50 rounded-t-[2rem]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center shadow-inner">
                            <i data-lucide="flag-triangle-right" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Flag Unit Manually</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Mark as Missing or Stolen</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeManualFlagModal()" class="w-10 h-10 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-200/50 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Select Unit --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                <i data-lucide="car" class="w-3.5 h-3.5"></i> Select Unit
                            </label>
                            
                            <div class="relative w-full" id="unitSearchContainer">
                                <input type="hidden" name="unit_id" id="unit_select_id" required>
                                <div class="relative">
                                    <input type="text" id="unitDisplay" 
                                        placeholder="Type to search units..."
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-400 transition-all placeholder:font-normal placeholder:text-slate-400"
                                        autocomplete="off">
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
                                </div>
                                <div id="unitDropdown" class="hidden absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-64 overflow-y-auto custom-scrollbar">
                                    <div id="unitList" class="p-1.5 space-y-0.5">
                                        <div class="unit-option px-3 py-2.5 rounded-lg hover:bg-slate-50 cursor-pointer text-xs font-bold text-slate-500 transition-colors" data-id="" data-primary="" data-secondary="">
                                            -- Choose Unit --
                                        </div>
                                        @foreach($availableUnits as $u)
                                            <div class="unit-option px-3 py-2.5 rounded-lg hover:bg-slate-50 cursor-pointer transition-all border border-transparent hover:border-slate-200" 
                                                 data-id="{{ $u->uuid }}" 
                                                 data-primary="{{ $u->primary_driver_id ?? '' }}" 
                                                 data-secondary="{{ $u->secondary_driver_id ?? '' }}"
                                                 data-search="{{ strtolower($u->plate_number . ' ' . $u->make . ' ' . $u->model) }}">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="font-black text-slate-800 text-sm tracking-tight">{{ $u->plate_number }}</div>
                                                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $u->make }} {{ $u->model }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Select Flag Type (Hidden) --}}
                        <input type="hidden" name="flag_type" value="missing">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Select Suspect Driver --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                <i data-lucide="user-x" class="w-3.5 h-3.5"></i> Suspect Driver (Optional)
                            </label>
                            
                            <div class="relative w-full" id="driverSearchContainer">
                                <input type="hidden" name="suspect_driver_id" id="suspect_driver_id">
                                <div class="relative">
                                    <input type="text" id="driverDisplay" 
                                           class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-400 transition-all shadow-sm"
                                           placeholder="Type to search drivers..." autocomplete="off">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                </div>
                                
                                <div id="driver_dropdown" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-xl max-h-48 overflow-y-auto hidden">
                                    <div class="all-drivers-list flex flex-col">
                                        <div class="driver-option px-3 py-2 hover:bg-red-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                             data-id="" data-name="-- No Specific Driver --" onclick="selectDriver('', '-- No Specific Driver --')">
                                            <div class="font-black text-sm text-gray-900">-- No Specific Driver --</div>
                                        </div>
                                        @foreach($availableDrivers as $d)
                                            <div class="driver-option px-3 py-2 hover:bg-red-50 cursor-pointer border-b border-gray-100 last:border-b-0"
                                                 data-id="{{ $d->uuid }}"
                                                 data-name="{{ $d->first_name }} {{ $d->last_name }}"
                                                 onclick="selectDriver('{{ $d->uuid }}', '{{ addslashes($d->first_name . ' ' . $d->last_name) }}')">
                                                <div class="font-black text-sm text-gray-900">{{ $d->first_name }} {{ $d->last_name }}</div>
                                                <div class="text-[11px] font-bold text-gray-500">{{ $d->contact_number }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div id="ban_driver_container" class="hidden mt-2 p-3 bg-red-50 border border-red-100 rounded-lg flex items-start gap-2">
                                <input type="checkbox" name="ban_driver" id="ban_driver" value="1" class="mt-0.5 rounded text-red-600 focus:ring-red-500 border-gray-300">
                                <label for="ban_driver" class="text-xs text-red-800 font-medium leading-tight cursor-pointer">
                                    <strong>Ban this driver?</strong><br>
                                    Check this if the driver is suspected of stealing the unit. Leave unchecked if the unit was Carnapped/Hijacked and the driver is a victim.
                                </label>
                            </div>
                        </div>

                        {{-- Missing Since Date --}}
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center justify-between gap-1.5">
                                <span class="flex items-center gap-1.5"><i data-lucide="calendar-off" class="w-3.5 h-3.5"></i> Missing / Flagged Since</span>
                                <span id="days-ago-badge" class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-[9px] font-bold">0 day(s) missing</span>
                            </label>
                            <input type="date" name="missing_since" value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-400 transition-all">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i> Reason / Description
                        </label>
                        <textarea name="description" required rows="4" minlength="5" maxlength="1000" placeholder="E.g., Hindi na nagrereply sa texts, huling boundary ay nung..."
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-4 focus:ring-red-500/10 focus:border-red-400 transition-all resize-none"></textarea>
                    </div>

                    {{-- Alert Box --}}
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 flex gap-3">
                        <i data-lucide="info" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"></i>
                        <p class="text-xs text-amber-700 font-medium leading-relaxed">
                            Flagging a unit as <strong>Missing/Stolen</strong> will log a critical incident on the suspect driver's behavior record (if selected).<br> The unit will remain flagged until manually recovered.
                        </p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-6 border-t border-gray-50 bg-slate-50/50 flex justify-end gap-3 rounded-b-[2rem]">
                    <button type="button" onclick="closeManualFlagModal()" class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 text-sm font-black text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-md shadow-red-500/20 active:scale-95 transition-all flex items-center gap-2">
                        <i data-lucide="flag" class="w-4 h-4"></i> Submit Flag
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let currentFilter = 'all';

    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.querySelector('input[name="missing_since"]');
        const updateDaysAgo = () => {
            if (!dateInput || !dateInput.value) return;
            const selectedDate = new Date(dateInput.value);
            const today = new Date();
            selectedDate.setHours(0,0,0,0);
            today.setHours(0,0,0,0);
            
            const diffTime = today - selectedDate;
            const diffDays = Math.max(0, Math.floor(diffTime / (1000 * 60 * 60 * 24)));
            
            const badge = document.getElementById('days-ago-badge');
            if (badge) {
                badge.textContent = `${diffDays} day(s) missing`;
            }
        };
        
        dateInput?.addEventListener('change', updateDaysAgo);
        updateDaysAgo();

        let currentPrimaryDriverId = null;
        let currentSecondaryDriverId = null;

        // Open/Close dropdown
        const driverDisplay = document.getElementById('driverDisplay');
        const driverDropdown = document.getElementById('driver_dropdown');
        if (driverDisplay && driverDropdown) {
            driverDisplay.addEventListener('click', () => {
                driverDropdown.classList.toggle('hidden');
                filterDrivers(driverDisplay.value.toLowerCase()); // apply badges
            });
            // Search filter on input
            driverDisplay.addEventListener('input', function() {
                driverDropdown.classList.remove('hidden');
                filterDrivers(this.value.toLowerCase());
            });
            
            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!document.getElementById('driverSearchContainer').contains(e.target)) {
                    driverDropdown.classList.add('hidden');
                }
            });
        }

        window.selectDriver = function(id, name) {
            document.getElementById('suspect_driver_id').value = id;
            document.getElementById('driverDisplay').value = name === '-- No Specific Driver --' ? '' : name;
            document.getElementById('driver_dropdown').classList.add('hidden');
            
            // Show/Hide Ban Checkbox
            const banContainer = document.getElementById('ban_driver_container');
            if (banContainer) {
                if (id) {
                    banContainer.classList.remove('hidden');
                } else {
                    banContainer.classList.add('hidden');
                    const banCb = document.getElementById('ban_driver');
                    if(banCb) banCb.checked = false;
                }
            }
        };

        window.filterDrivers = function(searchTerm) {
            const driverOptions = document.querySelectorAll('.driver-option');
            driverOptions.forEach(option => {
                const driverName = option.getAttribute('data-name').toLowerCase();
                const driverId = option.getAttribute('data-id');
                
                if (driverName.includes(searchTerm) || searchTerm === '') {
                    option.style.display = 'block';
                    
                    const isSuggested = driverId && (driverId == currentPrimaryDriverId || driverId == currentSecondaryDriverId);
                    if (isSuggested) {
                        option.style.order = '-1';
                        option.classList.remove('hover:bg-red-50');
                        option.classList.add('bg-red-50', 'border-l-4', 'border-red-500', 'hover:bg-red-100');
                        let nameDiv = option.querySelector('.font-black');
                        if (nameDiv && !option.querySelector('.suggested-badge')) {
                            nameDiv.innerHTML += ' <span class="suggested-badge ml-2 px-1.5 py-0.5 bg-red-500 text-white text-[10px] rounded-full shadow-sm font-bold">Recommended</span>';
                        }
                    } else {
                        option.style.order = '0';
                        option.classList.remove('bg-red-50', 'border-l-4', 'border-red-500', 'hover:bg-red-100');
                        option.classList.add('hover:bg-red-50');
                        let badge = option.querySelector('.suggested-badge');
                        if (badge) badge.remove();
                    }
                } else {
                    option.style.display = 'none';
                }
            });
        };

        // Unit Dropdown Logic
        const unitDisplay = document.getElementById('unitDisplay');
        const unitDropdown = document.getElementById('unitDropdown');
        const unitSelectId = document.getElementById('unit_select_id');
        
        if (unitDisplay && unitDropdown) {
            // Toggle dropdown
            unitDisplay.addEventListener('click', (e) => {
                e.stopPropagation();
                unitDropdown.classList.toggle('hidden');
                
                // Show all if empty
                if (unitDisplay.value === '') {
                    document.querySelectorAll('.unit-option').forEach(opt => opt.style.display = 'block');
                }
            });
            
            // Search filtering directly on unitDisplay
            unitDisplay.addEventListener('input', (e) => {
                unitDropdown.classList.remove('hidden');
                unitSelectId.value = ''; // clear hidden ID when they start typing
                
                const term = e.target.value.toLowerCase();
                document.querySelectorAll('.unit-option').forEach(opt => {
                    const searchData = opt.getAttribute('data-search') || '';
                    const isPlaceholder = opt.getAttribute('data-id') === '';
                    if (isPlaceholder || searchData.includes(term)) {
                        opt.style.display = 'block';
                    } else {
                        opt.style.display = 'none';
                    }
                });
            });

            // Selection
            document.querySelectorAll('.unit-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    const id = opt.getAttribute('data-id');
                    const primary = opt.getAttribute('data-primary');
                    const secondary = opt.getAttribute('data-secondary');
                    const plateElem = opt.querySelector('.font-black');
                    const makeModelElem = opt.querySelector('.text-\\[10px\\]');
                    
                    let displayText = '-- Choose Unit --';
                    if (id && plateElem) {
                        displayText = plateElem.innerText + (makeModelElem ? ' (' + makeModelElem.innerText + ')' : '');
                    }
                    
                    unitSelectId.value = id || '';
                    unitDisplay.value = id ? displayText : '';
                    unitDropdown.classList.add('hidden');
                    
                    // Trigger driver auto-select logic
                    currentPrimaryDriverId = primary;
                    currentSecondaryDriverId = secondary;
                    
                    if (currentPrimaryDriverId || currentSecondaryDriverId) {
                        const autoDriverId = currentPrimaryDriverId || currentSecondaryDriverId;
                        const autoDriverOpt = document.querySelector(`.driver-option[data-id="${autoDriverId}"]`);
                        if (autoDriverOpt) {
                            const name = autoDriverOpt.getAttribute('data-name');
                            window.selectDriver(autoDriverId, name);
                        }
                    } else {
                        window.selectDriver('', '-- No Specific Driver --');
                    }
                    
                    window.filterDrivers(document.getElementById('driverDisplay').value.toLowerCase());
                });
            });

            // Close when clicking outside
            document.addEventListener('click', (e) => {
                if (!document.getElementById('unitSearchContainer').contains(e.target)) {
                    unitDropdown.classList.add('hidden');
                }
            });
        }
    });

    function openManualFlagModal(preselectUnitId = null) {
        const modal = document.getElementById('manualFlagModal');
        const backdrop = document.getElementById('manualFlagBackdrop');
        const panel = document.getElementById('manualFlagPanel');
        
        if (preselectUnitId) {
            const select = document.querySelector('select[name="unit_id"]');
            if (select) {
                select.value = preselectUnitId;
                select.dispatchEvent(new Event('change'));
            }
        } else {
            const select = document.querySelector('select[name="unit_id"]');
            if (select) {
                select.value = "";
                select.dispatchEvent(new Event('change'));
            }
        }
        
        modal.classList.remove('hidden');
        // trigger reflow
        void modal.offsetWidth;
        
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        
        panel.classList.remove('scale-95', 'opacity-0');
        panel.classList.add('scale-100', 'opacity-100');
    }

    function closeManualFlagModal() {
        const modal = document.getElementById('manualFlagModal');
        const backdrop = document.getElementById('manualFlagBackdrop');
        const panel = document.getElementById('manualFlagPanel');
        
        if (backdrop) {
            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            backdrop.style.setProperty('display', 'none', 'important');
        }
        if (panel) {
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            panel.style.setProperty('display', 'none', 'important');
        }
        if (modal) {
            modal.classList.add('hidden');
            modal.style.setProperty('display', 'none', 'important');
            modal.style.setProperty('z-index', '-1', 'important');
        }
    }

    function filterCards() {
        const searchInput = document.querySelector('.js-flag-search');
        const query = (searchInput?.value || '').trim().toLowerCase();
        const cards = document.querySelectorAll('.flag-card');
        const noResults = document.getElementById('noSearchResults');
        let visible = 0;

        cards.forEach(card => {
            const terms  = card.getAttribute('data-search-terms') || '';
            const source = card.getAttribute('data-flag-source') || '';
            const matchSearch = !query || terms.includes(query);
            const matchFilter = currentFilter === 'all' || source === currentFilter;

            if (matchSearch && matchFilter) {
                card.classList.remove('hidden');
                visible++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (noResults) {
            if (visible === 0 && cards.length > 0) {
                noResults.classList.remove('hidden');
                noResults.classList.add('flex');
            } else {
                noResults.classList.add('hidden');
                noResults.classList.remove('flex');
            }
        }
    }

    function setFilter(filter) {
        currentFilter = filter;
        document.querySelectorAll('.filter-tab-btn').forEach(btn => {
            const active = btn.getAttribute('data-filter') === filter;
            btn.className = active
                ? 'filter-tab-btn px-4 py-2.5 bg-slate-900 text-white text-xs font-black rounded-xl transition-all shadow-md whitespace-nowrap'
                : 'filter-tab-btn px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all whitespace-nowrap';
        });
        filterCards();
    }

    const searchInput = document.querySelector('.js-flag-search');
    searchInput?.addEventListener('input', filterCards);

    function recoverUnit(unitId, plateName) {
        if (!confirm(`Mark unit "${plateName}" as RECOVERED?\nThis will reset its status back to Active.`)) return;

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(`/units/${unitId}/recover`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('flagcard-' + unitId);
                if (card) {
                    card.classList.add('fade-remove');
                    setTimeout(() => {
                        card.remove();

                        // Update total count badge
                        const badge = document.getElementById('total-flagged-count');
                        if (badge) {
                            const curr = parseInt(badge.textContent) || 0;
                            badge.textContent = Math.max(0, curr - 1);
                        }

                        // If grid is empty, show empty state
                        const remaining = document.querySelectorAll('.flag-card:not(.hidden)').length;
                        if (remaining === 0) {
                            const grid = document.getElementById('flaggedGrid');
                            if (grid) {
                                grid.innerHTML = `
                                    <div class="col-span-1 md:col-span-2 xl:col-span-3 flex flex-col items-center justify-center py-28 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                                        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-emerald-100">
                                            <i data-lucide="check-circle-2" class="w-10 h-10 animate-bounce"></i>
                                        </div>
                                        <h4 class="text-xl font-black text-slate-800 mb-2">No Flagged Units</h4>
                                        <p class="text-sm text-slate-500 max-w-xs text-center font-medium leading-relaxed">All units are operating normally.</p>
                                    </div>`;
                                if (typeof lucide !== 'undefined') lucide.createIcons();
                            }
                        }
                    }, 500);
                }

                if (typeof showNotification === 'function') {
                    showNotification(data.message, 'success');
                } else {
                    alert(data.message);
                }
            } else {
                alert('Error: ' + (data.message || 'Failed to recover unit.'));
            }
        })
        .catch(err => {
            console.error('Recover error:', err);
            alert('Network error. Please try again.');
        });
    }

    function ignoreFlag(unitId, plateName) {
        if (!confirm(`Ignore the auto-detected flag for unit "${plateName}"?\nThis will hide the alert for today, but it will reappear tomorrow if no boundary is submitted.`)) return;

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        fetch(`/units/${unitId}/ignore-flag`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const card = document.getElementById('flagcard-' + unitId);
                if (card) {
                    card.classList.add('fade-remove');
                    setTimeout(() => {
                        card.remove();

                        // Update total count badge
                        const badge = document.getElementById('total-flagged-count');
                        if (badge) {
                            const curr = parseInt(badge.textContent) || 0;
                            badge.textContent = Math.max(0, curr - 1);
                        }

                        // If grid is empty, show empty state
                        const remaining = document.querySelectorAll('.flag-card:not(.hidden)').length;
                        if (remaining === 0) {
                            const grid = document.getElementById('flaggedGrid');
                            if (grid) {
                                grid.innerHTML = `
                                    <div class="col-span-1 md:col-span-2 xl:col-span-3 flex flex-col items-center justify-center py-28 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200">
                                        <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-emerald-100">
                                            <i data-lucide="check-circle-2" class="w-10 h-10 animate-bounce"></i>
                                        </div>
                                        <h4 class="text-xl font-black text-slate-800 mb-2">No Flagged Units</h4>
                                        <p class="text-sm text-slate-500 max-w-xs text-center font-medium leading-relaxed">All units are operating normally.</p>
                                    </div>`;
                                if (typeof lucide !== 'undefined') lucide.createIcons();
                            }
                        }
                    }, 500);
                }

                if (typeof showNotification === 'function') {
                    showNotification(data.message, 'success');
                } else {
                    alert(data.message);
                }
            } else {
                alert('Error: ' + (data.message || 'Failed to ignore flag.'));
            }
        })
        .catch(err => {
            console.error('Ignore error:', err);
            alert('Network error. Please try again.');
        });
    }
</script>
@include('units.partials._unit_details_shared')

@endsection

