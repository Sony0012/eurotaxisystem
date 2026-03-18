@extends('layouts.app')

@section('title', 'Live Tracking - Euro System')
@section('page-heading', 'Live Unit Tracking')
@section('page-subheading', 'Real-time GPS monitoring')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_active'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Active Units</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $stats['online_now'] ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Online Now</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Map Placeholder --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">GPS Map</h3>
                <span class="text-xs text-green-600 flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Live
                </span>
            </div>
            <div class="bg-gray-100 flex items-center justify-center" style="height: 420px;">
                @if($tracked_units->whereNotNull('latitude')->count() > 0)
                    {{-- Leaflet Map --}}
                    <div id="mapContainer" style="width:100%;height:420px;"></div>
                @else
                    <div class="text-center text-gray-500">
                        <i data-lucide="map" class="w-16 h-16 mx-auto mb-3 text-gray-300"></i>
                        <p class="text-sm font-medium">No GPS data available</p>
                        <p class="text-xs mt-1">GPS devices will appear here once they start transmitting.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Unit List --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-5 py-4 border-b">
            <h3 class="font-semibold text-gray-900">Active Units</h3>
        </div>
        <div class="overflow-y-auto" style="max-height: 420px;">
            @forelse($tracked_units as $unit)
            @php
                $hasGps     = !is_null($unit->latitude);
                $isOnline   = $hasGps && $unit->last_seen && strtotime($unit->last_seen) >= strtotime('-5 minutes');
                $ignition   = $unit->ignition_status ?? 0;
            @endphp
            <div class="px-4 py-3 border-b hover:bg-gray-50 cursor-pointer">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $unit->unit_number }}</p>
                        <p class="text-xs text-gray-500">{{ $unit->plate_number }}</p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                            {{ $isOnline ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $isOnline ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                            {{ $isOnline ? 'Online' : 'Offline' }}
                        </span>
                    </div>
                </div>
                @if($hasGps)
                <div class="mt-1 grid grid-cols-3 gap-1 text-xs text-gray-500">
                    <span>🚗 {{ number_format($unit->speed ?? 0, 0) }} km/h</span>
                    <span>🧭 {{ number_format($unit->heading ?? 0, 0) }}°</span>
                    <span>🔑 {{ $ignition ? 'ON' : 'OFF' }}</span>
                </div>
                @endif
                @if($unit->driver_name)
                <p class="text-xs text-gray-400 mt-1">👤 {{ $unit->driver_name }}</p>
                @endif
                @if($unit->timestamp)
                <p class="text-xs text-gray-400 mt-0.5">Last seen: {{ formatDateTime($unit->timestamp) }}</p>
                @endif
            </div>
            @empty
            <div class="px-4 py-12 text-center text-gray-400">
                <i data-lucide="truck" class="w-10 h-10 mx-auto mb-2 text-gray-300"></i>
                <p class="text-sm">No active units.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@if($tracked_units->whereNotNull('latitude')->count() > 0)
const map = L.map('mapContainer').setView([
    {{ $tracked_units->whereNotNull('latitude')->avg('latitude') }},
    {{ $tracked_units->whereNotNull('longitude')->avg('longitude') }}
], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

const units = @json($tracked_units->whereNotNull('latitude')->values());
units.forEach(function(u) {
    const marker = L.marker([u.latitude, u.longitude]).addTo(map);
    marker.bindPopup(`
        <strong>${u.unit_number}</strong><br>
        ${u.plate_number}<br>
        Driver: ${u.driver_name || 'Unassigned'}<br>
        Speed: ${parseFloat(u.speed || 0).toFixed(0)} km/h<br>
        Ignition: ${u.ignition_status ? 'ON' : 'OFF'}
    `);
});
@endif
</script>
@endpush
@endsection