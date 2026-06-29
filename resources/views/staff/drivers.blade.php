@extends('layouts.app')

@section('page-heading', 'Mobile App Drivers')
@section('page-subheading', 'Manage driver accounts registered on the mobile application')

@section('content')
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border">
        <form action="{{ route('staff.drivers') }}" method="GET" class="relative max-w-md">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Search mobile app drivers by name or email..." 
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none text-sm">
        </form>
    </div>

    <!-- Drivers Table -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-green-100 rounded-lg">
                <i data-lucide="smartphone" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Mobile App Drivers</h2>
                <p class="text-sm text-gray-500">Drivers with registered mobile accounts</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Email/Phone</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">IP Address</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Phone ID (Device)</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Status</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($appDrivers as $driver)
                            @php
                                $latestBrowser = $driver->verifiedBrowsers->sortByDesc('last_active_at')->first();
                            @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-xs uppercase">
                                        {{ substr($driver->full_name ?? $driver->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $driver->full_name ?? $driver->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <div>{{ $driver->email ?? '---' }}</div>
                                <div class="text-xs text-gray-400">{{ $driver->phone ?? $driver->phone_number ?? '---' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-mono">
                                {{ $latestBrowser ? $latestBrowser->ip_address : '---' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($latestBrowser)
                                    <div class="truncate max-w-[200px]" title="{{ $latestBrowser->device_info ?? $latestBrowser->user_agent }}">
                                        {{ $latestBrowser->device_info ?? $latestBrowser->user_agent ?? 'Unknown Device' }}
                                    </div>
                                @else
                                    ---
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $driver->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $driver->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right relative">
                                <div class="inline-block text-left">
                                    <button type="button" 
                                        onclick="toggleDriversDropdown('app-driver-dropdown-{{ $driver->uuid }}', event)"
                                        class="p-2 hover:bg-gray-100 rounded-full transition-colors focus:outline-none">
                                        <i data-lucide="more-vertical" class="w-4 h-4 text-gray-500"></i>
                                    </button>
                                    <div id="app-driver-dropdown-{{ $driver->uuid }}" 
                                        class="driver-action-dropdown absolute right-6 mt-1 w-32 bg-white border border-gray-100 rounded-xl shadow-xl z-50 hidden animate-in fade-in zoom-in-95 duration-200 overflow-hidden">
                                        <div class="p-1.5 space-y-1">
                                            <form action="{{ route('staff.destroyAppDriver', $driver->uuid) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this Driver\'s Mobile App Account? They will lose access to the app immediately.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg transition-all text-left">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    Delete Account
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm italic">No app drivers found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Dropdown Toggle Logic
    window.toggleDriversDropdown = function(id, event) {
        event.stopPropagation();
        
        // Close all other dropdowns
        document.querySelectorAll('.driver-action-dropdown').forEach(el => {
            if (el.id !== id) {
                el.classList.add('hidden');
            }
        });

        const dropdown = document.getElementById(id);
        if (dropdown) {
            dropdown.classList.toggle('hidden');
        }
    };

    // Close dropdowns on outside click
    document.addEventListener('click', function() {
        document.querySelectorAll('.driver-action-dropdown').forEach(el => {
            el.classList.add('hidden');
        });
    });
</script>
@endpush

