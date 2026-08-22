@extends('layouts.app')

@section('title', 'EuroTaxi | Announcement Management')
@section('page-heading', 'Announcements')
@section('page-subheading', 'Manage and broadcast important updates to all drivers')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Create Announcement Form -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-2xl overflow-hidden">
                <div class="card-header bg-gradient-to-r from-yellow-500 to-amber-600 py-4 border-0">
                    <h5 class="text-white font-bold mb-0 flex items-center gap-2">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                        New Announcement
                    </h5>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="{{ route('announcements.store') }}" method="POST" id="createAnnouncementForm">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title</label>
                            <input type="text" name="title" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-sm font-bold"
                                placeholder="Enter announcement title...">
                        </div>

                        <div class="mb-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Message (Optional)</label>
                            <textarea name="message" rows="4"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-sm"
                                placeholder="Enter your message here..."></textarea>
                        </div>

                        <!-- Start Date & Duration Controls -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Schedule & Duration</label>
                                <span id="createDurationBadge" class="text-[10px] font-black bg-amber-100 text-amber-800 px-2 py-0.5 rounded-md uppercase tracking-wider">7 Days</span>
                            </div>

                            <!-- Duration Quick Presets -->
                            <div class="flex flex-wrap gap-1.5 mb-3" id="createPresetGroup">
                                <button type="button" onclick="applyCreateDurationPreset(1)" class="create-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 hover:border-yellow-300 transition-all" data-days="1">1 Day</button>
                                <button type="button" onclick="applyCreateDurationPreset(3)" class="create-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 hover:border-yellow-300 transition-all" data-days="3">3 Days</button>
                                <button type="button" onclick="applyCreateDurationPreset(7)" class="create-dur-pill active-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-yellow-500 bg-yellow-500 text-white shadow-xs transition-all" data-days="7">7 Days</button>
                                <button type="button" onclick="applyCreateDurationPreset(14)" class="create-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 hover:border-yellow-300 transition-all" data-days="14">14 Days</button>
                                <button type="button" onclick="applyCreateDurationPreset(30)" class="create-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 hover:border-yellow-300 transition-all" data-days="30">30 Days</button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Start Date</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-xs font-bold">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Display Until</label>
                                    <input type="date" name="valid_until" id="valid_until" required min="{{ date('Y-m-d') }}"
                                        class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-xs font-bold">
                                </div>
                            </div>
                            
                            <!-- Live Duration Info Box -->
                            <div id="createScheduleSummary" class="p-2.5 bg-amber-50/70 border border-amber-200/60 rounded-xl flex items-center justify-between text-[11px] font-semibold text-amber-900">
                                <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-600"></i> <span id="createScheduleRange"></span></span>
                                <span class="font-bold text-amber-700" id="createDurationSummary"></span>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn" 
                            class="w-full py-4 bg-yellow-600 hover:bg-yellow-700 text-white font-black rounded-xl transition-all shadow-lg shadow-yellow-100 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none disabled:shadow-none flex items-center justify-center gap-2">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            POST
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Announcement List -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-2xl overflow-hidden">
                <div class="card-header bg-white py-4 border-b border-gray-50 flex justify-between items-center">
                    <h5 class="text-gray-900 font-bold mb-0 flex items-center gap-2">
                        <i data-lucide="megaphone" class="w-5 h-5 text-yellow-600"></i>
                        Broadcast History
                    </h5>
                    <span class="text-[10px] font-black text-yellow-700 bg-yellow-50 px-3 py-1 rounded-full border border-yellow-100 uppercase tracking-widest">
                        {{ $announcements->total() }} Total
                    </span>
                </div>
                <div class="card-body p-0 bg-gray-50/30">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-0">Announcement</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-0">Schedule & Duration</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-0 w-36">Date Sent</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest border-0 text-end w-32">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse($announcements as $announcement)
                                @php
                                    $effectiveStart = $announcement->start_date ?? $announcement->created_at;
                                    $isScheduled = $announcement->start_date && $announcement->start_date->isFuture();
                                    $isExpired = $announcement->valid_until && $announcement->valid_until->isPast();
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="max-w-md">
                                            <h6 class="text-sm font-black text-gray-900 mb-1 leading-tight">{{ $announcement->title }}</h6>
                                            <p class="text-xs font-medium text-gray-500 mb-0 leading-relaxed line-clamp-2">{{ $announcement->message }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-1.5 mb-1 text-[11px] font-bold text-gray-800">
                                                <span>{{ $effectiveStart->format('M d, Y') }}</span>
                                                <span class="text-gray-400 font-normal">→</span>
                                                <span>{{ $announcement->valid_until ? $announcement->valid_until->format('M d, Y') : 'Indefinite' }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if($announcement->duration_days)
                                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded-md text-[9px] font-bold">
                                                        ⏱️ {{ $announcement->duration_days }} {{ Str::plural('Day', $announcement->duration_days) }}
                                                    </span>
                                                @endif
                                                @if($isScheduled)
                                                    <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-200">
                                                        ● Scheduled
                                                    </span>
                                                @elseif($isExpired)
                                                    <span class="text-[9px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md border border-rose-200">
                                                        ● Expired
                                                    </span>
                                                @else
                                                    <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                                        ● Active
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 w-36">
                                        <div class="text-[11px] font-bold text-gray-600 uppercase tracking-tighter">
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-[9px] font-medium text-gray-400">
                                            {{ $announcement->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-end w-32">
                                        <div class="flex items-center justify-end gap-2">
                                            <!-- View Button -->
                                            <button 
                                                class="p-2 hover:bg-yellow-50 text-gray-400 hover:text-yellow-600 rounded-xl transition-all btn-view-announcement" 
                                                title="View"
                                                data-title="{{ $announcement->title }}"
                                                data-message="{{ $announcement->message }}"
                                                data-sent="{{ $announcement->created_at->format('M d, Y') }}"
                                                data-start="{{ $effectiveStart->format('M d, Y') }}"
                                                data-until="{{ $announcement->valid_until ? $announcement->valid_until->format('M d, Y') : 'Indefinite' }}"
                                                data-duration="{{ $announcement->duration_days ? $announcement->duration_days . ' ' . Str::plural('Day', $announcement->duration_days) : 'Indefinite' }}"
                                                data-status="{{ $isScheduled ? 'Scheduled' : ($isExpired ? 'Expired' : 'Active') }}">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>

                                            <!-- Edit Button -->
                                            <button 
                                                class="p-2 hover:bg-yellow-50 text-gray-400 hover:text-yellow-600 rounded-xl transition-all btn-edit-announcement" 
                                                title="Edit"
                                                data-id="{{ $announcement->id }}"
                                                data-title="{{ $announcement->title }}"
                                                data-message="{{ $announcement->message }}"
                                                data-start="{{ $effectiveStart->format('Y-m-d') }}"
                                                data-until="{{ $announcement->valid_until ? $announcement->valid_until->format('Y-m-d') : '' }}">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Sigurado ka bang burahin ito?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 hover:bg-rose-50 text-gray-400 hover:text-rose-600 rounded-xl transition-all" title="Delete">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="p-4 bg-gray-50 rounded-full mb-3">
                                                <i data-lucide="megaphone-off" class="w-8 h-8 text-gray-300"></i>
                                            </div>
                                            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No announcements found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($announcements->hasPages())
                    <div class="p-4 border-t border-gray-50">
                        {{ $announcements->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Announcement Modal -->
<div id="viewAnnouncementModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="viewModalContainer">
        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 p-4 flex justify-between items-center text-white">
            <h5 class="font-bold mb-0 flex items-center gap-2">
                <i data-lucide="megaphone" class="w-5 h-5"></i>
                View Announcement
            </h5>
            <button type="button" onclick="closeViewModal()" class="text-white hover:text-gray-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Title</label>
                <div class="text-lg font-bold text-gray-900 mb-2" id="viewTitle"></div>
                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Message</label>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-sm font-semibold text-gray-800 leading-relaxed whitespace-pre-wrap" id="viewMessage"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Start Date</label>
                    <div class="text-sm font-bold text-gray-700" id="viewStartDate"></div>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Display Until</label>
                    <div class="text-sm font-bold text-gray-700" id="viewDisplayUntil"></div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-100">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Total Duration</label>
                    <div class="text-sm font-bold text-amber-700" id="viewDuration"></div>
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Status</label>
                    <div class="text-sm font-bold" id="viewStatus"></div>
                </div>
            </div>
        </div>
        <div class="p-4 border-t flex justify-end bg-gray-50">
            <button type="button" onclick="closeViewModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-bold transition-all">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div id="editAnnouncementModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="editModalContainer">
        <div class="bg-gradient-to-r from-yellow-500 to-amber-600 p-4 flex justify-between items-center text-white">
            <h5 class="font-bold mb-0 flex items-center gap-2">
                <i data-lucide="edit-3" class="w-5 h-5"></i>
                Edit Announcement
            </h5>
            <button type="button" onclick="closeEditModal()" class="text-white hover:text-gray-100 transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Title</label>
                    <input type="text" name="title" id="editTitle" required
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-sm font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Message (Optional)</label>
                    <textarea name="message" id="editMessage" rows="4"
                        class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-sm"></textarea>
                </div>

                <!-- Edit Modal Duration & Schedule Controls -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Schedule & Duration</label>
                        <span id="editDurationBadge" class="text-[10px] font-black bg-amber-100 text-amber-800 px-2 py-0.5 rounded-md uppercase tracking-wider">7 Days</span>
                    </div>

                    <!-- Edit Quick Presets -->
                    <div class="flex flex-wrap gap-1.5 mb-3" id="editPresetGroup">
                        <button type="button" onclick="applyEditDurationPreset(1)" class="edit-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 transition-all" data-days="1">1 Day</button>
                        <button type="button" onclick="applyEditDurationPreset(3)" class="edit-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 transition-all" data-days="3">3 Days</button>
                        <button type="button" onclick="applyEditDurationPreset(7)" class="edit-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 transition-all" data-days="7">7 Days</button>
                        <button type="button" onclick="applyEditDurationPreset(14)" class="edit-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 transition-all" data-days="14">14 Days</button>
                        <button type="button" onclick="applyEditDurationPreset(30)" class="edit-dur-pill px-2.5 py-1 text-xs font-bold rounded-lg border border-gray-200 bg-gray-50 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 transition-all" data-days="30">30 Days</button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-2">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Start Date</label>
                            <input type="date" name="start_date" id="editStartDate" required
                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Display Until</label>
                            <input type="date" name="valid_until" id="editValidUntil" required
                                class="w-full px-3 py-2.5 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all outline-none text-xs font-bold">
                        </div>
                    </div>

                    <div id="editScheduleSummary" class="p-2.5 bg-amber-50/70 border border-amber-200/60 rounded-xl flex items-center justify-between text-[11px] font-semibold text-amber-900">
                        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-3.5 h-3.5 text-amber-600"></i> <span id="editScheduleRange"></span></span>
                        <span class="font-bold text-amber-700" id="editDurationSummary"></span>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-bold transition-all">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-bold rounded-lg shadow-md shadow-yellow-100 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Helpers to compute date string adding days
    function addDaysToDate(dateStr, days) {
        const d = dateStr ? new Date(dateStr + 'T00:00:00') : new Date();
        d.setDate(d.getDate() + (days - 1));
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${yyyy}-${mm}-${dd}`;
    }

    function formatDateDisplay(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr + 'T00:00:00');
        return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function calculateDiffDays(startStr, endStr) {
        if (!startStr || !endStr) return null;
        const d1 = new Date(startStr + 'T00:00:00');
        const d2 = new Date(endStr + 'T00:00:00');
        const diffTime = d2 - d1;
        const diffDays = Math.round(diffTime / (1000 * 60 * 60 * 24)) + 1;
        return diffDays > 0 ? diffDays : 1;
    }

    // ── Create Form Logic ──
    const startInput = document.getElementById('start_date');
    const validUntilInput = document.getElementById('valid_until');
    const durationBadge = document.getElementById('createDurationBadge');
    const scheduleRange = document.getElementById('createScheduleRange');
    const durationSummary = document.getElementById('createDurationSummary');

    function updateCreateScheduleUI() {
        const sVal = startInput.value;
        const eVal = validUntilInput.value;

        if (sVal) {
            validUntilInput.min = sVal;
        }

        if (sVal && eVal) {
            const days = calculateDiffDays(sVal, eVal);
            const label = days + (days === 1 ? ' Day' : ' Days');
            durationBadge.textContent = label;
            scheduleRange.textContent = `${formatDateDisplay(sVal)} → ${formatDateDisplay(eVal)}`;
            durationSummary.textContent = `${days} Days total`;

            // Update pills highlight
            document.querySelectorAll('.create-dur-pill').forEach(pill => {
                if (parseInt(pill.getAttribute('data-days')) === days) {
                    pill.classList.add('border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-xs');
                    pill.classList.remove('bg-gray-50', 'text-gray-600', 'border-gray-200');
                } else {
                    pill.classList.remove('border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-xs');
                    pill.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-200');
                }
            });
        }
    }

    function applyCreateDurationPreset(days) {
        const sVal = startInput.value || new Date().toISOString().split('T')[0];
        validUntilInput.value = addDaysToDate(sVal, days);
        updateCreateScheduleUI();
    }

    // Initialize 7 days default
    if (startInput && validUntilInput) {
        applyCreateDurationPreset(7);
        startInput.addEventListener('change', () => {
            const currentDays = calculateDiffDays(startInput.value, validUntilInput.value) || 7;
            validUntilInput.value = addDaysToDate(startInput.value, currentDays);
            updateCreateScheduleUI();
        });
        validUntilInput.addEventListener('change', updateCreateScheduleUI);
    }

    // ── Edit Modal Logic ──
    const editStartInput = document.getElementById('editStartDate');
    const editValidUntilInput = document.getElementById('editValidUntil');
    const editDurationBadge = document.getElementById('editDurationBadge');
    const editScheduleRange = document.getElementById('editScheduleRange');
    const editDurationSummary = document.getElementById('editDurationSummary');

    function updateEditScheduleUI() {
        const sVal = editStartInput.value;
        const eVal = editValidUntilInput.value;

        if (sVal) {
            editValidUntilInput.min = sVal;
        }

        if (sVal && eVal) {
            const days = calculateDiffDays(sVal, eVal);
            const label = days + (days === 1 ? ' Day' : ' Days');
            editDurationBadge.textContent = label;
            editScheduleRange.textContent = `${formatDateDisplay(sVal)} → ${formatDateDisplay(eVal)}`;
            editDurationSummary.textContent = `${days} Days total`;

            document.querySelectorAll('.edit-dur-pill').forEach(pill => {
                if (parseInt(pill.getAttribute('data-days')) === days) {
                    pill.classList.add('border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-xs');
                    pill.classList.remove('bg-gray-50', 'text-gray-600', 'border-gray-200');
                } else {
                    pill.classList.remove('border-yellow-500', 'bg-yellow-500', 'text-white', 'shadow-xs');
                    pill.classList.add('bg-gray-50', 'text-gray-600', 'border-gray-200');
                }
            });
        }
    }

    function applyEditDurationPreset(days) {
        const sVal = editStartInput.value || new Date().toISOString().split('T')[0];
        editValidUntilInput.value = addDaysToDate(sVal, days);
        updateEditScheduleUI();
    }

    if (editStartInput && editValidUntilInput) {
        editStartInput.addEventListener('change', () => {
            const currentDays = calculateDiffDays(editStartInput.value, editValidUntilInput.value) || 7;
            editValidUntilInput.value = addDaysToDate(editStartInput.value, currentDays);
            updateEditScheduleUI();
        });
        editValidUntilInput.addEventListener('change', updateEditScheduleUI);
    }

    // Modal helpers
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
        // View Modal triggers
        document.querySelectorAll('.btn-view-announcement').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('viewTitle').innerText = this.getAttribute('data-title');
                document.getElementById('viewMessage').innerText = this.getAttribute('data-message') || 'No message content';
                document.getElementById('viewStartDate').innerText = this.getAttribute('data-start');
                document.getElementById('viewDisplayUntil').innerText = this.getAttribute('data-until');
                document.getElementById('viewDuration').innerText = this.getAttribute('data-duration');
                
                const status = this.getAttribute('data-status');
                const statusEl = document.getElementById('viewStatus');
                statusEl.innerText = status;
                statusEl.className = status === 'Active' ? 'text-sm font-bold text-emerald-600' : (status === 'Scheduled' ? 'text-sm font-bold text-indigo-600' : 'text-sm font-bold text-rose-600');

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
                document.getElementById('editTitle').value = this.getAttribute('data-title');
                document.getElementById('editMessage').value = this.getAttribute('data-message') || '';
                document.getElementById('editStartDate').value = this.getAttribute('data-start');
                document.getElementById('editValidUntil').value = this.getAttribute('data-until');
                document.getElementById('editForm').action = `/announcements/${id}`;

                updateEditScheduleUI();

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
