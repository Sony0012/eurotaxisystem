@extends('layouts.app')

@section('title', 'Live Tracking - Euro System')
@section('page-heading', 'Live Tracking')
@section('page-subheading', 'Real-time GPS monitoring of all taxi units')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 520px; width: 100%; border-radius: 0.5rem; }
        .unit-item { cursor: pointer; transition: background-color 0.2s; }
        .unit-item:hover { background-color: #fefce8; }
        .unit-item.selected { background-color: #fef9c3; border-left: 3px solid #ca8a04; }
        .unit-panel { height: 520px; overflow-y: auto; }
    </style>
@endpush

@section('content')

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-4 text-center">
                <p class="text-sm text-gray-500">Total Units</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-4 text-center">
                <p class="text-sm text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['active'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-4 text-center">
                <p class="text-sm text-gray-500">Idle</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $stats['idle'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-4 text-center">
                <p class="text-sm text-gray-500">Offline</p>
                <p class="text-2xl font-bold text-red-600">{{ $stats['offline'] ?? 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-4 text-center">
                <p class="text-sm text-gray-500">Avg Speed</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['avg_speed'] ?? 0, 0) }} km/h</p>
            </div>
        </div>
    </div>

    {{-- Tracking Controls --}}
    <div class="bg-white rounded-lg shadow p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" id="unitSearchInput" onkeyup="filterUnits()"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
                        placeholder="Search unit...">
                </div>
            </div>
            <div class="sm:w-40">
                <select id="statusFilterSelect" onchange="filterUnits()" class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="idle">Idle</option>
                    <option value="offline">Offline</option>
                </select>
            </div>
            <button type="button" id="autoRefreshBtn" onclick="toggleAutoRefresh()"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                <span id="autoRefreshText">Auto Refresh: ON</span>
            </button>
        </div>
    </div>

    {{-- Map + Unit List --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Map (2/3) --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div id="map"></div>
            </div>
        </div>

        {{-- Unit List (1/3) --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-4 py-3 border-b">
                    <h3 class="font-semibold text-gray-800">Units</h3>
                </div>
                <div class="unit-panel" id="unitList">
                    @forelse($units as $unit)
                        <div
                            class="unit-item p-4 border-b"
                            data-unit-id="{{ $unit->id }}"
                            data-unit-number="{{ $unit->unit_number }}"
                            data-lat="{{ $unit->latitude ?? 10.72 }}"
                            data-lng="{{ $unit->longitude ?? 122.56 }}"
                            data-status="{{ $unit->gps_status ?? 'offline' }}"
                            onclick="selectUnit(this)"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-gray-900 text-sm">{{ $unit->unit_number }}</div>
                                    <div class="text-xs text-gray-500">{{ $unit->plate_number }}</div>
                                    <div class="text-xs text-gray-500">
                                        Driver: {{ $unit->current_driver ?? 'None' }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if(($unit->gps_status ?? 'offline') === 'active') bg-green-100 text-green-800
                                        @elseif(($unit->gps_status ?? 'offline') === 'idle') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($unit->gps_status ?? 'offline') }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ number_format($unit->speed ?? 0, 0) }} km/h
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 mt-1">
                                Updated: {{ isset($unit->last_gps_update) ? \Carbon\Carbon::parse($unit->last_gps_update)->diffForHumans() : 'N/A' }}
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500 text-sm">No units with GPS data</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Map initialization
        const map = L.map('map').setView([10.72, 122.56], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const markers = {};
        let autoRefreshEnabled = true;
        let refreshInterval = null;

        // Color markers
        function markerIcon(status) {
            const colors = { active: '#22c55e', idle: '#eab308', offline: '#ef4444' };
            const color = colors[status] || '#6b7280';
            return L.divIcon({
                className: '',
                html: `<div style="width:16px;height:16px;border-radius:50%;background:${color};border:2px solid white;box-shadow:0 1px 3px rgba(0,0,0,0.3)"></div>`,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            });
        }

        // Add all unit markers
        document.querySelectorAll('.unit-item').forEach(el => {
            const lat = parseFloat(el.dataset.lat);
            const lng = parseFloat(el.dataset.lng);
            const status = el.dataset.status;
            const unitNumber = el.dataset.unitNumber;
            const id = el.dataset.unitId;

            if (!isNaN(lat) && !isNaN(lng)) {
                const marker = L.marker([lat, lng], { icon: markerIcon(status) }).addTo(map);
                marker.bindPopup(`
                    <div class="text-sm">
                        <strong>${unitNumber}</strong><br/>
                        <span style="color:${status === 'active' ? '#22c55e' : status === 'idle' ? '#ca8a04' : '#ef4444'}">${status.charAt(0).toUpperCase() + status.slice(1)}</span><br/>
                        <a href="{{ url('units') }}/${id}" style="color:#2563eb;font-size:0.75rem;">View Details</a>
                    </div>
                `);
                markers[id] = marker;
            }
        });

        function selectUnit(el) {
            document.querySelectorAll('.unit-item').forEach(i => i.classList.remove('selected'));
            el.classList.add('selected');

            const lat = parseFloat(el.dataset.lat);
            const lng = parseFloat(el.dataset.lng);
            const id = el.dataset.unitId;

            if (!isNaN(lat) && !isNaN(lng)) {
                map.setView([lat, lng], 16);
                if (markers[id]) {
                    markers[id].openPopup();
                }
            }
        }

        function filterUnits() {
            const search = document.getElementById('unitSearchInput').value.toLowerCase();
            const status = document.getElementById('statusFilterSelect').value;

            document.querySelectorAll('.unit-item').forEach(el => {
                const unitNum = el.dataset.unitNumber.toLowerCase();
                const unitStatus = el.dataset.status;
                const matchSearch = !search || unitNum.includes(search);
                const matchStatus = !status || unitStatus === status;
                el.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        }

        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            const btn = document.getElementById('autoRefreshBtn');
            const text = document.getElementById('autoRefreshText');

            if (autoRefreshEnabled) {
                btn.classList.replace('bg-gray-500', 'bg-green-600');
                text.textContent = 'Auto Refresh: ON';
                startRefresh();
            } else {
                btn.classList.replace('bg-green-600', 'bg-gray-500');
                text.textContent = 'Auto Refresh: OFF';
                if (refreshInterval) {
                    clearInterval(refreshInterval);
                    refreshInterval = null;
                }
            }
        }

        function startRefresh() {
            if (refreshInterval) clearInterval(refreshInterval);
            refreshInterval = setInterval(() => {
                if (autoRefreshEnabled) location.reload();
            }, 30000);
        }

        startRefresh();
    </script>
@endpush