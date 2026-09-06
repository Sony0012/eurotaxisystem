@extends('layouts.app')

@section('title', 'Financial Liabilities — Euro Taxi Fleet')
@section('page-heading', 'Financial Liabilities')
@section('page-subheading', 'Track and manage driver accident charges, parts, and boundary shortages')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ── General ── */

/* ── Driver summary cards ── */
.driver-card {
    cursor: pointer;
    transition: box-shadow .2s ease, transform .15s ease, border-color .2s;
    position: relative;
}
.driver-card:hover {
    box-shadow: 0 8px 32px -4px rgba(15,23,42,.14);
    transform: translateY(-2px);
}
.driver-card.selected {
    border-color: #0f172a !important;
    box-shadow: 0 0 0 3px rgba(15,23,42,.12), 0 8px 32px -4px rgba(15,23,42,.2);
}

/* ── Modal overlay ── */
#driver-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
    background: rgba(15,23,42,.55);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    opacity: 0;
    pointer-events: none;
    transition: opacity .22s ease;
}
#driver-modal-overlay.open {
    opacity: 1;
    pointer-events: all;
}
#driver-modal-box {
    width: 100%;
    max-width: 860px;
    max-height: 88vh;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 32px 80px -12px rgba(15,23,42,.45);
    transform: scale(.96) translateY(12px);
    transition: transform .25s cubic-bezier(0.34,1.56,0.64,1);
}
#driver-modal-overlay.open #driver-modal-box {
    transform: scale(1) translateY(0);
}
#driver-modal-body {
    overflow-y: auto;
    flex: 1;
}

/* ── Tabs ── */
.tab-pill.active {
    background: #0f172a;
    color: #fff;
    box-shadow: 0 2px 8px rgba(15,23,42,.18);
}
.tab-pill { transition: background .18s, color .18s; }

/* ── Debt type badges ── */
.badge-shortage { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }
.badge-damage   { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
.badge-parts    { background:#fffbeb; color:#b45309; border:1px solid #fde68a; }
.badge-general  { background:#f8fafc; color:#475569; border:1px solid #e2e8f0; }

/* ── Progress bar ── */
.pbar-track { background:#f1f5f9; border-radius:99px; height:5px; overflow:hidden; }
.pbar-fill  { height:100%; border-radius:99px;
    background: linear-gradient(90deg,#ef4444,#f97316);
    transition: width .6s ease; }

/* ── Pay input ── */
.pay-input { transition: border-color .15s, box-shadow .15s; }
.pay-input:focus {
    outline: none;
    border-color: #ef4444;
    box-shadow: 0 0 0 3px rgba(239,68,68,.12);
}

/* ── Debt item row ── */
.debt-row { transition: background .15s; }
.debt-row:hover { background: #f8fafc; }

/* ── Auto-Ban Settings Modal animation ── */
#autoBanSettingsModal .modal-box {
    transform: scale(0.95) translateY(10px);
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
#autoBanSettingsModal.open .modal-box {
    transform: scale(1) translateY(0);
}

</style>

{{-- ══ DRIVER DETAIL MODAL OVERLAY ══ --}}
<div id="driver-modal-overlay" onclick="handleModalBackdropClick(event)">
    <div id="driver-modal-box">
        {{-- Modal header injected by JS --}}
        <div id="driver-modal-header"></div>
        {{-- Sub-header --}}
        <div id="driver-modal-subheader" class="hidden px-6 py-3 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 flex items-center gap-1.5">
                <i data-lucide="list" class="w-3.5 h-3.5"></i>
                Liability Breakdown
            </span>
            <span id="driver-modal-count" class="text-[10px] font-bold text-gray-400"></span>
        </div>
        {{-- Scrollable rows --}}
        <div id="driver-modal-body" class="bg-white divide-y divide-gray-100"></div>
    </div>
</div>

<div class="w-full mx-auto space-y-6 pb-20 relative z-10">

    {{-- ── Hero Header Panel with SVG Decorative Mesh & Glassmorphic Stats ── --}}
    <div class="relative bg-gradient-to-br from-slate-950 via-slate-900 to-zinc-900 rounded-[2.25rem] p-6 sm:p-8 overflow-hidden shadow-2xl border border-slate-800">
        <!-- SVG Decorative Mesh / Glow -->
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-rose-600/15 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        <svg class="absolute right-0 bottom-0 w-96 h-96 opacity-5 pointer-events-none" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M100 0C44.7715 0 0 44.7715 0 100C0 155.228 44.7715 200 100 200C155.228 200 200 155.228 200 100C200 44.7715 155.228 0 100 0Z" stroke="white" stroke-width="2" stroke-dasharray="8 8"/>
            <path d="M100 30C61.3401 30 30 61.3401 30 100C30 138.66 61.3401 170 100 170C138.66 170 170 138.66 170 100C170 61.3401 138.66 30 100 30Z" stroke="white" stroke-width="1.5"/>
            <path d="M100 60C77.9086 60 60 77.9086 60 100C60 122.091 77.9086 140 100 140C122.091 140 140 122.091 140 100C140 77.9086 122.091 60 100 60Z" stroke="white" stroke-width="1"/>
        </svg>

        <div class="relative flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4 sm:gap-5">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-500 via-rose-500 to-red-600 rounded-2xl flex items-center justify-center shadow-xl shadow-red-500/25 shrink-0 border border-white/20">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                        <line x1="2" y1="10" x2="22" y2="10"></line>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight">Financial Liabilities</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/30 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-400 animate-ping"></span> Live Audit
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium max-w-xl leading-relaxed">
                        Track and manage driver accident charges, parts shortages, and boundary liabilities in real time.
                    </p>
                </div>
            </div>

            {{-- Stats row with modern glassmorphism & SVG icons --}}
            <div class="flex flex-wrap sm:flex-nowrap gap-3 shrink-0 w-full lg:w-auto">
                {{-- Active Debtors --}}
                <div class="flex-1 sm:flex-initial flex items-center gap-3.5 bg-rose-500/10 hover:bg-rose-500/15 transition-all px-4 sm:px-5 py-3.5 rounded-2xl border border-rose-500/25 backdrop-blur-md shadow-lg shadow-rose-500/5 min-w-[140px]">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-rose-400 shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-black text-rose-300 uppercase tracking-widest block">Active Debtors</span>
                        <span class="text-2xl sm:text-3xl font-black text-rose-400 tracking-tight" id="stat-debtors">—</span>
                    </div>
                </div>

                {{-- Total Outstanding --}}
                <div class="flex-1 sm:flex-initial flex items-center gap-3.5 bg-red-500/15 hover:bg-red-500/20 transition-all px-4 sm:px-5 py-3.5 rounded-2xl border border-red-500/30 backdrop-blur-md shadow-lg shadow-red-500/10 min-w-[170px]">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center text-red-400 shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-black text-red-300 uppercase tracking-widest block">Total Outstanding</span>
                        <span class="text-xl sm:text-2xl font-black text-white tracking-tight" id="stat-total-pending">₱0.00</span>
                    </div>
                </div>

                {{-- Total Collected --}}
                <div class="flex-1 sm:flex-initial flex items-center gap-3.5 bg-emerald-500/10 hover:bg-emerald-500/15 transition-all px-4 sm:px-5 py-3.5 rounded-2xl border border-emerald-500/25 backdrop-blur-md shadow-lg shadow-emerald-500/5 min-w-[160px]">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 shrink-0">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <path d="M19 5c-1.5 0-2.8 1.4-3 2-3.5-1.5-11-.3-11 5 0 1.8 0 3 2 4.5V20h4v-2h3v2h4v-4c1-.5 1.7-1 2-2h2v-4h-2c0-1-.5-1.5-1-2V5z"></path>
                            <path d="M2 9v1c0 1.1.9 2 2 2h1"></path>
                            <circle cx="16" cy="11" r="1"></circle>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[9px] sm:text-[10px] font-black text-emerald-300 uppercase tracking-widest block">Total Collected</span>
                        <span class="text-xl sm:text-2xl font-black text-emerald-400 tracking-tight" id="stat-collections">₱0.00</span>
                    </div>
                </div>

                {{-- Auto-Ban Settings Button --}}
                <button type="button" onclick="openAutoBanSettingsModal()"
                    class="flex items-center gap-2 px-4 sm:px-5 py-3.5 bg-slate-800/90 hover:bg-slate-700 text-amber-300 hover:text-amber-200 font-black text-xs uppercase tracking-widest rounded-2xl border border-amber-500/30 shadow-xl shadow-black/20 transition-all active:scale-95 cursor-pointer shrink-0">
                    <i data-lucide="sliders" class="w-4 h-4 text-amber-400"></i>
                    <span class="hidden sm:inline">Auto-Ban Settings</span>
                    <span class="sm:hidden">Auto-Ban</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Auto-Ban Policy Awareness Banner ── --}}
    <div class="bg-gradient-to-r from-amber-500/10 via-orange-500/10 to-transparent border border-amber-500/20 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-2xl bg-amber-500/20 text-amber-600 flex items-center justify-center shrink-0 border border-amber-500/30">
                <i data-lucide="zap" class="w-5 h-5 text-amber-500"></i>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs font-black text-slate-800 uppercase tracking-wide">Automated Lockout Policy</span>
                    <span id="policyStatusBadge" class="px-2 py-0.5 {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border rounded-md text-[10px] font-black uppercase tracking-wider">
                        {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'ACTIVE & ENFORCING' : 'DISABLED' }}
                    </span>
                </div>
                <p id="policyBannerSummary" class="text-xs text-slate-500 font-medium mt-1">
                    Drivers holding an unreturned vehicle for <strong class="text-slate-900 font-bold" id="bannerOverdueDays">{{ $autoBanSettings['auto_ban_overdue_unit_days'] ?? ($autoBanSettings['auto_ban_missed_boundary_days'] ?? 3) }}</strong> overdue days (daily missed boundary charges) are automatically <span class="uppercase font-black {{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'banned' ? 'text-red-600' : 'text-amber-600' }}" id="bannerActionType">{{ $autoBanSettings['auto_ban_action_type'] ?? 'BANNED' }}</span> on shift deadline.
                </p>
            </div>
        </div>
        <button type="button" onclick="openAutoBanSettingsModal()"
            class="inline-flex items-center gap-1.5 text-xs font-black text-amber-700 hover:text-amber-800 bg-amber-100/80 hover:bg-amber-100 border border-amber-300/60 px-4 py-2.5 rounded-xl transition-all self-start sm:self-center shrink-0 cursor-pointer active:scale-95 shadow-2xs">
            <i data-lucide="sliders" class="w-3.5 h-3.5"></i> Configure Policy Days
        </button>
    </div>

    {{-- ══════════════════════
         TOOLBAR
    ══════════════════════ --}}
    <div class="bg-white rounded-2xl border border-gray-100 px-4 py-3 flex flex-col sm:flex-row justify-between items-center gap-3">
        <div class="flex gap-1 bg-gray-50 border border-gray-100 p-1 rounded-xl w-full sm:w-auto">
            <button onclick="switchTab('active')" id="tab-active"
                class="tab-pill active flex-1 sm:flex-none px-5 py-2 text-xs font-black rounded-lg flex items-center justify-center gap-1.5">
                <i data-lucide="wallet" class="w-3.5 h-3.5"></i> Active Liabilities
            </button>
            <button onclick="switchTab('history')" id="tab-history"
                class="tab-pill flex-1 sm:flex-none px-5 py-2 text-xs font-black text-gray-500 rounded-lg flex items-center justify-center gap-1.5">
                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Settlement History
            </button>
        </div>

        {{-- Search with full autofill prevention --}}
        <div class="relative w-full sm:w-auto flex flex-col sm:flex-row items-center gap-2" id="search-container">
            <div style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0;pointer-events:none;" aria-hidden="true">
                <input type="text"  name="fake_name_trap" tabindex="-1">
                <input type="email" name="email"          tabindex="-1">
                <input type="password" name="password"   tabindex="-1">
            </div>
            <div id="dateFilterContainer" class="hidden relative w-full sm:w-auto">
                <input type="date" id="dateFilter" class="w-full sm:w-auto pl-4 pr-9 py-2.5 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-slate-400 focus:ring-0 outline-none transition-all cursor-pointer">
                <button type="button" id="clearDateBtn" class="hidden absolute right-2 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors" title="Clear Date">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" id="searchInput" name="driver_search_xq9"
                    autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');"
                    placeholder="Search driver or plate…"
                    class="w-full pl-10 pr-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-slate-400 focus:ring-0 outline-none transition-all cursor-text">
            </div>
        </div>
    </div>

    {{-- ══════════════════════
         ACTIVE LIABILITIES
    ══════════════════════ --}}
    <div id="pendingDebtsContent" class="space-y-5">

        {{-- Loading --}}
        <div id="loading-active" class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-gray-100">
            <div class="relative w-12 h-12 mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-red-400 border-t-transparent animate-spin"></div>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Loading Financial Records…</p>
        </div>

        {{-- Driver Cards Grid --}}
        <div id="driver-cards-grid" class="hidden">
            <div id="cards-row" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4"></div>

            {{-- Pagination Controls --}}
            <div id="pagination-controls" class="flex justify-center items-center gap-2 mt-6"></div>
        </div>

        {{-- Empty State --}}
        <div id="debts-empty" class="hidden flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-dashed border-gray-200">
            <div class="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center mb-5 border-2 border-emerald-100">
                <i data-lucide="check-circle" class="w-8 h-8 text-emerald-500"></i>
            </div>
            <h4 class="text-base font-black text-gray-800 mb-1">Zero Active Liabilities</h4>
            <p class="text-sm text-gray-400 font-medium">All driver charges have been fully settled.</p>
        </div>

        {{-- No search results --}}
        <div id="debts-no-results" class="hidden text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
            <i data-lucide="search-x" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
            <p class="text-sm font-bold text-gray-400">No results found.</p>
        </div>
    </div>

    {{-- ══════════════════════
         HISTORY TAB
    ══════════════════════ --}}
    <div id="debtHistoryContent" class="hidden space-y-6">

        <div id="loading-history" class="hidden flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-gray-100">
            <div class="relative w-12 h-12 mb-4">
                <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-emerald-400 border-t-transparent animate-spin"></div>
            </div>
            <p class="text-xs font-black text-emerald-500 uppercase tracking-widest">Reconstructing Logs…</p>
        </div>

        <div id="history-list" class="hidden">
            {{-- History Sub-Tabs --}}
            <div class="flex items-center justify-center mb-8 mt-2">
                <div class="inline-flex bg-gray-100/80 p-1 rounded-xl border border-gray-200/60 shadow-inner">
                    <button id="htab-cashin" onclick="switchHistoryTab('cashin')" class="px-6 py-2.5 text-xs font-black rounded-lg transition-all bg-white text-gray-900 shadow-sm border border-gray-200 flex items-center gap-2">
                        <i data-lucide="banknote" class="w-4 h-4 text-blue-500"></i> Cash-In Logs
                    </button>
                    <button id="htab-settled" onclick="switchHistoryTab('settled')" class="px-6 py-2.5 text-xs font-black rounded-lg transition-all text-gray-500 hover:text-gray-900 border border-transparent flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500"></i> Settled Accounts
                    </button>
                </div>
            </div>

            <section id="sec-cashin">
                <div id="payment-logs-list" class="space-y-6"></div>
            </section>
            
            <section id="sec-settled" class="hidden">
                <div id="settled-debts-list" class="space-y-6"></div>
            </section>
        </div>
    </div>

</div>

{{-- ════════════════════════════════════════════════════════
     AUTO-BAN & LOCKOUT POLICY SETTINGS MODAL
════════════════════════════════════════════════════════ --}}
<div id="autoBanSettingsModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden z-[9999] flex items-center justify-center p-4">
    <div class="modal-box relative bg-white rounded-[2rem] shadow-2xl w-full max-w-xl overflow-hidden flex flex-col">
        
        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-amber-950/40 p-6 shrink-0 border-b border-slate-700/50">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-500/20 border border-amber-500/30 rounded-2xl flex items-center justify-center">
                        <i data-lucide="sliders" class="w-6 h-6 text-amber-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-white uppercase tracking-wide">Auto-Ban & Lockout Policy</h3>
                        <p class="text-[11px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">Driver Missed Boundary & Overdue Rules</p>
                    </div>
                </div>
                <button type="button" onclick="closeAutoBanSettingsModal()" class="text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-700 p-2 rounded-full transition-colors cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form id="autoBanSettingsForm" onsubmit="submitAutoBanSettings(event)" class="p-7 space-y-6 overflow-y-auto max-h-[80vh]">
            
            {{-- Automation Status Toggle --}}
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-4">
                <div>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wide">Enable Automated Lockout System</h4>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Automatically trigger suspension/ban when a driver hits the threshold.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer shrink-0">
                    <input type="checkbox" id="setting_auto_ban_enabled" class="sr-only peer" {{ ($autoBanSettings['auto_ban_enabled'] ?? '1') == '1' ? 'checked' : '' }} onchange="updatePolicySimulator()">
                    <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                </label>
            </div>

            {{-- 1. Overdue Unreturned Vehicle Limit (Days) --}}
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">
                        Overdue Unreturned Vehicle Limit <span class="text-red-500">*</span>
                    </label>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">Auto Missed Boundary Tracking</span>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustSettingDays('setting_overdue_days', -1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        -
                    </button>
                    <div class="relative flex-1">
                        <input type="number" id="setting_overdue_days" min="1" max="30"
                            value="{{ $autoBanSettings['auto_ban_overdue_unit_days'] ?? ($autoBanSettings['auto_ban_missed_boundary_days'] ?? 3) }}"
                            oninput="updatePolicySimulator()"
                            class="w-full text-center font-black text-base py-3 border-2 border-slate-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none bg-slate-50/50">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">Days</span>
                    </div>
                    <button type="button" onclick="adjustSettingDays('setting_overdue_days', 1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        +
                    </button>
                </div>
                <p class="text-[10px] text-slate-400 font-medium">Number of overdue days a driver holds the taxi past their shift deadline (daily missed boundary charges are auto-billed) before automatic lockout is executed.</p>
            </div>

            {{-- 3. Lockout Action Type --}}
            <div class="space-y-2.5">
                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">
                    Action to Execute on Threshold <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative flex items-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-50" id="label_action_banned">
                        <input type="radio" name="setting_action_type" value="banned" class="sr-only" {{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'banned' ? 'checked' : '' }} onchange="onActionTypeChange('banned')">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                                <i data-lucide="shield-ban" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">Permanent Ban</div>
                                <div class="text-[10px] text-slate-400 font-medium">Deactivate & blacklist</div>
                            </div>
                        </div>
                    </label>

                    <label class="relative flex items-center p-3.5 border-2 rounded-2xl cursor-pointer transition-all hover:bg-slate-50" id="label_action_suspended">
                        <input type="radio" name="setting_action_type" value="suspended" class="sr-only" {{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'suspended' ? 'checked' : '' }} onchange="onActionTypeChange('suspended')">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-xs font-black text-slate-900">Temporary Suspend</div>
                                <div class="text-[10px] text-slate-400 font-medium">Set duration lockout</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- 4. Temporary Suspension Duration (conditional) --}}
            <div id="suspensionDurationContainer" class="{{ ($autoBanSettings['auto_ban_action_type'] ?? 'banned') === 'suspended' ? '' : 'hidden' }} space-y-2">
                <label class="block text-[11px] font-black text-slate-700 uppercase tracking-widest">
                    Default Suspension Duration <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustSettingDays('setting_suspension_days', -1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        -
                    </button>
                    <div class="relative flex-1">
                        <input type="number" id="setting_suspension_days" min="1" max="90"
                            value="{{ $autoBanSettings['auto_ban_default_suspension_days'] ?? 7 }}"
                            oninput="updatePolicySimulator()"
                            class="w-full text-center font-black text-base py-3 border-2 border-slate-200 rounded-xl focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition-all outline-none bg-slate-50/50">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">Days</span>
                    </div>
                    <button type="button" onclick="adjustSettingDays('setting_suspension_days', 1)" class="w-11 h-11 bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-black rounded-xl text-lg flex items-center justify-center transition-all cursor-pointer select-none">
                        +
                    </button>
                </div>
            </div>

            {{-- 5. Live Interactive Rule Simulator & Awareness Forecaster --}}
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-2xl p-5 text-white shadow-xl border border-slate-700/60 space-y-2.5">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i data-lucide="activity" class="w-4 h-4 text-amber-400 animate-pulse"></i>
                        <span class="text-[10px] font-black text-amber-400 uppercase tracking-widest">Policy Forecast & Awareness</span>
                    </div>
                    <span id="simulatorStatusBadge" class="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-md text-[9px] font-black uppercase tracking-wider">Active</span>
                </div>
                <div id="simulatorExplanationText" class="text-xs text-slate-300 font-medium leading-relaxed bg-black/20 p-3.5 rounded-xl border border-white/5 space-y-1.5">
                    <!-- Injected dynamically by updatePolicySimulator() -->
                </div>
            </div>

            {{-- Modal Actions --}}
            <div class="flex justify-end items-center gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAutoBanSettingsModal()"
                    class="px-5 py-3 text-slate-600 hover:text-slate-800 hover:bg-slate-100 font-bold text-xs uppercase tracking-widest rounded-xl transition-all cursor-pointer">
                    Cancel
                </button>
                <button type="submit" id="saveAutoBanSettingsBtn"
                    class="px-6 py-3.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-slate-950 font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-amber-500/20 transition-all active:scale-95 cursor-pointer flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span>Save Policy Settings</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ─── State ───────────────────────────────────────────── */
let allDebtsData   = [];
let allHistoryData = { settled: [], payments: [] };
let selectedDriverId = null;

/* Pagination State */
let currentPage = 1;
const itemsPerPage = 10;
let currentSearchTerm = '';

/* ─── Init ────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    fetchPendingDebts();
    fetchDebtHistory();

    /* Autofill prevention */
    const si = document.getElementById('searchInput');
    setTimeout(() => { si.value = ''; si.removeAttribute('readonly'); }, 120);
    si.addEventListener('focus', function () {
        this.removeAttribute('readonly');
        if (this.value && this.value.includes('@')) this.value = '';
    }, { once: true });
    const df = document.getElementById('dateFilter');
    const dfc = document.getElementById('dateFilterContainer');
    const clrBtn = document.getElementById('clearDateBtn');
    
    function handleSearch() {
        const val = si.value.toLowerCase();
        const dVal = df ? df.value : '';
        if (clrBtn) {
            if (dVal) clrBtn.classList.remove('hidden');
            else clrBtn.classList.add('hidden');
        }
        if (document.getElementById('tab-active').classList.contains('active')) {
            filterCards(val);
        } else {
            renderHistoryList(val, dVal);
        }
    }
    si.addEventListener('input', handleSearch);
    if(df) df.addEventListener('change', handleSearch);
    if(clrBtn) {
        clrBtn.addEventListener('click', () => {
            if(df) df.value = '';
            handleSearch();
        });
    }
});

/* ─── Tab switcher ────────────────────────────────────── */
function switchTab(tab) {
    const tabA  = document.getElementById('tab-active');
    const tabH  = document.getElementById('tab-history');
    const contA = document.getElementById('pendingDebtsContent');
    const contH = document.getElementById('debtHistoryContent');
    const srchInput = document.getElementById('searchInput');
    const dateInput = document.getElementById('dateFilter');
    const dfContainer = document.getElementById('dateFilterContainer');

    if (tab === 'active') {
        tabA.classList.add('active');    tabH.classList.remove('active');
        tabH.classList.add('text-gray-500'); tabA.classList.remove('text-gray-500');
        contA.classList.remove('hidden'); contH.classList.add('hidden');
        srchInput.placeholder = "Search driver or plate…";
        if(dfContainer) dfContainer.classList.add('hidden');
        renderCards(srchInput.value.toLowerCase(), 1);
    } else {
        tabH.classList.add('active');    tabA.classList.remove('active');
        tabA.classList.add('text-gray-500'); tabH.classList.remove('text-gray-500');
        contH.classList.remove('hidden'); contA.classList.add('hidden');
        srchInput.placeholder = "Search driver, plate, or date…";
        if(dfContainer) dfContainer.classList.remove('hidden');
        renderHistoryList(srchInput.value.toLowerCase(), dateInput ? dateInput.value : '');
    }
}

/* ─── History Sub-Tab switcher ────────────────────────── */
function switchHistoryTab(tab) {
    const btnC = document.getElementById('htab-cashin');
    const btnS = document.getElementById('htab-settled');
    const secC = document.getElementById('sec-cashin');
    const secS = document.getElementById('sec-settled');

    if (tab === 'cashin') {
        btnC.className = "px-6 py-2.5 text-xs font-black rounded-lg transition-all bg-white text-gray-900 shadow-sm border border-gray-200 flex items-center gap-2";
        btnS.className = "px-6 py-2.5 text-xs font-black rounded-lg transition-all text-gray-500 hover:text-gray-900 border border-transparent flex items-center gap-2";
        secC.classList.remove('hidden');
        secS.classList.add('hidden');
    } else {
        btnS.className = "px-6 py-2.5 text-xs font-black rounded-lg transition-all bg-white text-gray-900 shadow-sm border border-gray-200 flex items-center gap-2";
        btnC.className = "px-6 py-2.5 text-xs font-black rounded-lg transition-all text-gray-500 hover:text-gray-900 border border-transparent flex items-center gap-2";
        secS.classList.remove('hidden');
        secC.classList.add('hidden');
    }
}

/* ─── Fetch active debts ──────────────────────────────── */
function fetchPendingDebts() {
    fetch('{{ route('driver-management.pending-debts') }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('loading-active').classList.add('hidden');

        if (!data.success || !data.debts || data.debts.length === 0) {
            allDebtsData = [];
            document.getElementById('debts-empty').classList.remove('hidden');
            updateActiveStats();
            return;
        }

        allDebtsData = data.debts;
        updateActiveStats();
        renderCards();
        document.getElementById('driver-cards-grid').classList.remove('hidden');
    })
    .catch(() => {
        document.getElementById('loading-active').innerHTML =
            `<div class="text-center py-10 text-red-500 font-bold text-sm">Failed to load records. Please refresh.</div>`;
    });
}

/* ─── Stat cards ──────────────────────────────────────── */
function updateActiveStats() {
    let total = 0;
    allDebtsData.forEach(d => { total += parseFloat(d.total_remaining); });
    document.getElementById('stat-debtors').textContent = allDebtsData.length;
    document.getElementById('stat-total-pending').textContent =
        '₱' + total.toLocaleString('en-PH', { minimumFractionDigits: 2 });
}

/* ─── Debt type helper ────────────────────────────────── */
function debtMeta(debt) {
    const d = (debt.description || '').toLowerCase();
    const type = (debt.incident_type || '').toLowerCase();
    if (type === 'short boundary' || type === 'short_boundary' || debt.is_boundary_shortage)
        return { icon:'trending-down', label:'Boundary Shortage', badge:'badge-shortage', color:'#be123c', dotCls:'bg-rose-400' };
    if (type.includes('damage') || type === 'vehicle damage' || d.includes('damage') || d.includes('accident'))
        return { icon:'car-front',     label:'Vehicle Damage',    badge:'badge-damage',   color:'#c2410c', dotCls:'bg-orange-400' };
    if (type.includes('part') || d.includes('part') || d.includes('missing'))
        return { icon:'wrench',        label:'Missing Parts',     badge:'badge-parts',    color:'#b45309', dotCls:'bg-amber-400' };
    const fallbackLabel = debt.incident_type ? debt.incident_type : 'General Liability';
    return     { icon:'alert-triangle',label: fallbackLabel, badge:'badge-general',  color:'#475569', dotCls:'bg-slate-400' };
}

/* ─── Render driver summary CARDS ─────────────────────── */
function renderCards(searchTerm = currentSearchTerm, page = 1) {
    currentSearchTerm = searchTerm;
    currentPage = page;

    const grid = document.getElementById('cards-row');
    const noRes = document.getElementById('debts-no-results');
    const pagination = document.getElementById('pagination-controls');
    let html = '';

    /* 1. Filter data based on search */
    const filteredData = allDebtsData.filter(driver => {
        if (!searchTerm) return true;
        return driver.driver_name.toLowerCase().includes(searchTerm) ||
               (driver.unit_plate && driver.unit_plate.toLowerCase().includes(searchTerm));
    });

    /* 2. Slice for pagination */
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

    const startIndex = (currentPage - 1) * itemsPerPage;
    const paginatedData = filteredData.slice(startIndex, startIndex + itemsPerPage);

    /* 3. Render cards */
    paginatedData.forEach(driver => {
        const photoUrl   = driver.profile_photo_url || '{{ asset('image/avatars/driver.svg') }}';
        const totalDebt  = parseFloat(driver.total_remaining) +
                           driver.debts.reduce((s,d) => s + parseFloat(d.total_paid), 0);
        const totalPaid  = driver.debts.reduce((s,d) => s + parseFloat(d.total_paid), 0);
        const paidPct    = totalDebt > 0 ? Math.min(100,(totalPaid / totalDebt * 100)) : 0;
        const remaining  = parseFloat(driver.total_remaining);

        html += `
        <div class="driver-card bg-white border border-slate-200/90 rounded-2xl p-5 select-none transition-all duration-200 hover:border-amber-400 hover:shadow-lg group flex flex-col justify-between"
             id="dcard-${driver.driver_id}"
             onclick="openDriverPanel(${driver.driver_id})">

            {{-- Top row: Actual Driver Profile Photo + Name + Unit Plate --}}
            <div class="flex items-center gap-3.5 mb-4">
                <div class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden border-2 border-amber-400 bg-slate-100 shrink-0 shadow-sm cursor-pointer group-hover:scale-105 transition-transform"
                     onclick="event.stopPropagation(); if(typeof openImageModal==='function'){ openImageModal('${photoUrl}'); }"
                     title="Click to view driver photo">
                    <img src="${photoUrl}" alt="${driver.driver_name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-black text-slate-900 group-hover:text-amber-600 transition-colors truncate">${driver.driver_name}</h4>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase bg-slate-50 text-slate-600 border border-slate-200/80">
                            <i data-lucide="car" class="w-3 h-3 text-slate-400"></i>
                            ${driver.unit_plate || 'No Unit'}
                        </span>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 mb-3.5"></div>

            <div class="space-y-3">
                <div class="flex justify-between items-end">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Outstanding Balance</p>
                        <p class="text-xl font-black text-red-600 tracking-tight">₱${remaining.toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-rose-50 text-rose-700 border border-rose-200">
                            ${driver.debts.length} item${driver.debts.length > 1 ? 's' : ''}
                        </span>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Settlement Progress</span>
                        <span class="text-[10px] font-black text-slate-700">${paidPct.toFixed(0)}%</span>
                    </div>
                    <div class="pbar-track bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="pbar-fill h-full rounded-full bg-gradient-to-r from-red-500 via-orange-500 to-emerald-500 transition-all duration-500" style="width:${paidPct}%"></div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1 text-slate-400 group-hover:text-slate-700 transition-colors">
                    <span class="text-[10px] font-bold">Click to view &amp; pay liabilities</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform"></i>
                </div>
            </div>
        </div>`;
    });

    grid.innerHTML = html;
    lucide.createIcons();

    /* 4. Render Pagination Controls */
    let pagHtml = '';
    if (totalPages > 1) {
        pagHtml += `<button onclick="renderCards(currentSearchTerm, ${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"><i data-lucide="chevron-left" class="w-4 h-4"></i></button>`;

        for (let i = 1; i <= totalPages; i++) {
            if (i === currentPage) {
                pagHtml += `<button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-900 text-white font-black text-xs shadow-md">${i}</button>`;
            } else {
                pagHtml += `<button onclick="renderCards(currentSearchTerm, ${i})" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 font-bold text-xs hover:bg-gray-50 transition-colors">${i}</button>`;
            }
        }

        pagHtml += `<button onclick="renderCards(currentSearchTerm, ${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"><i data-lucide="chevron-right" class="w-4 h-4"></i></button>`;
    }
    pagination.innerHTML = pagHtml;
    lucide.createIcons();

    /* Visibility handling */
    noRes.classList.toggle('hidden', filteredData.length > 0 || !searchTerm);
    if (filteredData.length === 0 && !searchTerm) {
        document.getElementById('driver-cards-grid').classList.add('hidden');
        document.getElementById('debts-empty').classList.remove('hidden');
    } else {
        document.getElementById('driver-cards-grid').classList.remove('hidden');
    }
}

/* ─── Filter cards by search ──────────────────────────── */
function filterCards(term) {
    closeModal();
    renderCards(term, 1);
}

/* ─── Close modal ────────────────────────────────────── */
function closeModal() {
    selectedDriverId = null;
    document.getElementById('driver-modal-overlay').classList.remove('open');
    document.body.style.overflow = '';
    document.querySelectorAll('.driver-card').forEach(c => c.classList.remove('selected'));
    lucide.createIcons();
}

/* ─── Backdrop click ─────────────────────────────────── */
function handleModalBackdropClick(e) {
    if (e.target === document.getElementById('driver-modal-overlay')) closeModal();
}

/* ─── Open driver modal ──────────────────────────────── */
function openDriverPanel(driverId) {
    const driver = allDebtsData.find(d => d.driver_id == driverId);
    if (!driver) return;

    /* Toggle off if same card */
    if (selectedDriverId === driverId) { closeModal(); return; }

    /* Highlight selected card */
    document.querySelectorAll('.driver-card').forEach(c => c.classList.remove('selected'));
    const card = document.getElementById('dcard-' + driverId);
    if (card) card.classList.add('selected');

    selectedDriverId = driverId;
    renderModal(driver);

    /* Open overlay */
    document.getElementById('driver-modal-overlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

/* ─── ESC key closes modal ───────────────────────────── */
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

/* ─── Render modal content ──────────────────────────── */
function renderModal(driver) {
    const initials = driver.driver_name.trim().split(' ').slice(0,2).map(n=>n[0]).join('').toUpperCase();
    const colors   = ['#0f172a','#1e3a5f','#7c3aed','#0369a1','#065f46','#92400e','#991b1b'];
    const avatarBg = colors[driver.driver_name.charCodeAt(0) % colors.length];
    const totalPaid = driver.debts.reduce((s,d)=>s+parseFloat(d.total_paid),0);
    const totalDebt = parseFloat(driver.total_remaining) + totalPaid;
    const paidPct   = totalDebt > 0 ? Math.min(100,(totalPaid/totalDebt*100)) : 0;

    /* Build debt item rows */
    let rows = '';
    driver.debts.forEach((debt, idx) => {
        const meta    = debtMeta(debt);
        const charge  = parseFloat(debt.total_charge);
        const paid    = parseFloat(debt.total_paid);
        const balance = parseFloat(debt.remaining_balance);
        const pct     = charge > 0 ? Math.min(100,(paid/charge*100)).toFixed(0) : 0;
        const dateStr = new Date(debt.timestamp || debt.date)
            .toLocaleDateString('en-PH', { month:'short', day:'numeric', year:'numeric' });

        rows += `
        <div class="debt-row border-b border-gray-100 last:border-b-0">
            <div class="p-6 flex flex-col lg:flex-row gap-6">

                {{-- Left: type + desc --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest ${meta.badge}">
                            <i data-lucide="${meta.icon}" class="w-3 h-3"></i>
                            ${meta.label}
                        </span>
                        <span class="text-[10px] font-bold text-gray-400">${dateStr}</span>
                        <span class="text-[10px] font-bold text-gray-300">•</span>
                        <span class="text-[10px] font-bold text-gray-400">Item #${idx + 1}</span>
                    </div>
                    <p class="text-sm font-bold text-gray-800 leading-snug mb-4">${debt.description}</p>

                    {{-- Amounts row --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">Total Charge</p>
                            <p class="text-sm font-black text-gray-800">₱${charge.toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-3 border border-emerald-100">
                            <p class="text-[9px] font-black uppercase tracking-widest text-emerald-600 mb-0.5">Amount Paid</p>
                            <p class="text-sm font-black text-emerald-700">₱${paid.toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                        </div>
                        <div class="bg-red-50 rounded-xl p-3 border border-red-100">
                            <p class="text-[9px] font-black uppercase tracking-widest text-red-500 mb-0.5">Remaining</p>
                            <p class="text-sm font-black text-red-700">₱${balance.toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="flex items-center gap-2 mt-3">
                        <div class="pbar-track flex-1">
                            <div class="pbar-fill" style="width:${pct}%"></div>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 shrink-0">${pct}% paid</span>
                    </div>
                </div>

                {{-- Right: payment box --}}
                <div class="lg:w-56 shrink-0">
                    <div class="bg-white border-2 border-gray-200 rounded-2xl p-4 h-full flex flex-col justify-between">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-red-500 mb-1">Balance to Pay</p>
                            <p class="text-2xl font-black text-red-600 leading-none mb-4">₱${balance.toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                        </div>
                        <form onsubmit="return handlePaymentSubmit(event, this, '${driver.driver_name}', '${meta.label}', ${balance}, ${debt.id})"
                              class="space-y-2">
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Enter Payment Amount</p>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-black">₱</span>
                                    <input type="number" name="payment_amount"
                                        step="0.01" min="0.01" max="${balance}" required
                                        placeholder="0.00"
                                        oninput="if(parseFloat(this.value)>${balance}) this.value=${balance};"
                                        class="pay-input w-full pl-7 pr-2 py-2.5 text-sm font-black border border-gray-200 rounded-xl">
                                </div>
                                <button type="submit"
                                    class="px-4 py-2.5 bg-slate-900 hover:bg-red-600 text-white text-xs font-black rounded-xl transition-colors whitespace-nowrap shadow-sm">
                                    Pay
                                </button>
                            </div>
                            <button type="button"
                                onclick="const i=this.closest('form').querySelector('input[name=payment_amount]');i.value=${balance};"
                                class="w-full text-[10px] font-black text-slate-500 hover:text-red-600 transition-colors text-center py-1">
                                Pay full balance
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>`;
    });

    /* Inject into modal elements */
    const photoUrl = driver.profile_photo_url || '{{ asset('image/avatars/driver.svg') }}';
    document.getElementById('driver-modal-header').innerHTML = `
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 px-6 py-5 flex items-center justify-between border-b border-slate-700">
            <div class="flex items-center gap-4">
                <div class="relative w-12 h-12 sm:w-14 sm:h-14 rounded-full overflow-hidden border-2 border-amber-400 bg-slate-800 shrink-0 shadow-md cursor-pointer group hover:scale-105 transition-transform"
                     onclick="event.stopPropagation(); if(typeof openImageModal==='function'){ openImageModal('${photoUrl}'); }"
                     title="Click to view driver photo">
                    <img src="${photoUrl}" alt="${driver.driver_name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-base sm:text-lg font-black text-white">${driver.driver_name}</h3>
                        <span class="px-2.5 py-0.5 bg-amber-400/20 text-amber-300 text-[9px] font-black uppercase rounded-full border border-amber-400/30">
                            ${driver.debts.length} Liabilit${driver.debts.length > 1 ? 'ies' : 'y'}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[11px] font-bold text-slate-300 flex items-center gap-1.5">
                            <i data-lucide="car" class="w-3.5 h-3.5 text-amber-400"></i> ${driver.unit_plate || 'No Unit Assigned'}
                        </span>
                        <span class="text-slate-600">•</span>
                        <span class="text-[11px] font-bold text-slate-300">${paidPct.toFixed(0)}% settled overall</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[9px] font-black uppercase tracking-widest text-red-300">Total Outstanding</p>
                    <p class="text-2xl font-black text-white tracking-tight">₱${parseFloat(driver.total_remaining).toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                </div>
                <button onclick="closeModal()"
                    class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors border border-white/20 text-white">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>`;

    document.getElementById('driver-modal-count').textContent = `${driver.debts.length} pending item${driver.debts.length > 1 ? 's' : ''}`;
    document.getElementById('driver-modal-subheader').classList.remove('hidden');
    document.getElementById('driver-modal-body').innerHTML = rows;

    lucide.createIcons();
}

/* ─── Payment modal + submit ─────────────────────────── */
function handlePaymentSubmit(e, form, driverName, debtType, maxBalance, debtId) {
    e.preventDefault();
    const amount = parseFloat(form.payment_amount.value);
    if (!amount || amount <= 0) return false;

    Swal.fire({
        title: '<span style="font-size:1rem;font-weight:900;color:#0f172a">Confirm Payment</span>',
        html: `
            <div style="text-align:left;margin-top:12px;display:flex;flex-direction:column;gap:8px;">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 16px;">
                    <p style="font-size:9px;font-weight:900;color:#94a3b8;letter-spacing:.12em;text-transform:uppercase;margin:0 0 2px">Driver</p>
                    <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">${driverName}</p>
                </div>
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px 16px;">
                    <p style="font-size:9px;font-weight:900;color:#94a3b8;letter-spacing:.12em;text-transform:uppercase;margin:0 0 2px">Payment For</p>
                    <p style="font-size:13px;font-weight:700;color:#334155;margin:0">${debtType}</p>
                </div>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 16px;">
                    <p style="font-size:9px;font-weight:900;color:#16a34a;letter-spacing:.12em;text-transform:uppercase;margin:0 0 4px">Cash Received</p>
                    <p style="font-size:28px;font-weight:900;color:#15803d;margin:0">₱${amount.toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                </div>
                <p style="font-size:11px;color:#94a3b8;font-weight:600;margin:0">This permanently records the cash entry and reduces the driver's outstanding balance.</p>
            </div>`,
        showCancelButton: true,
        confirmButtonColor: '#0f172a',
        cancelButtonColor:  '#94a3b8',
        confirmButtonText:  'Accept Payment',
        cancelButtonText:   'Cancel',
        customClass: {
            confirmButton: 'rounded-xl font-black text-xs px-6 py-3',
            cancelButton:  'rounded-xl font-black text-xs px-6 py-3',
            popup:         'rounded-2xl'
        }
    }).then(result => {
        if (!result.isConfirmed) return;
        Swal.fire({ title:'Processing…', allowOutsideClick:false, didOpen:()=>Swal.showLoading() });

        const fd = new FormData();
        fd.append('_token', '{{ csrf_token() }}');
        fd.append('debt_id', debtId);
        fd.append('payment_amount', amount);

        fetch('{{ route('driver-management.pay-debt') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                Swal.fire({ icon:'success', title:'Payment Recorded!', text:'The liability has been updated.', timer:2000, showConfirmButton:false });
                const prevSelected = selectedDriverId;
                fetchPendingDebts();
                fetchDebtHistory();
                /* Re-open same driver panel after refresh */
                setTimeout(() => {
                    if (prevSelected) openDriverPanel(prevSelected);
                }, 900);
            } else {
                throw new Error(res.message || 'Payment failed.');
            }
        })
        .catch(() => Swal.fire('Error', 'Could not process payment. Please try again.', 'error'));
    });

    return false;
}

/* ─── Fetch history ───────────────────────────────────── */
function getDateCategory(dateStr) {
    const d = new Date(dateStr);
    return d.toLocaleDateString('en-US', { month: 'long', year: 'numeric' }).toUpperCase();
}

function renderGroupedList(items, type) {
    if (items.length === 0) {
        return `<div class="py-10 text-center text-sm font-semibold text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">No records found.</div>`;
    }

    const groups = {};
    items.forEach(item => {
        const cat = getDateCategory(item.date);
        if (!groups[cat]) groups[cat] = [];
        groups[cat].push(item);
    });

    // Sort the month-year categories descending (newest first)
    const order = Object.keys(groups).sort((a, b) => {
        return new Date(b) - new Date(a);
    });

    let html = '';

    order.forEach(cat => {
        if (!groups[cat] || groups[cat].length === 0) return;

        html += `
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-3 pl-2">
                    <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 bg-slate-900 text-white rounded-md shadow-sm">
                        ${cat}
                    </span>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>
                <div class="grid grid-cols-1 ${type === 'payment' ? 'sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'md:grid-cols-2 lg:grid-cols-3'} gap-3">
        `;

        groups[cat].forEach(item => {
            if (type === 'payment') {
                html += `
                    <div class="bg-white border border-gray-100 rounded-xl p-5 hover:shadow-md hover:-translate-y-0.5 hover:border-blue-200 transition-all relative overflow-hidden group">
                        <div class="absolute inset-y-0 left-0 w-1 bg-emerald-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="flex justify-between items-start mb-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                <i data-lucide="banknote" class="w-4 h-4"></i>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-black text-emerald-600">+₱${parseFloat(item.amount).toLocaleString('en-PH',{minimumFractionDigits:2})}</p>
                                <p class="text-[9px] font-black uppercase text-gray-400">Cash In</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mt-2 mb-1">
                            <p class="text-[10px] font-black uppercase tracking-widest text-blue-500">
                                ${new Date(item.date).toLocaleDateString('en-PH',{month:'short',day:'numeric',year:'numeric'})}
                            </p>
                            <span class="text-[9px] font-bold text-gray-400">${new Date(item.date).toLocaleTimeString('en-PH',{hour:'2-digit',minute:'2-digit'})}</span>
                        </div>
                        <p class="text-xs font-bold text-gray-600 leading-snug line-clamp-2">${item.description}</p>
                    </div>`;
            } else {
                const sPhoto = item.profile_photo_url || '{{ asset('image/avatars/driver.svg') }}';
                html += `
                    <div class="bg-white border border-slate-200/90 rounded-2xl p-4 flex items-center gap-3.5 hover:border-emerald-300 hover:shadow-md transition-all relative overflow-hidden group">
                        <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative w-11 h-11 rounded-full overflow-hidden border-2 border-amber-400 bg-slate-100 shrink-0 shadow-xs cursor-pointer hover:scale-105 transition-transform"
                             onclick="event.stopPropagation(); if(typeof openImageModal==='function'){ openImageModal('${sPhoto}'); }"
                             title="Click to view driver photo">
                            <img src="${sPhoto}" alt="${item.driver_name}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='{{ asset('image/avatars/driver.svg') }}';">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="text-sm font-black text-slate-900 truncate">
                                ${item.driver_name} <span class="text-[10px] font-bold text-slate-400 ml-1">(${item.unit_plate||'No Unit'})</span>
                            </h5>
                            <p class="text-xs text-slate-500 truncate mt-0.5">${item.description}</p>
                            <p class="text-[9px] font-black uppercase tracking-widest text-emerald-600 mt-1.5 flex items-center gap-1">
                                <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-500"></i> Settled ${new Date(item.date).toLocaleDateString('en-PH')}
                            </p>
                        </div>
                    </div>`;
            }
        });

        html += `</div></div>`;
    });

    return html;
}

function renderHistoryList(searchTerm = '', searchDate = '') {
    if (!allHistoryData || !allHistoryData.settled) return;

    let filteredSettled = allHistoryData.settled;
    let filteredPayments = allHistoryData.payments;

    if (searchTerm || searchDate) {
        filteredSettled = allHistoryData.settled.filter(item => {
            const dateStr = new Date(item.date).toLocaleDateString('en-PH', {month:'short', day:'numeric', year:'numeric'}).toLowerCase();
            const mText = !searchTerm || (item.driver_name && item.driver_name.toLowerCase().includes(searchTerm)) ||
                   (item.unit_plate && item.unit_plate.toLowerCase().includes(searchTerm)) ||
                   dateStr.includes(searchTerm) || item.date.includes(searchTerm);
            const mDate = !searchDate || item.date.startsWith(searchDate);
            return mText && mDate;
        });

        filteredPayments = allHistoryData.payments.filter(item => {
            const dateStr = new Date(item.date).toLocaleDateString('en-PH', {month:'short', day:'numeric', year:'numeric'}).toLowerCase();
            const mText = !searchTerm || dateStr.includes(searchTerm) || 
                   item.date.includes(searchTerm) ||
                   (item.description && item.description.toLowerCase().includes(searchTerm));
            const mDate = !searchDate || item.date.startsWith(searchDate);
            return mText && mDate;
        });
    }

    const settledList = document.getElementById('settled-debts-list');
    settledList.innerHTML = renderGroupedList(filteredSettled, 'settled');

    const payList = document.getElementById('payment-logs-list');
    payList.innerHTML = renderGroupedList(filteredPayments, 'payment');
    
    lucide.createIcons();
}

function fetchDebtHistory() {
    fetch('{{ route('driver-management.debt-history') }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) throw new Error();
        allHistoryData = data;

        let totalCollections = 0;
        data.payments.forEach(p => { totalCollections += parseFloat(p.amount); });
        document.getElementById('stat-collections').textContent =
            '₱' + totalCollections.toLocaleString('en-PH', { minimumFractionDigits: 2 });

        renderHistoryList(document.getElementById('searchInput').value.toLowerCase());

        document.getElementById('loading-history').classList.add('hidden');
        document.getElementById('history-list').classList.remove('hidden');
        lucide.createIcons();
    })
    .catch(() => {
        document.getElementById('loading-history').innerHTML =
            `<div class="text-center py-10 text-red-500 font-bold text-sm">Failed to load history.</div>`;
    });
}

/* ─── Image Modal Lightbox ────────────────────────────── */
function openImageModal(src) {
    if (!src) return;
    let modal = document.getElementById('imagePreviewModalOverlay');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'imagePreviewModalOverlay';
        modal.className = 'fixed inset-0 bg-slate-900/95 backdrop-blur-sm z-[9999] flex items-center justify-center hidden opacity-0 transition-opacity duration-300';
        modal.onclick = function(e) {
            if (e.target === modal) closeImageModal();
        };
        modal.innerHTML = `
            <button type="button" class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition-colors group cursor-pointer" onclick="closeImageModal()">
                <i data-lucide="x" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
            </button>
            <div class="relative w-full max-w-[90vw] h-[90vh] flex justify-center items-center p-4">
                <img id="imagePreviewModalImg" src="" class="max-w-full max-h-full object-contain rounded-2xl shadow-2xl scale-95 transition-transform duration-300 border border-white/10" />
            </div>
        `;
        document.body.appendChild(modal);
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
    
    const img = document.getElementById('imagePreviewModalImg');
    img.src = src;
    
    modal.classList.remove('hidden');
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    img.classList.remove('scale-95');
    img.classList.add('scale-100');
}

function closeImageModal() {
    const modal = document.getElementById('imagePreviewModalOverlay');
    const img = document.getElementById('imagePreviewModalImg');
    if (modal) {
        modal.classList.add('opacity-0');
        if (img) {
            img.classList.remove('scale-100');
            img.classList.add('scale-95');
        }
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
}

/* ════════════════════════════════════════════════════════
   AUTO-BAN & LOCKOUT POLICY SETTINGS LOGIC
════════════════════════════════════════════════════════ */
function openAutoBanSettingsModal() {
    const modal = document.getElementById('autoBanSettingsModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.add('open'), 10);
    updatePolicySimulator();
    if (window.lucide) lucide.createIcons();
}

function closeAutoBanSettingsModal() {
    const modal = document.getElementById('autoBanSettingsModal');
    if (!modal) return;
    modal.classList.remove('open');
    setTimeout(() => modal.classList.add('hidden'), 200);
}

function adjustSettingDays(inputId, amount) {
    const input = document.getElementById(inputId);
    if (!input) return;
    let val = parseInt(input.value, 10) || 1;
    val += amount;
    const min = parseInt(input.min, 10) || 1;
    const max = parseInt(input.max, 10) || 30;
    if (val < min) val = min;
    if (val > max) val = max;
    input.value = val;
    updatePolicySimulator();
}

function onActionTypeChange(action) {
    const durContainer = document.getElementById('suspensionDurationContainer');
    const labelBanned = document.getElementById('label_action_banned');
    const labelSuspended = document.getElementById('label_action_suspended');

    if (action === 'suspended') {
        if (durContainer) durContainer.classList.remove('hidden');
        if (labelSuspended) labelSuspended.className = 'relative flex items-center p-3.5 border-2 border-amber-500 bg-amber-50/50 rounded-2xl cursor-pointer transition-all';
        if (labelBanned) labelBanned.className = 'relative flex items-center p-3.5 border-2 border-slate-200 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all';
    } else {
        if (durContainer) durContainer.classList.add('hidden');
        if (labelBanned) labelBanned.className = 'relative flex items-center p-3.5 border-2 border-red-500 bg-red-50/50 rounded-2xl cursor-pointer transition-all';
        if (labelSuspended) labelSuspended.className = 'relative flex items-center p-3.5 border-2 border-slate-200 hover:bg-slate-50 rounded-2xl cursor-pointer transition-all';
    }
    updatePolicySimulator();
}

function updatePolicySimulator() {
    const isEnabled = document.getElementById('setting_auto_ban_enabled')?.checked ?? true;
    const overdueDays = parseInt(document.getElementById('setting_overdue_days')?.value, 10) || 3;
    const actionRadios = document.getElementsByName('setting_action_type');
    let actionType = 'banned';
    for (const r of actionRadios) {
        if (r.checked) { actionType = r.value; break; }
    }
    const suspDays = parseInt(document.getElementById('setting_suspension_days')?.value, 10) || 7;

    const badge = document.getElementById('simulatorStatusBadge');
    if (badge) {
        if (isEnabled) {
            badge.className = 'px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 rounded-md text-[9px] font-black uppercase tracking-wider';
            badge.textContent = 'Enforcing Active';
        } else {
            badge.className = 'px-2 py-0.5 bg-rose-500/20 text-rose-300 border border-rose-500/40 rounded-md text-[9px] font-black uppercase tracking-wider';
            badge.textContent = 'Automation Disabled';
        }
    }

    const expText = document.getElementById('simulatorExplanationText');
    if (expText) {
        if (!isEnabled) {
            expText.innerHTML = `
                <div class="text-rose-300 font-bold flex items-center gap-1.5">
                    <span>⚠️</span> Auto-Lockout is currently <strong>TURNED OFF</strong>. No drivers will be automatically suspended or banned based on unreturned vehicles.
                </div>
            `;
        } else {
            const actionLabel = actionType === 'banned' 
                ? '<strong class="text-red-400 font-black tracking-wide">PERMANENTLY BANNED</strong> (Blacklisted & Mobile Account Revoked)' 
                : `<strong class="text-amber-400 font-black tracking-wide">SUSPENDED FOR ${suspDays} DAYS</strong> (Temporary Lockout)`;

            expText.innerHTML = `
                <div class="space-y-2">
                    <p>• <strong>Vehicle & Boundary Trigger:</strong> Driver holds the taxi for <strong class="text-amber-400 font-bold">${overdueDays} overdue days</strong> past shift deadline without returning.</p>
                    <p>• <strong>Daily Charge Accumulation:</strong> Daily missed boundary fees (₱ rate) are automatically billed for each overdue day.</p>
                    <p>• <strong>Automated Lockout Penalty:</strong> Upon reaching Day ${overdueDays}, the driver is automatically ${actionLabel} at 11:59 PM shift deadline.</p>
                </div>
            `;
        }
    }
}

async function submitAutoBanSettings(e) {
    e.preventDefault();
    const btn = document.getElementById('saveAutoBanSettingsBtn');
    const origHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-slate-900 border-t-transparent rounded-full animate-spin"></span> Saving...';

    const isEnabled = document.getElementById('setting_auto_ban_enabled').checked ? 1 : 0;
    const overdueDays = parseInt(document.getElementById('setting_overdue_days').value, 10) || 3;
    const suspDays = parseInt(document.getElementById('setting_suspension_days').value, 10) || 7;
    
    let actionType = 'banned';
    const actionRadios = document.getElementsByName('setting_action_type');
    for (const r of actionRadios) {
        if (r.checked) { actionType = r.value; break; }
    }

    const payload = {
        auto_ban_enabled: isEnabled,
        auto_ban_missed_boundary_days: overdueDays,
        auto_ban_overdue_unit_days: overdueDays,
        auto_ban_critical_incidents_threshold: 1,
        auto_ban_default_suspension_days: suspDays,
        auto_ban_action_type: actionType,
    };

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        const res = await fetch('{{ route("driver-management.update-auto-ban-settings") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(payload)
        });

        const result = await res.json();
        if (res.ok && result.success) {
            // Update the awareness banner values instantly without reload
            const bannerOverdue = document.getElementById('bannerOverdueDays');
            const bannerAction = document.getElementById('bannerActionType');
            const policyBadge = document.getElementById('policyStatusBadge');

            if (bannerOverdue) bannerOverdue.textContent = overdueDays;
            if (bannerAction) {
                bannerAction.textContent = actionType.toUpperCase();
                bannerAction.className = `uppercase font-black ${actionType === 'banned' ? 'text-red-600' : 'text-amber-600'}`;
            }
            if (policyBadge) {
                if (isEnabled) {
                    policyBadge.className = 'px-2 py-0.5 bg-emerald-100 text-emerald-800 border border-emerald-200 rounded-md text-[10px] font-black uppercase tracking-wider';
                    policyBadge.textContent = 'ACTIVE & ENFORCING';
                } else {
                    policyBadge.className = 'px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded-md text-[10px] font-black uppercase tracking-wider';
                    policyBadge.textContent = 'DISABLED';
                }
            }

            closeAutoBanSettingsModal();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Policy Updated',
                    text: result.message || 'Auto-ban policy updated successfully.',
                    confirmButtonColor: '#f59e0b',
                    customClass: { popup: 'rounded-3xl' }
                });
            } else {
                alert(result.message || 'Auto-ban policy updated successfully.');
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: result.message || 'Failed to update auto-ban settings.',
                    confirmButtonColor: '#ef4444',
                    customClass: { popup: 'rounded-3xl' }
                });
            } else {
                alert(result.message || 'Failed to update auto-ban settings.');
            }
        }
    } catch(err) {
        console.error('Save Auto-Ban Settings Error:', err);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'A network error occurred while saving policy settings.',
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-3xl' }
            });
        } else {
            alert('A network error occurred while saving policy settings.');
        }
    } finally {
        btn.disabled = false;
        btn.innerHTML = origHtml;
        if (window.lucide) lucide.createIcons();
    }
}
</script>
@endpush
