@extends('layouts.app')

@section('title', 'EuroTaxi | Broadcast & Announcement Management')
@section('page-heading', 'Announcements')
@section('page-subheading', 'Broadcast important updates and notifications to your drivers')

@section('content')
<div class="w-full space-y-6 pb-12">

    {{-- ── 1. PAGE HEADER & STATUS ────────────────────────────────────────── --}}
    <div class="relative overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-r from-white via-amber-50/40 to-amber-100/30 backdrop-blur-md p-6 sm:p-8 shadow-xs w-full">
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <!-- 3D Megaphone Graphic (Larger) -->
                <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 shrink-0">
                    <img src="{{ asset('image/kpi/announcement_3d.svg') }}" alt="Announcements" class="w-full h-full object-contain filter drop-shadow-lg hover:scale-105 transition-transform">
                </div>
                <div>
                    <!-- Breadcrumb -->
                    <div class="flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">
                        <span>Communication</span>
                        <span>/</span>
                        <span class="text-amber-600 font-black">Announcements</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight">Announcements</h1>
                    <p class="text-xs sm:text-sm lg:text-base text-slate-500 mt-1 max-w-2xl font-medium leading-relaxed">
                        Broadcast important updates, alerts, policies, and schedules to all drivers across your fleet in real-time.
                    </p>
                </div>
            </div>

            <!-- Status Badge & Counter -->
            <div class="flex items-center gap-3 self-start lg:self-center shrink-0">
                <div class="flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs sm:text-sm font-bold shadow-2xs">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span>Broadcast system active</span>
                </div>
                <div class="px-4 py-2 rounded-full bg-slate-900 text-white text-xs sm:text-sm font-black uppercase tracking-wider shadow-sm">
                    {{ $announcements->total() }} Total
                </div>
            </div>
        </div>
    </div>

    {{-- ── 2. CREATE BROADCAST COMPOSER & LIVE PREVIEW ───────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 w-full">
        
        <!-- Left 8 cols: Broadcast Composer -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col justify-between">
                <div>
                    <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="p-3 bg-amber-50 rounded-2xl text-amber-600">
                                <i data-lucide="send" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-lg sm:text-xl font-black text-slate-900">Create a Broadcast</h2>
                                <p class="text-xs sm:text-sm text-slate-500">Send an important announcement or alert to your drivers.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('announcements.store') }}" method="POST" id="broadcastComposerForm" class="p-6 sm:p-8 space-y-6">
                        @csrf

                        <!-- Title Field -->
                        <div>
                            <label for="composer_title" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                                Title <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="title" id="composer_title" required
                                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all outline-none text-sm font-bold text-slate-800 placeholder:text-slate-400"
                                placeholder="e.g. Schedule Reminder: Mandatory Monthly Inspection">
                        </div>

                        <!-- Message Field -->
                        <div>
                            <label for="composer_message" class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">
                                Message (Optional)
                            </label>
                            <textarea name="message" id="composer_message" rows="4"
                                class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all outline-none text-sm text-slate-700 placeholder:text-slate-400 leading-relaxed"
                                placeholder="Enter the full announcement details, requirements, or guidelines..."></textarea>
                        </div>

                        <!-- ── Integrated Meeting Scheduler / Calendar UI with Perfectly Positioned Time Pickers ── -->
                        <div class="rounded-2xl border border-slate-200/90 bg-gradient-to-br from-slate-50/80 to-slate-100/40 p-5 sm:p-6 space-y-5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-amber-500/10 text-amber-600">
                                        <i data-lucide="calendar-days" class="w-5 h-5"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-black text-slate-900">Schedule Broadcast</h3>
                                        <p class="text-xs text-slate-500">Pick active dates and times. Past dates are strictly disabled.</p>
                                    </div>
                                </div>
                                <!-- Duration badge -->
                                <span id="schedulerDurationBadge" class="px-3.5 py-1.5 bg-amber-500 text-white rounded-full text-xs font-black uppercase tracking-wider shadow-2xs">
                                    7 Days
                                </span>
                            </div>

                            <!-- Calendar Grid & Date/Time Selection Columns -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200/80 shadow-2xs">
                                
                                <!-- Left: Interactive Month Calendar Grid -->
                                <div class="flex flex-col">
                                    <div class="flex items-center justify-between mb-3">
                                        <button type="button" id="prevMonthBtn" onclick="prevCalendarMonth()" class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition-colors" aria-label="Previous Month">
                                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                                        </button>
                                        <h4 id="calendarMonthYear" class="text-sm sm:text-base font-bold text-slate-800 tracking-tight">August 2026</h4>
                                        <button type="button" onclick="nextCalendarMonth()" class="p-2 rounded-xl hover:bg-slate-100 text-slate-600 transition-colors" aria-label="Next Month">
                                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                                        </button>
                                    </div>

                                    <!-- Weekday headers -->
                                    <div class="grid grid-cols-7 text-center text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-wider mb-2">
                                        <div>Mon</div>
                                        <div>Tue</div>
                                        <div>Wed</div>
                                        <div>Thu</div>
                                        <div>Fri</div>
                                        <div>Sat</div>
                                        <div>Sun</div>
                                    </div>

                                    <!-- Days cells (Past dates disabled) -->
                                    <div id="calendarDaysGrid" class="grid grid-cols-7 gap-1.5">
                                        {{-- Generated by JS with strictly disabled past dates --}}
                                    </div>
                                </div>

                                <!-- Right: Selected Dates & Perfectly Positioned Time Pickers -->
                                <div class="flex flex-col justify-between space-y-4">
                                    <div class="space-y-3.5">
                                        <!-- Start Date & Time Control -->
                                        <div>
                                            <label class="block text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Start date & time*</label>
                                            <div class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border border-slate-200 bg-slate-50/70 gap-2">
                                                <span id="displayStartDateText" class="text-xs sm:text-sm font-bold text-slate-800 truncate">Aug 22, 2026</span>
                                                
                                                <!-- Button-Anchored Time Trigger for Start Time -->
                                                <div class="relative inline-flex">
                                                    <button type="button" onclick="openTimePicker('start_time')" 
                                                        class="flex items-center gap-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 rounded-xl px-3.5 py-1.5 transition-all active:scale-95 shadow-2xs group cursor-pointer"
                                                        title="Click to set start time">
                                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-700 group-hover:scale-110 transition-transform"></i>
                                                        <span id="start_time_display" class="text-xs sm:text-sm font-black tracking-wide">12:00 AM</span>
                                                    </button>
                                                    <input type="time" name="start_time" id="start_time" value="00:00" 
                                                        class="absolute inset-0 w-full h-full opacity-0 pointer-events-none -z-10" 
                                                        onchange="onStartTimeChanged(this.value)">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- End Date & Time Control -->
                                        <div>
                                            <label class="block text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">End date / Display Until & time*</label>
                                            <div class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl border border-slate-200 bg-slate-50/70 gap-2">
                                                <span id="displayEndDateText" class="text-xs sm:text-sm font-bold text-slate-800 truncate">Aug 29, 2026</span>
                                                
                                                <!-- Button-Anchored Time Trigger for End Time -->
                                                <div class="relative inline-flex">
                                                    <button type="button" onclick="openTimePicker('valid_until_time')" 
                                                        class="flex items-center gap-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 rounded-xl px-3.5 py-1.5 transition-all active:scale-95 shadow-2xs group cursor-pointer"
                                                        title="Click to set expiration time">
                                                        <i data-lucide="clock" class="w-3.5 h-3.5 text-amber-700 group-hover:scale-110 transition-transform"></i>
                                                        <span id="end_time_display" class="text-xs sm:text-sm font-black tracking-wide">11:59 PM</span>
                                                    </button>
                                                    <input type="time" name="valid_until_time" id="valid_until_time" value="23:59" 
                                                        class="absolute inset-0 w-full h-full opacity-0 pointer-events-none -z-10" 
                                                        onchange="onEndTimeChanged(this.value)">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quick Duration Presets -->
                                        <div>
                                            <label class="block text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Quick Presets</label>
                                            <div class="flex flex-wrap gap-2" id="schedulerPresetGroup">
                                                <button type="button" onclick="setSchedulerPreset(1)" class="sched-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 transition-all" data-days="1">1 Day</button>
                                                <button type="button" onclick="setSchedulerPreset(3)" class="sched-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 transition-all" data-days="3">3 Days</button>
                                                <button type="button" onclick="setSchedulerPreset(7)" class="sched-pill active-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-amber-500 bg-amber-500 text-white shadow-2xs transition-all" data-days="7">7 Days</button>
                                                <button type="button" onclick="setSchedulerPreset(14)" class="sched-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 transition-all" data-days="14">14 Days</button>
                                                <button type="button" onclick="setSchedulerPreset(30)" class="sched-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 transition-all" data-days="30">30 Days</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary footer with live time range -->
                                    <div class="pt-3.5 border-t border-slate-100 flex items-center justify-between text-xs sm:text-sm gap-2">
                                        <span id="schedulerEventSummary" class="text-slate-600 font-semibold truncate text-xs">Event: Aug 22 - Aug 29, 2026 (7 Days)</span>
                                        <span class="text-[10px] sm:text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md shrink-0">Auto-synced</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Actual Form Inputs -->
                            <input type="hidden" name="start_date" id="form_start_date" value="{{ date('Y-m-d') }}">
                            <input type="hidden" name="valid_until" id="form_valid_until" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        </div>

                        <!-- Submit CTA Button -->
                        <button type="submit" id="composerSubmitBtn"
                            class="w-full py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-black text-sm sm:text-base tracking-wide uppercase rounded-2xl transition-all shadow-md shadow-amber-500/20 active:scale-[0.99] flex items-center justify-center gap-2.5">
                            <i data-lucide="send" class="w-5 h-5"></i>
                            <span>Send Broadcast</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right 4 cols: Live Driver App Preview -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-7 space-y-5 h-full flex flex-col justify-between">
                <div class="space-y-5">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="radio" class="w-4 h-4 text-amber-500"></i>
                            <h3 class="text-xs sm:text-sm font-black text-slate-800 uppercase tracking-wider">Driver App Preview</h3>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-400">Live Simulation</span>
                    </div>

                    <!-- Driver Card Simulation -->
                    <div class="rounded-2xl border border-amber-200/80 bg-gradient-to-br from-amber-50/90 via-yellow-50/50 to-amber-100/40 p-5 space-y-3.5 shadow-2xs relative overflow-hidden">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shadow-2xs shrink-0">
                                <i data-lucide="megaphone" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="text-[10px] font-black text-amber-700 uppercase tracking-wider block">Official Broadcast</span>
                                <h4 id="previewTitle" class="text-xs sm:text-sm font-black text-slate-900 truncate">New Announcement</h4>
                            </div>
                        </div>
                        
                        <p id="previewMessage" class="text-xs sm:text-sm text-slate-600 line-clamp-4 leading-relaxed font-medium">
                            Your message will appear here to drivers in real-time upon posting...
                        </p>

                        <div class="pt-3.5 border-t border-amber-200/60 flex items-center justify-between text-[11px] font-bold text-slate-500 gap-1">
                            <span class="flex items-center gap-1.5 truncate">
                                <i data-lucide="clock-3" class="w-3.5 h-3.5 text-amber-600 shrink-0"></i>
                                <span id="previewSchedule" class="truncate">Scheduled: Aug 22 - Aug 29</span>
                            </span>
                            <span class="px-2.5 py-0.5 bg-amber-200/80 text-amber-900 rounded-md text-[10px] font-black uppercase shrink-0">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Guidance note -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs sm:text-sm text-slate-500 leading-relaxed flex items-start gap-3 mt-4">
                    <i data-lucide="info" class="w-4 h-4 text-slate-400 shrink-0 mt-0.5"></i>
                    <span>Drivers receive instant push alerts and see this banner pinned at the top of their dashboard until the expiration date and time.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── 3. FULL-WIDTH BROADCAST HISTORY DATA TABLE ─────────────────────── --}}
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden w-full">
        
        <!-- Table Header -->
        <div class="px-6 sm:px-8 py-5 sm:py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="p-2.5 bg-slate-100 rounded-xl text-slate-700">
                    <i data-lucide="history" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900">Broadcast History</h3>
                    <p class="text-xs sm:text-sm text-slate-500">Chronological archive of all sent announcements, schedules, and expiration states.</p>
                </div>
            </div>

            <!-- Stats counter pills -->
            <div class="flex items-center gap-2.5">
                <span class="text-xs sm:text-sm font-black text-slate-700 bg-slate-100 px-3.5 py-1.5 rounded-xl border border-slate-200">
                    {{ $announcements->total() }} Total
                </span>
                <span class="text-xs sm:text-sm font-black text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200">
                    {{ $announcements->where('valid_until', '>=', now())->count() }} Active
                </span>
            </div>
        </div>

        <!-- Table Container (Full Width) -->
        <div class="overflow-x-auto w-full">
            <table class="w-full divide-y divide-slate-100 text-left">
                <thead class="bg-slate-50/70">
                    <tr>
                        <th class="px-6 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest">Announcement</th>
                        <th class="px-6 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest w-80 shrink-0">Schedule & Duration</th>
                        <th class="px-6 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest w-48 shrink-0">Date Sent</th>
                        <th class="px-6 sm:px-8 py-4 text-[10px] sm:text-xs font-black text-slate-400 uppercase tracking-widest text-right w-36 shrink-0">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($announcements as $announcement)
                    @php
                        $effectiveStart = $announcement->start_date ?? $announcement->created_at;
                        $isScheduled = $announcement->start_date && $announcement->start_date->isFuture();
                        $isExpired = $announcement->valid_until && $announcement->valid_until->isPast();
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <!-- Announcement Details -->
                        <td class="px-6 sm:px-8 py-5 min-w-[280px]">
                            <div class="space-y-1">
                                <h4 class="text-sm sm:text-base font-black text-slate-900 leading-snug">{{ $announcement->title }}</h4>
                                <p class="text-xs sm:text-sm text-slate-500 line-clamp-2 leading-relaxed font-normal">{{ $announcement->message ?: 'No message body provided.' }}</p>
                            </div>
                        </td>

                        <!-- Schedule & Duration with Time -->
                        <td class="px-6 sm:px-8 py-5 w-80 shrink-0">
                            <div class="flex flex-col space-y-1.5">
                                <div class="flex items-center gap-1.5 text-xs sm:text-sm font-bold text-slate-800">
                                    <span>{{ $effectiveStart->format('M d, Y h:i A') }}</span>
                                    <span class="text-slate-400 font-normal">→</span>
                                    <span>{{ $announcement->valid_until ? $announcement->valid_until->format('M d, Y h:i A') : 'Indefinite' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($announcement->duration_days)
                                        <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[10px] sm:text-xs font-black">
                                            ⏱️ {{ $announcement->duration_days }} {{ Str::plural('Day', $announcement->duration_days) }}
                                        </span>
                                    @endif
                                    @if($isScheduled)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span> Scheduled
                                        </span>
                                    @elseif($isExpired)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] sm:text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Active
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <!-- Date Sent -->
                        <td class="px-6 sm:px-8 py-5 w-48 shrink-0">
                            <div class="text-xs sm:text-sm font-bold text-slate-700">
                                {{ $announcement->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-[10px] sm:text-xs text-slate-400 font-medium">
                                {{ $announcement->created_at->diffForHumans() }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 sm:px-8 py-5 text-right w-36 shrink-0">
                            <div class="flex items-center justify-end gap-2">
                                <!-- View Button -->
                                <button type="button"
                                    class="p-2.5 hover:bg-slate-100 text-slate-500 hover:text-slate-800 rounded-xl transition-all btn-view-announcement"
                                    title="View Announcement"
                                    data-title="{{ $announcement->title }}"
                                    data-message="{{ $announcement->message }}"
                                    data-sent="{{ $announcement->created_at->format('M d, Y h:i A') }}"
                                    data-start="{{ $effectiveStart->format('M d, Y h:i A') }}"
                                    data-until="{{ $announcement->valid_until ? $announcement->valid_until->format('M d, Y h:i A') : 'Indefinite' }}"
                                    data-duration="{{ $announcement->duration_days ? $announcement->duration_days . ' ' . Str::plural('Day', $announcement->duration_days) : 'Indefinite' }}"
                                    data-status="{{ $isScheduled ? 'Scheduled' : ($isExpired ? 'Expired' : 'Active') }}">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>

                                <!-- Edit Button -->
                                <button type="button"
                                    class="p-2.5 hover:bg-amber-50 text-slate-500 hover:text-amber-700 rounded-xl transition-all btn-edit-announcement"
                                    title="Edit Announcement"
                                    data-id="{{ $announcement->id }}"
                                    data-title="{{ $announcement->title }}"
                                    data-message="{{ $announcement->message }}"
                                    data-start-date="{{ $effectiveStart->format('Y-m-d') }}"
                                    data-start-time="{{ $effectiveStart->format('H:i') }}"
                                    data-until-date="{{ $announcement->valid_until ? $announcement->valid_until->format('Y-m-d') : '' }}"
                                    data-until-time="{{ $announcement->valid_until ? $announcement->valid_until->format('H:i') : '23:59' }}">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this broadcast?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 hover:bg-rose-50 text-slate-400 hover:text-rose-600 rounded-xl transition-all" title="Delete Announcement">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 sm:px-8 py-16 text-center">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-3">
                                <div class="w-20 h-20 flex items-center justify-center">
                                    <img src="{{ asset('image/kpi/announcement_3d.svg') }}" class="w-full h-full object-contain opacity-70">
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">No broadcasts yet</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Create your first announcement above to keep all drivers updated on schedules, alerts, and policies.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($announcements->hasPages())
        <div class="p-5 sm:p-6 border-t border-slate-100 bg-slate-50/50">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>

</div>

<!-- ── View Announcement Modal ── -->
<div id="viewAnnouncementModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="viewModalContainer">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-6 flex justify-between items-center text-white">
            <div class="flex items-center gap-2.5">
                <i data-lucide="megaphone" class="w-5 h-5"></i>
                <h3 class="font-black text-base">View Announcement</h3>
            </div>
            <button type="button" onclick="closeViewModal()" class="p-1.5 rounded-xl hover:bg-white/20 text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Title</label>
                <div class="text-base font-black text-slate-900" id="viewTitle"></div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Message</label>
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs font-semibold text-slate-700 leading-relaxed whitespace-pre-wrap" id="viewMessage"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Start Date & Time</label>
                    <div class="text-xs font-bold text-slate-800" id="viewStartDate"></div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Display Until & Time</label>
                    <div class="text-xs font-bold text-slate-800" id="viewDisplayUntil"></div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Duration</label>
                    <div class="text-xs font-bold text-amber-700" id="viewDuration"></div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</label>
                    <div class="text-xs font-bold" id="viewStatus"></div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t border-slate-100 flex justify-end bg-slate-50">
            <button type="button" onclick="closeViewModal()" class="px-5 py-2.5 bg-slate-200 text-slate-700 hover:bg-slate-300 rounded-xl text-xs font-bold transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<!-- ── Edit Announcement Modal ── -->
<div id="editAnnouncementModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="editModalContainer">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 p-6 flex justify-between items-center text-white">
            <div class="flex items-center gap-2.5">
                <i data-lucide="pencil" class="w-5 h-5"></i>
                <h3 class="font-black text-base">Edit Announcement</h3>
            </div>
            <button type="button" onclick="closeEditModal()" class="p-1.5 rounded-xl hover:bg-white/20 text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Title</label>
                    <input type="text" name="title" id="editTitle" required
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Message (Optional)</label>
                    <textarea name="message" id="editMessage" rows="3"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all outline-none text-xs text-slate-700 leading-relaxed"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Start Date & Time</label>
                        <input type="date" name="start_date" id="editStartDate" min="{{ date('Y-m-d') }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800 mb-1.5">
                        
                        <div class="relative">
                            <button type="button" onclick="openTimePicker('editStartTime')" 
                                class="w-full flex items-center justify-between bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 rounded-xl px-3 py-2 transition-all active:scale-95 shadow-2xs group cursor-pointer">
                                <span class="flex items-center gap-1.5 text-[11px] font-bold"><i data-lucide="clock" class="w-3.5 h-3.5 text-amber-700"></i> Time:</span>
                                <span id="edit_start_time_display" class="text-xs font-black">12:00 AM</span>
                            </button>
                            <input type="time" name="start_time" id="editStartTime" value="00:00" 
                                class="absolute inset-0 w-full h-full opacity-0 pointer-events-none -z-10"
                                onchange="document.getElementById('edit_start_time_display').textContent = format12HourTime(this.value)">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1">Display Until & Time</label>
                        <input type="date" name="valid_until" id="editValidUntil" min="{{ date('Y-m-d') }}" required
                            class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all outline-none text-xs font-bold text-slate-800 mb-1.5">
                        
                        <div class="relative">
                            <button type="button" onclick="openTimePicker('editValidUntilTime')" 
                                class="w-full flex items-center justify-between bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 rounded-xl px-3 py-2 transition-all active:scale-95 shadow-2xs group cursor-pointer">
                                <span class="flex items-center gap-1.5 text-[11px] font-bold"><i data-lucide="clock" class="w-3.5 h-3.5 text-amber-700"></i> Time:</span>
                                <span id="edit_valid_until_time_display" class="text-xs font-black">11:59 PM</span>
                            </button>
                            <input type="time" name="valid_until_time" id="editValidUntilTime" value="23:59" 
                                class="absolute inset-0 w-full h-full opacity-0 pointer-events-none -z-10"
                                onchange="document.getElementById('edit_valid_until_time_display').textContent = format12HourTime(this.value)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-slate-100 flex justify-end gap-2.5 bg-slate-50">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-300 transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-xl text-xs shadow-md shadow-amber-200 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── INTERACTIVE MEETING SCHEDULER & FUNCTIONAL TIME ENGINE ─────────────
    const todayZero = new Date();
    todayZero.setHours(0,0,0,0);

    let currentCalDate = new Date();
    currentCalDate.setDate(1); // Set to start of month

    let selStartDate = new Date(todayZero);
    let selEndDate = new Date(todayZero);
    selEndDate.setDate(selStartDate.getDate() + 7);

    function toISODate(d) {
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    function formatShortDate(d) {
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function formatFullDate(d) {
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function format12HourTime(timeStr) {
        if (!timeStr) return '12:00 AM';
        const [hStr, mStr] = timeStr.split(':');
        let h = parseInt(hStr, 10);
        const m = mStr || '00';
        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12;
        h = h ? h : 12; // hour 0 is 12
        return `${h}:${m} ${ampm}`;
    }

    function openTimePicker(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        try {
            if (typeof input.showPicker === 'function') {
                input.showPicker();
            } else {
                input.focus();
                input.click();
            }
        } catch (e) {
            input.focus();
            input.click();
        }
    }

    function calculateDiffDays(d1, d2) {
        const diffTime = d2.getTime() - d1.getTime();
        const days = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
        return days > 0 ? days : 1;
    }

    function renderCalendar() {
        const monthYearEl = document.getElementById('calendarMonthYear');
        const gridEl = document.getElementById('calendarDaysGrid');
        const prevBtn = document.getElementById('prevMonthBtn');
        if (!monthYearEl || !gridEl) return;

        monthYearEl.textContent = currentCalDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

        // Disable previous month button if already showing current month & year
        if (prevBtn) {
            const isCurrentOrPastMonth = currentCalDate.getFullYear() <= todayZero.getFullYear() && currentCalDate.getMonth() <= todayZero.getMonth();
            prevBtn.disabled = isCurrentOrPastMonth;
            if (isCurrentOrPastMonth) {
                prevBtn.classList.add('opacity-30', 'cursor-not-allowed', 'pointer-events-none');
            } else {
                prevBtn.classList.remove('opacity-30', 'cursor-not-allowed', 'pointer-events-none');
            }
        }

        const year = currentCalDate.getFullYear();
        const month = currentCalDate.getMonth();

        const firstDayOfMonth = new Date(year, month, 1);
        const lastDayOfMonth = new Date(year, month + 1, 0);

        // Adjust for Monday start (0=Sun, 1=Mon, ..., 6=Sat)
        let firstDayIndex = firstDayOfMonth.getDay() - 1;
        if (firstDayIndex === -1) firstDayIndex = 6;

        gridEl.innerHTML = '';

        // Previous month padding
        const prevMonthLastDate = new Date(year, month, 0).getDate();
        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const dayNum = prevMonthLastDate - i;
            const btn = document.createElement('div');
            btn.className = 'h-10 w-full flex items-center justify-center text-xs text-slate-300 select-none opacity-25 cursor-not-allowed';
            btn.textContent = dayNum;
            gridEl.appendChild(btn);
        }

        // Current month days with validation against past dates
        const totalDays = lastDayOfMonth.getDate();

        for (let day = 1; day <= totalDays; day++) {
            const thisDate = new Date(year, month, day);
            thisDate.setHours(0,0,0,0);

            const isPast = thisDate < todayZero;
            const isStart = selStartDate && thisDate.getTime() === selStartDate.getTime();
            const isEnd = selEndDate && thisDate.getTime() === selEndDate.getTime();
            const inRange = selStartDate && selEndDate && thisDate > selStartDate && thisDate < selEndDate;
            const isToday = thisDate.getTime() === todayZero.getTime();

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'relative h-10 w-full rounded-xl flex items-center justify-center text-xs sm:text-sm font-bold transition-all select-none';

            if (isPast) {
                // STRICTLY DISABLED FOR PAST DATES
                btn.disabled = true;
                btn.className += ' text-slate-300 opacity-30 cursor-not-allowed pointer-events-none bg-slate-50/40';
            } else if (isStart || isEnd) {
                btn.className += ' bg-amber-500 text-white shadow-xs z-10';
                btn.onclick = () => onDayClick(thisDate);
            } else if (inRange) {
                btn.className += ' bg-amber-100 text-amber-900 rounded-none';
                btn.onclick = () => onDayClick(thisDate);
            } else if (isToday) {
                btn.className += ' text-amber-600 font-black border border-amber-400 bg-amber-50/60 hover:bg-amber-100';
                btn.onclick = () => onDayClick(thisDate);
            } else {
                btn.className += ' text-slate-700 hover:bg-slate-100';
                btn.onclick = () => onDayClick(thisDate);
            }

            btn.textContent = day;
            gridEl.appendChild(btn);
        }

        updateSchedulerDisplay();
    }

    function onDayClick(date) {
        if (date < todayZero) return; // Prevent any past date selection

        if (!selStartDate || (selStartDate && selEndDate)) {
            selStartDate = new Date(date);
            selEndDate = null;
        } else if (date < selStartDate) {
            selStartDate = new Date(date);
        } else {
            selEndDate = new Date(date);
        }
        renderCalendar();
    }

    function prevCalendarMonth() {
        if (currentCalDate.getFullYear() <= todayZero.getFullYear() && currentCalDate.getMonth() <= todayZero.getMonth()) {
            return;
        }
        currentCalDate.setMonth(currentCalDate.getMonth() - 1);
        renderCalendar();
    }

    function nextCalendarMonth() {
        currentCalDate.setMonth(currentCalDate.getMonth() + 1);
        renderCalendar();
    }

    function onStartTimeChanged(val) {
        const displayEl = document.getElementById('start_time_display');
        if (displayEl) displayEl.textContent = format12HourTime(val);
        updateSchedulerDisplay();
    }

    function onEndTimeChanged(val) {
        const displayEl = document.getElementById('end_time_display');
        if (displayEl) displayEl.textContent = format12HourTime(val);
        updateSchedulerDisplay();
    }

    function updateSchedulerDisplay() {
        const startTextEl = document.getElementById('displayStartDateText');
        const endTextEl = document.getElementById('displayEndDateText');
        const badgeEl = document.getElementById('schedulerDurationBadge');
        const summaryEl = document.getElementById('schedulerEventSummary');
        const formStart = document.getElementById('form_start_date');
        const formEnd = document.getElementById('form_valid_until');
        const previewSchedule = document.getElementById('previewSchedule');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('valid_until_time');

        const startTimeFormatted = format12HourTime(startTimeInput ? startTimeInput.value : '00:00');
        const endTimeFormatted = format12HourTime(endTimeInput ? endTimeInput.value : '23:59');

        if (selStartDate) {
            startTextEl.textContent = formatFullDate(selStartDate);
            formStart.value = toISODate(selStartDate);
        }

        if (selEndDate) {
            endTextEl.textContent = formatFullDate(selEndDate);
            formEnd.value = toISODate(selEndDate);
        } else {
            endTextEl.textContent = 'Select date';
        }

        if (selStartDate && selEndDate) {
            const days = calculateDiffDays(selStartDate, selEndDate);
            const badgeText = days + (days === 1 ? ' Day' : ' Days');
            badgeEl.textContent = badgeText;
            summaryEl.textContent = `Event: ${formatShortDate(selStartDate)} (${startTimeFormatted}) - ${formatShortDate(selEndDate)} (${endTimeFormatted})`;
            previewSchedule.textContent = `Scheduled: ${formatShortDate(selStartDate)} - ${formatShortDate(selEndDate)}`;

            // Update preset pills
            document.querySelectorAll('.sched-pill').forEach(pill => {
                if (parseInt(pill.getAttribute('data-days')) === days) {
                    pill.className = 'sched-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-amber-500 bg-amber-500 text-white shadow-2xs transition-all';
                } else {
                    pill.className = 'sched-pill px-3 py-1.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 text-slate-600 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-300 transition-all';
                }
            });
        }
    }

    function setSchedulerPreset(days) {
        if (!selStartDate || selStartDate < todayZero) selStartDate = new Date(todayZero);
        selEndDate = new Date(selStartDate);
        selEndDate.setDate(selStartDate.getDate() + (days - 1));
        renderCalendar();
    }

    // ── Real-time Driver Preview Syncer ──
    const composerTitle = document.getElementById('composer_title');
    const composerMessage = document.getElementById('composer_message');
    const previewTitle = document.getElementById('previewTitle');
    const previewMessage = document.getElementById('previewMessage');

    if (composerTitle && previewTitle) {
        composerTitle.addEventListener('input', (e) => {
            previewTitle.textContent = e.target.value.trim() || 'New Announcement';
        });
    }

    if (composerMessage && previewMessage) {
        composerMessage.addEventListener('input', (e) => {
            previewMessage.textContent = e.target.value.trim() || 'Your message will appear here to drivers in real-time upon posting...';
        });
    }

    // ── Modal Handlers ──
    function closeViewModal() {
        const modal = document.getElementById('viewAnnouncementModal');
        const container = document.getElementById('viewModalContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    function closeEditModal() {
        const modal = document.getElementById('editAnnouncementModal');
        const container = document.getElementById('editModalContainer');
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCalendar();

        // View Modal triggers
        document.querySelectorAll('.btn-view-announcement').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('viewTitle').innerText = this.getAttribute('data-title');
                document.getElementById('viewMessage').innerText = this.getAttribute('data-message') || 'No message content provided.';
                document.getElementById('viewStartDate').innerText = this.getAttribute('data-start');
                document.getElementById('viewDisplayUntil').innerText = this.getAttribute('data-until');
                document.getElementById('viewDuration').innerText = this.getAttribute('data-duration');
                
                const status = this.getAttribute('data-status');
                const statusEl = document.getElementById('viewStatus');
                statusEl.innerText = status;
                statusEl.className = status === 'Active' ? 'text-xs font-bold text-emerald-600' : (status === 'Scheduled' ? 'text-xs font-bold text-indigo-600' : 'text-xs font-bold text-rose-600');

                const modal = document.getElementById('viewAnnouncementModal');
                const container = document.getElementById('viewModalContainer');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 50);
            });
        });

        // Edit Modal triggers
        document.querySelectorAll('.btn-edit-announcement').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const startTime = this.getAttribute('data-start-time') || '00:00';
                const untilTime = this.getAttribute('data-until-time') || '23:59';

                document.getElementById('editTitle').value = this.getAttribute('data-title');
                document.getElementById('editMessage').value = this.getAttribute('data-message') || '';
                document.getElementById('editStartDate').value = this.getAttribute('data-start-date');
                document.getElementById('editStartTime').value = startTime;
                document.getElementById('edit_start_time_display').textContent = format12HourTime(startTime);

                document.getElementById('editValidUntil').value = this.getAttribute('data-until-date');
                document.getElementById('editValidUntilTime').value = untilTime;
                document.getElementById('edit_valid_until_time_display').textContent = format12HourTime(untilTime);

                document.getElementById('editForm').action = `/announcements/${id}`;

                const modal = document.getElementById('editAnnouncementModal');
                const container = document.getElementById('editModalContainer');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    container.classList.remove('scale-95', 'opacity-0');
                    container.classList.add('scale-100', 'opacity-100');
                }, 50);
            });
        });
    });
</script>
@endsection
