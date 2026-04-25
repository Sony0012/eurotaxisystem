<div class="space-y-4">
    <!-- Unit Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-4 rounded-xl text-white shadow-lg">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-xl font-black leading-none tracking-tight">{{ $unit->plate_number }}</h3>
                    <span class="px-2 py-0.5 bg-white bg-opacity-20 rounded-full text-[10px] font-black uppercase tracking-widest border border-white border-opacity-30">
                        {{ ucfirst($unit->status ?? '') }}
                    </span>
                    <span class="px-2 py-0.5 bg-white bg-opacity-20 rounded-full text-[10px] font-black uppercase tracking-widest border border-white border-opacity-30">
                        {{ ucfirst($unit->unit_type ?? 'Standard') }}
                    </span>
                    @if(($unit->status ?? '') === 'surveillance')
                        <span class="px-2 py-0.5 bg-red-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest animate-pulse">🚨 Under Surveillance</span>
                    @endif
                </div>
                <p class="text-xs text-blue-100 font-medium tracking-wide">{{ ($unit->make ?? '') . ' ' . ($unit->model ?? '') . ' (' . ($unit->year ?? '') . ')' }}</p>
            </div>
            <div class="text-right">
                @php
                    $displayRate = isset($unit->current_pricing['rate']) ? $unit->current_pricing['rate'] : ($unit->boundary_rate ?? 0);
                    $rateLabel = isset($unit->current_pricing['label']) ? $unit->current_pricing['label'] : 'Daily Boundary Rate';
                @endphp
                <div class="text-xl font-black leading-none mb-1">₱{{ number_format((float) $displayRate, 2) }}</div>
                <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest">{{ $rateLabel }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex w-full overflow-x-auto scrollbar-none">
            <button onclick="showTab('overview')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-blue-500 font-black text-[11px] uppercase tracking-widest text-blue-600 transition-all duration-200 whitespace-nowrap" data-tab="overview">
                Overview
            </button>
            <button onclick="showTab('drivers')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="drivers">
                Drivers
            </button>
            <button onclick="showTab('coding')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="coding">
                Coding
            </button>
            <button onclick="showTab('boundary')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="boundary">
                Boundary
            </button>
            <button onclick="showTab('maintenance')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="maintenance">
                Maintenance
            </button>
            <button onclick="showTab('roi')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="roi">
                ROI
            </button>
            <button onclick="showTab('location')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="location">
                Location
            </button>
            <button onclick="showTab('dashcam')" class="tab-btn flex-1 py-3 px-1 border-b-2 border-transparent font-black text-[11px] uppercase tracking-widest text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-all duration-200 whitespace-nowrap" data-tab="dashcam">
                Dashcam
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    <div id="tabContent" class="pt-2">
        <div id="overview-tab" class="tab-content">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-blue-50 rounded-lg">
                            <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Drivers</p>
                            <p class="text-xl font-black text-gray-900">{{ count($assigned_drivers) }}/2</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-green-50 rounded-lg">
                            <i data-lucide="calendar" class="w-5 h-5 text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Next Coding</p>
                            <p class="text-xl font-black text-gray-900">{{ $days_until_coding ?? 0 }}d</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-purple-50 rounded-lg">
                            <i data-lucide="trending-up" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">ROI</p>
                            <p class="text-xl font-black text-gray-900">{{ number_format((float) ($roi_data['roi_percentage'] ?? 0), 1) }}%</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-orange-50 rounded-lg">
                            <i data-lucide="wrench" class="w-5 h-5 text-orange-600"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Maint</p>
                            <p class="text-xl font-black text-gray-900">{{ count($maintenance_records) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Basic Information Section --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <h4 class="text-xs font-black text-gray-900 mb-5 flex items-center gap-2 uppercase tracking-widest border-b border-gray-50 pb-3">
                        <i data-lucide="info" class="w-4 h-4 text-blue-600"></i>
                        Basic Information
                    </h4>
                    <div class="space-y-4 text-xs">
                        <div class="flex justify-between items-center group">
                            <span class="text-gray-400 font-bold uppercase tracking-tight">Plate Number</span>
                            <span class="font-black text-gray-900 bg-gray-50 px-2 py-1 rounded">{{ $unit->plate_number }}</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-gray-400 font-bold uppercase tracking-tight">Vehicle</span>
                            <span class="font-black text-gray-700">{{ ($unit->make ?? '') . ' ' . ($unit->model ?? '') }}</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-gray-400 font-bold uppercase tracking-tight">Year</span>
                            <span class="font-black text-gray-700">{{ $unit->year }}</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-gray-400 font-bold uppercase tracking-tight">Status</span>
                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full bg-green-50 text-green-600 border border-green-100">
                                {{ ucfirst($unit->status ?? 'Active') }}
                            </span>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-50 mt-4 space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Created By</span>
                                <span class="text-[11px] font-bold text-gray-600">{{ !empty($unit->created_at) ? \Carbon\Carbon::parse($unit->created_at)->format('M d, Y h:i A') : 'System' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest">Last Update</span>
                                <span class="text-[11px] font-bold text-gray-600">{{ !empty($unit->updated_at) ? \Carbon\Carbon::parse($unit->updated_at)->format('M d, Y h:i A') : 'System' }}</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-4 border-t border-gray-50 mt-4">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Boundary Rate</span>
                            <span class="text-xl font-black text-blue-600">₱{{ number_format((float) $displayRate, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Driver Assignment Section --}}
                <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                    <h4 class="text-xs font-black text-gray-900 mb-5 flex items-center gap-2 uppercase tracking-widest border-b border-gray-50 pb-3">
                        <i data-lucide="users" class="w-4 h-4 text-blue-600"></i>
                        Driver Assignment
                    </h4>
                    <div class="space-y-4 text-xs">
                        <div class="flex justify-between items-center group">
                            <span class="text-gray-400 font-bold uppercase tracking-tight">Assigned Drivers</span>
                            <span class="font-black text-gray-900">{{ count($assigned_drivers) }}/2</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <span class="text-gray-400 font-bold uppercase tracking-tight">Availability</span>
                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full {{ count($assigned_drivers) >= 2 ? 'bg-red-50 text-red-600 border border-red-100' : 'bg-green-50 text-green-600 border border-green-100' }}">
                                {{ count($assigned_drivers) >= 2 ? 'Full' : 'Available' }}
                            </span>
                        </div>

                        <div class="mt-6 space-y-3">
                            @forelse($assigned_drivers as $driver)
                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 group hover:border-blue-200 transition-colors">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="text-sm font-black text-gray-900 group-hover:text-blue-600 transition-colors">{{ $driver->full_name }}</p>
                                        <span class="text-[9px] font-black bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded uppercase">Active</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-[11px]">
                                        <p class="text-gray-500 font-medium">{{ $driver->license_number ?? 'No License' }}</p>
                                        <p class="text-gray-500 font-medium text-right">Contact: {{ $driver->contact_number ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-12 text-center">
                                    <div class="bg-gray-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="user-x" class="w-6 h-6 text-gray-300"></i>
                                    </div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">No Drivers Assigned</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
