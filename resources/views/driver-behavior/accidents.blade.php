@extends('layouts.app')
@section('title', 'Accident Reports - Euro System')
@section('page-heading', 'Accident / SOS Reports')
@section('page-subheading', 'Real-time incident reports sent from driver mobile app')

@section('content')
<style>
    .stat-card-premium { @apply transition-all duration-500 cursor-default; }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="relative overflow-hidden rounded-2xl shadow-sm border border-red-200 bg-gradient-to-br from-red-50 to-rose-50/70 p-4 sm:p-5 flex items-center justify-between">
        <div class="flex-1 min-w-0 relative z-10">
            <p class="text-red-500 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest mb-1">Total Accident & SOS Reports</p>
            <p class="text-slate-800 text-xl sm:text-3xl font-bold tracking-tight leading-none mb-1">{{ count($accident_reports) }}</p>
        </div>
        <img src="{{ asset('image/kpi/accident_3d.svg') }}" alt="Accidents 3D" class="w-16 h-16 sm:w-20 sm:h-20 object-contain pointer-events-none flex-shrink-0">
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-5 py-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <i data-lucide="shield-alert" class="w-4 h-4 text-red-500"></i>
            <h3 class="font-black text-sm text-gray-800 uppercase tracking-widest">Accident & SOS Reports Feed</h3>
        </div>
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative w-full sm:w-64">
                <!-- Dummy hidden inputs to trap browser password manager autofill -->
                <input type="text" style="position:absolute; opacity:0; width:0; height:0; z-index:-1; pointer-events:none;" tabindex="-1" autocomplete="username">
                <input type="password" style="position:absolute; opacity:0; width:0; height:0; z-index:-1; pointer-events:none;" tabindex="-1" autocomplete="current-password">

                <input type="search" id="accidentSearchInput" name="q_search_feed_no_autofill"
                       readonly onfocus="this.removeAttribute('readonly');"
                       autocomplete="off" aria-autocomplete="none" data-lpignore="true" data-form-type="other"
                       class="block w-full pl-9 pr-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-red-500 focus:border-red-500 cursor-text bg-white" 
                       placeholder="Search driver, plate, note...">
                <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
            </div>
            <div class="relative w-full sm:w-48">
                <input type="date" id="accidentDateFilter" class="block w-full pl-9 pr-3 py-2 text-xs border border-gray-200 rounded-lg focus:ring-red-500 focus:border-red-500">
                <i data-lucide="calendar" class="absolute left-3 top-2.5 w-4 h-4 text-gray-400"></i>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-50 border-separate" style="border-spacing: 0 8px; padding: 0 10px;">
            <thead class="bg-transparent">
                <tr>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date / Time</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver & Unit</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Type / Severity</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Driver Message / Evidence</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Location</th>
                    <th class="px-5 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-5 py-3 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y-0">
                @forelse($accident_reports as $r)
                @php 
                    $rawNotes = $r->notes ?? '';
                    $finalDescription = $r->description ?: $rawNotes; 
                    
                    // Extract damage severity
                    $damageLevel = 'Emergency SOS';
                    if (preg_match('/Damage Level:\s*([a-zA-Z_]+)/i', $rawNotes, $dmgMatches)) {
                        $damageLevel = ucwords(str_replace('_', ' ', $dmgMatches[1]));
                    } elseif ($r->accident_type) {
                        $damageLevel = ucwords($r->accident_type);
                    }
                    
                    // Clean up description text from driver
                    $cleanDesc = $finalDescription;
                    $cleanDesc = preg_replace('/Emergency Alert triggered by driver.*?Description:\s*/s', '', $cleanDesc);
                    $cleanDesc = preg_replace('/Damage Level:.*?\n/s', '', $cleanDesc);
                    $cleanDesc = preg_replace('/--- ACCIDENT REPORT ---\s*/s', '', $cleanDesc);
                    $cleanDesc = trim(str_replace('Description:', '', $cleanDesc));
                    if (empty($cleanDesc) || $cleanDesc === 'Emergency Alert triggered by driver') {
                        $cleanDesc = 'Emergency SOS alert pinged by driver via mobile application.';
                    }
                    
                    $driverName = trim(($r->driver->first_name ?? '') . ' ' . ($r->driver->last_name ?? ''));
                    if (empty($driverName)) $driverName = 'Unknown Driver';
                    
                    $driverPhoto = $r->driver && $r->driver->profile_photo ? asset($r->driver->profile_photo) : asset('image/avatars/driver.svg');
                    $driverPhone = $r->driver->contact_number ?? 'Not provided';
                    $driverEmergency = trim(($r->driver->emergency_contact ?? '') . ($r->driver->emergency_phone ? ' • ' . $r->driver->emergency_phone : ''));
                    if (empty($driverEmergency)) $driverEmergency = 'None recorded';
                    
                    $unitPlate = $r->unit->plate_number ?? 'UNKNOWN';
                    $unitDetails = trim(($r->unit->make ?? '') . ' ' . ($r->unit->model ?? '') . ' ' . ($r->unit->year ?? ''));
                    if (empty($unitDetails)) $unitDetails = 'Fleet Vehicle';
                    
                    $repDate = \Carbon\Carbon::parse($r->created_at)->format('M d, Y h:i A');
                    $repType = ($r->type === 'accident' || str_contains(strtolower($rawNotes), 'accident')) ? 'Accident Report' : 'Emergency SOS';
                    $repStatus = strtoupper($r->status ?? 'RESPONDING');
                    $repPhoto = $r->photo_path ? asset($r->photo_path) : '';
                    $repLat = $r->latitude ?? '';
                    $repLng = $r->longitude ?? '';
                @endphp
                <tr class="accident-row bg-white shadow-sm border border-gray-100 rounded-xl cursor-pointer hover:-translate-y-1 hover:shadow-lg hover:border-red-200 transition-all duration-300 {{ $r->status === 'pending' ? 'bg-red-50/30 border-red-100' : '' }}" 
                    data-id="{{ $r->id }}"
                    data-driver="{{ e($driverName) }}"
                    data-driver-photo="{{ e($driverPhoto) }}"
                    data-driver-phone="{{ e($driverPhone) }}"
                    data-driver-emergency="{{ e($driverEmergency) }}"
                    data-unit="{{ e($unitPlate) }}"
                    data-unit-details="{{ e($unitDetails) }}"
                    data-date="{{ e($repDate) }}"
                    data-type="{{ e($repType) }}"
                    data-damage-level="{{ e($damageLevel) }}"
                    data-description="{{ e($cleanDesc) }}"
                    data-status="{{ e($repStatus) }}"
                    data-photo="{{ e($repPhoto) }}"
                    data-latitude="{{ e($repLat) }}"
                    data-longitude="{{ e($repLng) }}"
                    onclick="openAccidentModal(this)">
                    <td class="px-5 py-4 rounded-l-xl border-y border-l border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        <div class="text-xs font-black text-gray-800">{{ \Carbon\Carbon::parse($r->created_at)->format('M d, Y') }}</div>
                        <div class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($r->created_at)->format('h:i A') }}</div>
                    </td>
                    <td class="px-5 py-4 border-y border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        <div class="flex items-center gap-2.5">
                            <img src="{{ $driverPhoto }}" alt="Driver" class="w-8 h-8 rounded-xl object-cover border border-amber-300 bg-slate-100 shrink-0" onerror="this.src='{{ asset('image/avatars/driver.svg') }}'">
                            <div>
                                <div class="text-xs font-black text-gray-900 leading-tight">{{ $driverName }}</div>
                                <div class="text-[10px] font-black text-blue-600 uppercase font-mono mt-0.5">{{ $unitPlate }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 border-y border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        <div class="flex flex-col gap-1">
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-full border border-red-200 bg-red-100 text-red-700 w-max">
                                {{ $repType }}
                            </span>
                            @if($damageLevel !== 'Emergency SOS')
                            <span class="text-[9px] font-bold text-slate-500">
                                Damage: <strong class="text-slate-800">{{ $damageLevel }}</strong>
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 border-y border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        <div class="text-xs text-gray-700 max-w-[220px] truncate font-medium">{{ $cleanDesc }}</div>
                        @if($r->photo_path)
                            <div class="text-[9px] text-blue-600 font-extrabold flex items-center gap-1.5 mt-1 bg-blue-50 border border-blue-200/80 px-2 py-0.5 rounded-md w-max" onclick="event.stopPropagation(); openPhotoLightbox('{{ asset($r->photo_path) }}')">
                                <i data-lucide="camera" class="w-3 h-3 text-blue-600"></i> Photo Attached (View)
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-4 border-y border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        @if($r->latitude && $r->longitude)
                            <div class="text-xs text-gray-800 font-medium mb-1 max-w-[200px] truncate reverse-geocode" data-lat="{{ $r->latitude }}" data-lng="{{ $r->longitude }}" id="addr-{{ $r->id }}">Fetching address...</div>
                            <a href="https://www.google.com/maps?q={{ $r->latitude }},{{ $r->longitude }}" target="_blank" onclick="event.stopPropagation()" class="text-[10px] font-black uppercase tracking-widest text-blue-600 hover:underline flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3"></i> View Map
                            </a>
                        @else
                            <span class="text-[10px] font-medium text-gray-400">No Location</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 border-y border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        @if($r->status === 'pending')
                            <span class="flex items-center gap-1 text-[10px] font-black uppercase tracking-widest text-red-600"><span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span> PENDING</span>
                        @else
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-600">✓ {{ $r->status }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right rounded-r-xl border-y border-r border-gray-100 {{ $r->status === 'pending' ? 'border-red-100' : '' }}">
                        <div class="flex items-center justify-end gap-2" onclick="event.stopPropagation()">
                            @if($r->status === 'pending')
                            <form action="{{ route('accident-alerts.acknowledge', $r->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-green-100 hover:scale-105 transition-all">Ack ✓</button>
                            </form>
                            @endif
                            <form action="{{ route('driver-behavior.archive-accident', $r->id) }}" method="POST" onsubmit="return confirm('Archive this accident report?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                    <i data-lucide="archive" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-12 text-center">
                        <i data-lucide="check-circle" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                        <p class="text-sm font-medium text-gray-500">No accident reports found.</p>
                        <p class="text-xs text-gray-400">Accident SOS alerts from the driver app will appear here.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Accident Report Modal (21st.dev Executive Theme) -->
<div id="accidentModal" class="hidden fixed inset-0 z-[99999] flex items-center justify-center bg-slate-950/80 backdrop-blur-md p-3 sm:p-5" onclick="closeAccidentModal()">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-700/30 flex flex-col max-h-[92vh]" onclick="event.stopPropagation()" id="accidentModalContent">
        <!-- Top Obsidian Header -->
        <div class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-rose-500/20 text-rose-400 rounded-2xl border border-rose-500/30 shadow-inner">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-white font-black text-base sm:text-lg tracking-tight uppercase" id="modHeaderTitle">
                            Accident / SOS Report
                        </h3>
                        <span id="modStatusBadge" class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full border bg-emerald-500/20 border-emerald-500/40 text-emerald-300">
                            RESPONDING
                        </span>
                    </div>
                    <p class="text-xs font-semibold text-slate-400" id="modDate">-</p>
                </div>
            </div>
            <button onclick="closeAccidentModal()" type="button" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-white/10 focus:outline-none">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        
        <!-- Modal Scrollable Body -->
        <div class="p-5 sm:p-6 overflow-y-auto flex-1 space-y-4 bg-slate-50/50">
            
            <!-- Driver & Vehicle Profile Strip -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <!-- Driver Info Left -->
                <div class="flex items-center gap-3.5">
                    <div class="relative">
                        <img id="modDriverAvatar" src="{{ asset('image/avatars/driver.svg') }}" 
                             alt="Driver Photo" 
                             class="w-14 h-14 rounded-2xl object-cover border-2 border-amber-400 shadow-sm bg-slate-100"
                             onerror="this.src='{{ asset('image/avatars/driver.svg') }}'">
                        <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Driver On Duty</p>
                        <h4 class="text-base font-black text-slate-900 leading-tight" id="modDriver">Driver Name</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <a id="modDriverPhoneLink" href="tel:" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:underline">
                                <i data-lucide="phone" class="w-3 h-3 text-blue-500"></i>
                                <span id="modDriverPhone">0991 123 4567</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Unit Info Right -->
                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl px-4 py-2.5 text-left sm:text-right w-full sm:w-auto">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Assigned Taxi Unit</p>
                    <div class="flex items-center justify-start sm:justify-end gap-2 mt-0.5">
                        <span id="modUnit" class="px-2.5 py-0.5 bg-blue-600 text-white font-mono font-black text-xs sm:text-sm rounded-lg tracking-wider uppercase shadow-xs">
                            EAB 8186
                        </span>
                    </div>
                    <p id="modUnitDetails" class="text-[11px] font-bold text-slate-600 mt-1">Toyota Vios 2015</p>
                </div>
            </div>

            <!-- Incident Overview Summary Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Incident Type</p>
                    <p class="text-xs sm:text-sm font-black text-slate-800" id="modType">Emergency Alert</p>
                </div>
                <div class="bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Damage Severity</p>
                    <p class="text-xs sm:text-sm font-black text-rose-600" id="modDamageLevel">Emergency SOS</p>
                </div>
                <div class="col-span-2 sm:col-span-1 bg-white p-3.5 rounded-2xl border border-slate-200/80 shadow-2xs">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Emergency Contact</p>
                    <p class="text-xs font-bold text-slate-700 truncate" id="modDriverEmergency">None recorded</p>
                </div>
            </div>

            <!-- Driver Message / Incident Statement -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="message-square-quote" class="w-4 h-4 text-amber-500"></i>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Driver's Message / Incident Narrative</p>
                </div>
                <div class="bg-gradient-to-br from-rose-50/50 via-amber-50/30 to-slate-50 border border-rose-100/80 p-4 rounded-xl text-xs sm:text-sm text-slate-800 leading-relaxed font-medium whitespace-pre-line" id="modDesc">
                    -
                </div>
            </div>

            <!-- Attached Photo Evidence from Driver App -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <div class="flex items-center justify-between mb-2.5">
                    <div class="flex items-center gap-2">
                        <i data-lucide="camera" class="w-4 h-4 text-blue-500"></i>
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Incident Photo Evidence</p>
                    </div>
                    <span id="modPhotoBadge" class="text-[9px] font-bold text-blue-600 uppercase tracking-wider bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full">
                        Attached Photo
                    </span>
                </div>

                <!-- When Photo is Available -->
                <div id="modPhotoContainer" class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-950 flex items-center justify-center p-2 cursor-pointer transition-all hover:border-blue-400" onclick="openPhotoLightbox(document.getElementById('modPhoto').src)">
                    <img id="modPhoto" src="" alt="Accident Photo" class="max-w-full h-auto max-h-[340px] object-contain rounded-xl transition-transform duration-300 group-hover:scale-[1.02]">
                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white font-black text-xs uppercase tracking-wider backdrop-blur-xs">
                        <i data-lucide="zoom-in" class="w-5 h-5"></i> Click to Zoom Fullscreen
                    </div>
                </div>

                <!-- When No Photo is Uploaded -->
                <div id="modNoPhotoContainer" class="p-6 bg-slate-50 border border-dashed border-slate-200 rounded-2xl text-center">
                    <div class="w-10 h-10 rounded-full bg-slate-200/60 flex items-center justify-center mx-auto mb-2 text-slate-400">
                        <i data-lucide="image-off" class="w-5 h-5"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-600">No On-Scene Photo Attached</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">The driver sent an emergency SOS alert ping without an image attachment.</p>
                </div>
            </div>

            <!-- GPS Location Address & Google Maps -->
            <div id="modLocationContainer" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="map-pin" class="w-4 h-4 text-emerald-500"></i>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">GPS Coordinates & Location Address</p>
                </div>
                <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200/80 mb-3">
                    <p class="text-xs sm:text-sm font-bold text-slate-800" id="modAddress">Fetching address...</p>
                </div>
                <a id="modLocationLink" href="#" target="_blank" class="w-full flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white py-3 rounded-xl shadow-md shadow-blue-500/20 transition-all font-black text-xs uppercase tracking-wider">
                    <i data-lucide="map-pin" class="w-4 h-4"></i> View Exact Location on Google Maps
                </a>
            </div>
        </div>

        <!-- Footer Action Buttons -->
        <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-200/80 flex items-center justify-between gap-3 shrink-0">
            <a id="modCallBtn" href="tel:" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200/80 font-black text-xs rounded-xl transition-all uppercase tracking-wider">
                <i data-lucide="phone-call" class="w-3.5 h-3.5"></i> Call Driver
            </a>
            <button onclick="closeAccidentModal()" type="button" class="px-5 py-2 bg-slate-200/80 hover:bg-slate-300 text-slate-700 font-black text-xs rounded-xl transition-all uppercase tracking-wider">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Photo Lightbox Modal -->
<div id="accidentPhotoLightbox" class="hidden fixed inset-0 bg-black/90 backdrop-blur-md z-[100000] flex items-center justify-center p-4" onclick="closePhotoLightbox()">
    <div class="relative max-w-4xl max-h-[90vh] flex items-center justify-center" onclick="event.stopPropagation()">
        <img id="lightboxPhotoImg" src="" alt="Full Screen Accident Photo" class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl">
        <button onclick="closePhotoLightbox()" type="button" class="absolute -top-12 right-0 text-white/80 hover:text-white bg-white/10 hover:bg-white/20 p-2 rounded-full backdrop-blur-sm transition-all">
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
    </div>
</div>

<script>
    window.openAccidentModal = function(el) {
        if (!el) return;
        const row = (el instanceof HTMLElement) ? (el.classList.contains('accident-row') ? el : el.closest('.accident-row')) : null;
        if (!row) return;

        const id = row.getAttribute('data-id') || '';
        const driver = row.getAttribute('data-driver') || '-';
        const driverPhoto = row.getAttribute('data-driver-photo') || '';
        const driverPhone = row.getAttribute('data-driver-phone') || 'Not provided';
        const driverEmergency = row.getAttribute('data-driver-emergency') || 'None recorded';
        
        const unit = row.getAttribute('data-unit') || '-';
        const unitDetails = row.getAttribute('data-unit-details') || 'Fleet Vehicle';
        const date = row.getAttribute('data-date') || '-';
        const type = row.getAttribute('data-type') || 'Emergency Alert';
        const damageLevel = row.getAttribute('data-damage-level') || 'Emergency SOS';
        const desc = row.getAttribute('data-description') || 'No description provided.';
        const status = row.getAttribute('data-status') || '-';
        const photo = row.getAttribute('data-photo') || '';
        const lat = row.getAttribute('data-latitude') || '';
        const lng = row.getAttribute('data-longitude') || '';

        // Driver details
        const modDriver = document.getElementById('modDriver');
        const modDriverAvatar = document.getElementById('modDriverAvatar');
        const modDriverPhone = document.getElementById('modDriverPhone');
        const modDriverPhoneLink = document.getElementById('modDriverPhoneLink');
        const modCallBtn = document.getElementById('modCallBtn');
        const modDriverEmergency = document.getElementById('modDriverEmergency');

        if (modDriver) modDriver.textContent = driver;
        if (modDriverAvatar) modDriverAvatar.src = driverPhoto || '{{ asset("image/avatars/driver.svg") }}';
        if (modDriverPhone) modDriverPhone.textContent = driverPhone;
        if (modDriverPhoneLink) modDriverPhoneLink.href = 'tel:' + driverPhone.replace(/[^0-9+]/g, '');
        if (modCallBtn) modCallBtn.href = 'tel:' + driverPhone.replace(/[^0-9+]/g, '');
        if (modDriverEmergency) modDriverEmergency.textContent = driverEmergency;

        // Vehicle details
        const modUnit = document.getElementById('modUnit');
        const modUnitDetails = document.getElementById('modUnitDetails');
        if (modUnit) modUnit.textContent = unit;
        if (modUnitDetails) modUnitDetails.textContent = unitDetails;

        // Incident info
        const modHeaderTitle = document.getElementById('modHeaderTitle');
        const modDate = document.getElementById('modDate');
        const modType = document.getElementById('modType');
        const modDamageLevel = document.getElementById('modDamageLevel');
        const modDesc = document.getElementById('modDesc');
        const modStatusBadge = document.getElementById('modStatusBadge');

        if (modHeaderTitle) modHeaderTitle.textContent = type;
        if (modDate) modDate.textContent = 'Reported on ' + date;
        if (modType) modType.textContent = type;
        if (modDamageLevel) modDamageLevel.textContent = damageLevel;
        if (modDesc) modDesc.textContent = desc;
        
        if (modStatusBadge) {
            modStatusBadge.textContent = status;
            if (status === 'PENDING') {
                modStatusBadge.className = 'text-[9px] font-black uppercase px-2 py-0.5 rounded-full border bg-rose-500/20 border-rose-500/40 text-rose-300';
            } else {
                modStatusBadge.className = 'text-[9px] font-black uppercase px-2 py-0.5 rounded-full border bg-emerald-500/20 border-emerald-500/40 text-emerald-300';
            }
        }
        
        // Photo section
        const photoContainer = document.getElementById('modPhotoContainer');
        const noPhotoContainer = document.getElementById('modNoPhotoContainer');
        const photoEl = document.getElementById('modPhoto');
        const photoBadge = document.getElementById('modPhotoBadge');

        if (photo && photo.trim().length > 0) {
            if (photoEl) photoEl.src = photo;
            if (photoContainer) photoContainer.style.display = 'flex';
            if (noPhotoContainer) noPhotoContainer.style.display = 'none';
            if (photoBadge) {
                photoBadge.textContent = 'Photo Attached';
                photoBadge.className = 'text-[9px] font-bold text-blue-600 uppercase tracking-wider bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full';
            }
        } else {
            if (photoContainer) photoContainer.style.display = 'none';
            if (noPhotoContainer) noPhotoContainer.style.display = 'block';
            if (photoBadge) {
                photoBadge.textContent = 'No Photo Attached';
                photoBadge.className = 'text-[9px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-full';
            }
        }
        
        // Location section
        const locationContainer = document.getElementById('modLocationContainer');
        const locationLink = document.getElementById('modLocationLink');
        const addressEl = document.getElementById('modAddress');
        
        if (lat && lng) {
            if (locationLink) locationLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
            if (locationContainer) locationContainer.style.display = 'block';
            
            // Get cached address from table row
            const tableAddr = document.getElementById('addr-' + id);
            if (tableAddr && tableAddr.textContent && tableAddr.textContent !== 'Fetching address...') {
                if (addressEl) addressEl.textContent = tableAddr.textContent;
            } else {
                if (addressEl) addressEl.textContent = 'Fetching address...';
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
                    .then(res => res.json())
                    .then(resData => {
                        if (addressEl) addressEl.textContent = resData.display_name || 'Address not found';
                    })
                    .catch(() => {
                        if (addressEl) addressEl.textContent = 'Coordinates: ' + lat + ', ' + lng;
                    });
            }
        } else {
            if (locationContainer) locationContainer.style.display = 'none';
        }
        
        const modal = document.getElementById('accidentModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
        
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    };
    
    window.closeAccidentModal = function() {
        const modal = document.getElementById('accidentModal');
        if (modal) modal.classList.add('hidden');
    };

    window.openPhotoLightbox = function(src) {
        if (!src) return;
        const lb = document.getElementById('accidentPhotoLightbox');
        const img = document.getElementById('lightboxPhotoImg');
        if (img) img.src = src;
        if (lb) lb.classList.remove('hidden');
        if (window.lucide && typeof window.lucide.createIcons === 'function') {
            window.lucide.createIcons();
        }
    };

    window.closePhotoLightbox = function() {
        const lb = document.getElementById('accidentPhotoLightbox');
        if (lb) lb.classList.add('hidden');
    };

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            window.closePhotoLightbox();
            window.closeAccidentModal();
        }
    });

    async function processTableGeocoding() {
        const elements = document.querySelectorAll('.reverse-geocode');
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            const lat = el.getAttribute('data-lat');
            const lng = el.getAttribute('data-lng');
            if (lat && lng) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
                    const data = await response.json();
                    el.textContent = data.display_name || 'Address not found';
                    el.setAttribute('title', data.display_name);
                } catch (e) {
                    el.textContent = 'Coordinates: ' + lat + ', ' + lng;
                }
                // Wait 1.1s to respect Nominatim rate limit (max 1 req/sec)
                await new Promise(r => setTimeout(r, 1100));
            }
        }
    }

    function initAccidentsPage() {
        processTableGeocoding();

        const searchInput = document.getElementById('accidentSearchInput');
        const dateFilter = document.getElementById('accidentDateFilter');
        const tableBody = document.querySelector('table tbody');
        if (!tableBody) return;
        const rows = tableBody.querySelectorAll('tr.accident-row');

        function filterTable() {
            const searchTerm = (searchInput ? searchInput.value : '').toLowerCase();
            const dateTerm = dateFilter ? dateFilter.value : '';
            
            let visibleCount = 0;

            rows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                const dateText = row.querySelector('td:first-child')?.textContent || '';
                
                let matchesDate = true;
                if (dateTerm) {
                    const parsedRowDate = new Date(dateText.split('\n')[0]);
                    if (!isNaN(parsedRowDate)) {
                        const rowDateStr = parsedRowDate.getFullYear() + '-' + 
                                        String(parsedRowDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                        String(parsedRowDate.getDate()).padStart(2, '0');
                        matchesDate = (rowDateStr === dateTerm);
                    }
                }

                const matchesSearch = textContent.includes(searchTerm);

                if (matchesSearch && matchesDate) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle no results
            let noResultsRow = tableBody.querySelector('.no-results-filter');
            if (visibleCount === 0 && rows.length > 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-filter';
                    noResultsRow.innerHTML = `
                        <td colspan="7" class="px-5 py-8 text-center text-gray-500 text-sm">
                            No accident reports match your search criteria.
                        </td>
                    `;
                    tableBody.appendChild(noResultsRow);
                }
                noResultsRow.style.display = '';
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        }

        if (searchInput) {
            let isUserTyping = false;

            searchInput.addEventListener('keydown', function() { isUserTyping = true; });
            searchInput.addEventListener('input', function() { 
                isUserTyping = true;
                filterTable();
            });

            const killAutofill = () => {
                if (!isUserTyping && searchInput.value && (searchInput.value.includes('@') || searchInput.value.includes('.com') || searchInput.value.includes('gmail'))) {
                    searchInput.value = '';
                    filterTable();
                }
            };

            killAutofill();
            window.addEventListener('pageshow', killAutofill);
            const autofillCheckInterval = setInterval(killAutofill, 50);
            setTimeout(() => clearInterval(autofillCheckInterval), 3500);

            searchInput.addEventListener('focus', function() {
                this.removeAttribute('readonly');
                killAutofill();
            });
        }
        if (dateFilter) dateFilter.addEventListener('change', filterTable);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAccidentsPage);
    } else {
        initAccidentsPage();
    }
    document.addEventListener('page:loaded', initAccidentsPage);
</script>
@endsection
