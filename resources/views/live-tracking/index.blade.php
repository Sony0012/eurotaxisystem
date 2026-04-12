@extends('layouts.app')

@section('title', 'Live Tracking - Euro System')
@section('page-heading', 'Live Tracking')
@section('page-subheading', 'Real-time GPS monitoring of all taxi units')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .unit-item {
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            background-color: #fff;
            overflow: hidden;
        }

        .unit-item:hover {
            background-color: #fefce8;
            transform: scale(1.01);
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .unit-item.selected {
            background-color: #fef9c3;
            border-left-color: #ca8a04;
        }

        /* Hover Reveal for Drivers */
        .driver-reveal {
            max-height: 0;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .unit-item:hover .driver-reveal,
        .unit-item.selected .driver-reveal {
            max-height: 100px;
            opacity: 1;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .unit-panel {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            background-color: #fff;
        }

        .map-container {
            flex: 1;
            background-color: #f3f4f6;
            position: relative;
            overflow: hidden;
        }

        #mapViewer {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .custom-div-icon {
            background: none;
            border: none;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    {{-- Map + Unit List --}}
    <div class="grid grid-cols-1 lg:grid-cols-4 xl:grid-cols-5 gap-4">

        {{-- Unit List --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                <div class="px-4 py-4 border-b bg-gray-50/50 flex flex-col gap-3 shrink-0">
                    <div class="flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                            <i data-lucide="layers" class="w-4 h-4 text-yellow-600"></i>
                            Unit Explorer
                        </h3>
                    </div>
                    
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                        </div>
                        <input type="text" id="unitSearchInput"
                            class="block w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all outline-none"
                            placeholder="Search plate...">
                    </div>
                    
                    <div class="flex gap-2 w-full">
                        <select id="statusFilterSelect"
                            class="block w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition-all outline-none bg-white">
                            <option value="">All Fleet Units</option>
                            <option value="active">Active (On/Idle)</option>
                            <option value="offline">Offline / Stopped</option>
                        </select>
                    </div>
                </div>
                <div class="unit-panel flex-1 overflow-y-auto no-scrollbar" id="unitList">
                    @forelse($tracked_units as $unit)
                        <div class="unit-item p-2.5 border-b border-gray-100 {{ $unit->gps_status === 'offline' ? 'opacity-70' : '' }}"
                            data-unit-id="{{ $unit->id }}"
                            data-plate-number="{{ $unit->plate_number }}" 
                            data-driver-name="{{ $unit->driver_name ?? '' }}"
                            data-secondary-driver="{{ $unit->secondary_driver ?? '' }}"
                            data-status="{{ $unit->gps_status }}"
                            onclick="selectUnit(this)">
                            
                            <!-- Row 1: Plate & Status -->
                            <div class="flex justify-between items-center mb-1.5">
                                <div class="font-black text-[15px] text-gray-900 tracking-tight leading-none uppercase">
                                    {{ $unit->plate_number }}
                                </div>
                                <div class="status-badge" id="status-unit-{{ $unit->id }}">
                                    @php
                                        $statusClass = [
                                            'moving' => 'bg-green-50 text-green-700 border-green-100',
                                            'idle' => 'bg-yellow-50 text-yellow-700 border-yellow-100',
                                            'stopped' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'offline' => 'bg-gray-50 text-gray-400 border-gray-100'
                                        ][$unit->gps_status] ?? 'bg-gray-50 text-gray-400 border-gray-100';
                                    @endphp
                                    <span class="px-1.5 py-0.5 text-[8.5px] font-black uppercase tracking-tighter rounded-sm border {{ $statusClass }}">
                                        {{ ucfirst($unit->gps_status) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Hover Reveal Section: Drivers (Hidden by default) -->
                            <div class="driver-reveal flex flex-col gap-1.5 border-t border-gray-50 pt-1">
                                <!-- Primary -->
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <i data-lucide="user" class="w-3 h-3 text-blue-500 shrink-0"></i> 
                                    <div class="flex items-center gap-1 min-w-0">
                                        <span class="text-[8.5px] font-black text-gray-400 uppercase shrink-0">D1</span>
                                        <span class="driver-primary text-[11px] font-bold text-gray-700 leading-none truncate">{{ $unit->driver_name ?: 'None' }}</span>
                                    </div>
                                </div>
                                
                                <!-- Secondary -->
                                <div class="secondary-driver-container flex items-center gap-1.5 min-w-0 {{ !$unit->secondary_driver ? 'hidden' : '' }}">
                                    <i data-lucide="users" class="w-3 h-3 text-gray-400 shrink-0"></i> 
                                    <div class="flex items-center gap-1 min-w-0">
                                        <span class="text-[8.5px] font-black text-gray-400 uppercase shrink-0">D2</span>
                                        <span class="driver-secondary text-[11px] font-bold text-gray-500 leading-none truncate">{{ $unit->secondary_driver }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 3: Engine & Speed (Compact Row) -->
                            <div class="flex justify-between items-center text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                <div class="flex items-center gap-1" id="engine-status-container-{{ $unit->id }}">
                                    <i data-lucide="zap" class="w-3 h-3 {{ $unit->ignition_status ? 'text-green-500' : 'text-gray-300' }}"></i>
                                    <span>{{ $unit->ignition_status ? 'Engine ON' : 'Engine OFF' }}</span>
                                </div>
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-[13px] font-black text-gray-800 unit-speed">{{ number_format($unit->speed ?? 0, 1) }}</span>
                                    <span class="text-[8.5px]">km/h</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-10 text-center text-gray-400 bg-gray-50/20">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 opacity-20"></i>
                            <p class="text-sm font-medium">No units found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Leaflet Map Container --}}
        <div class="lg:col-span-3 xl:col-span-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-[600px]">
                <div class="px-5 py-3 border-b bg-gray-50/50 flex flex-col lg:flex-row justify-between items-center shrink-0 gap-3">
                    <h3 class="font-bold text-gray-800 text-sm flex items-center gap-2">
                        <i data-lucide="map" class="w-4 h-4 text-blue-600"></i>
                        <span class="hidden md:inline">Live Fleet Map</span>
                    </h3>

                    {{-- Compact Live Stats --}}
                    <div class="flex items-center divide-x divide-gray-200 border border-gray-200 rounded-lg bg-white overflow-hidden shadow-sm flex-wrap text-center">
                        <div class="flex flex-col md:flex-row px-2 md:px-3 py-1 items-center gap-0 md:gap-1.5">
                            <span class="text-[9px] md:text-[10px] text-gray-400 uppercase font-black tracking-widest">Total</span>
                            <span id="stat-total" class="text-sm font-black text-gray-900">{{ $stats['total'] ?? 0 }}</span>
                        </div>
                        <div class="flex flex-col md:flex-row px-2 md:px-3 py-1 items-center gap-0 md:gap-1.5 bg-green-50/30">
                            <span class="text-[9px] md:text-[10px] text-green-500 uppercase font-black tracking-widest">Moving</span>
                            <span id="stat-active" class="text-sm font-black text-green-600">{{ $stats['moving'] ?? 0 }}</span>
                        </div>
                        <div class="flex flex-col md:flex-row px-2 md:px-3 py-1 items-center gap-0 md:gap-1.5 bg-yellow-50/30">
                            <span class="text-[9px] md:text-[10px] text-yellow-500 uppercase font-black tracking-widest">Idle</span>
                            <span id="stat-idle" class="text-sm font-black text-yellow-600">{{ $stats['idle'] ?? 0 }}</span>
                        </div>
                        <div class="flex flex-col md:flex-row px-2 md:px-3 py-1 items-center gap-0 md:gap-1.5 bg-blue-50/30">
                            <span class="text-[9px] md:text-[10px] text-blue-500 uppercase font-black tracking-widest">Stopped</span>
                            <span id="stat-stopped" class="text-sm font-black text-blue-600">{{ $stats['stopped'] ?? 0 }}</span>
                        </div>
                        <div class="flex flex-col md:flex-row px-2 md:px-3 py-1 items-center gap-0 md:gap-1.5 bg-gray-50/80">
                            <span class="text-[9px] md:text-[10px] text-gray-400 uppercase font-black tracking-widest">Offline</span>
                            <span id="stat-offline" class="text-sm font-black text-gray-500">{{ $stats['offline'] ?? 0 }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        {{-- API Status Indicator --}}
                        <div class="flex items-center gap-1.5 border-l pl-3 border-gray-100">
                            @if($apiActive)
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                <span class="text-[10px] font-black text-green-600 uppercase">API Online</span>
                            @else
                                <span class="relative flex h-2 w-2">
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-400"></span>
                                </span>
                                <span class="text-[10px] font-black text-red-500 uppercase">API Setup Required</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!$apiActive)
                    <div class="bg-amber-50 border-b border-amber-200 px-5 py-3 flex items-center gap-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600"></i>
                        <p class="text-sm text-amber-800">
                            <strong>Tracksolid API is currently inactive.</strong> Live GPS coordinates cannot be fetched. Please check credentials or use the 
                            <a href="https://tracksolidpro.com/" target="_blank" class="font-bold underline">Tracksolid Pro Portal</a> as a backup.
                        </p>
                    </div>
                @endif
                <div class="map-container">
                    <div id="mapViewer"></div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/realtime-tracking.js') }}?v={{ time() }}"></script>
@endpush