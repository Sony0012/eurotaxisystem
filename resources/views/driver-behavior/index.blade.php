@extends('layouts.app')

@section('title', 'Driver Behavior - Euro System')
@section('page-heading', 'Driver Behavior Monitoring')
@section('page-subheading', 'Track incidents and violations')

@section('content')
{{-- Filters --}}
<div class="bg-white rounded-lg shadow p-4 mb-5">
    <form method="GET" action="{{ route('driver-behavior.index') }}" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search driver, unit, type..."
            class="flex-1 min-w-[150px] px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
        <select name="severity" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            <option value="">All Severity</option>
            <option value="low" @selected($severity=='low')>Low</option>
            <option value="medium" @selected($severity=='medium')>Medium</option>
            <option value="high" @selected($severity=='high')>High</option>
            <option value="critical" @selected($severity=='critical')>Critical</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i> Filter
        </button>
        <button type="button" onclick="document.getElementById('addIncidentModal').classList.remove('hidden')"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i> Log Incident
        </button>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Incident Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Severity</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date/Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($incidents as $inc)
                @php
                    $sevClass = match($inc->severity) {
                        'critical' => 'bg-red-100 text-red-800',
                        'high'     => 'bg-orange-100 text-orange-800',
                        'medium'   => 'bg-yellow-100 text-yellow-800',
                        default    => 'bg-green-100 text-green-800',
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $inc->driver_name ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-700">{{ $inc->unit_number ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ ucwords(str_replace('_', ' ', $inc->incident_type)) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $sevClass }}">{{ ucfirst($inc->severity) }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $inc->description }}">{{ $inc->description ?? '—' }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">{{ formatDateTime($inc->timestamp) }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('driver-behavior.destroy', $inc->id) }}" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <i data-lucide="shield-check" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>No incidents recorded.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pagination['total_pages'] > 1)
    <div class="px-4 py-3 border-t flex justify-between text-sm text-gray-600">
        <span>{{ $pagination['total_items'] }} total</span>
        <div class="flex gap-2">
            @if($pagination['has_prev'])<a href="?page={{ $pagination['prev_page'] }}&search={{ $search }}&severity={{ $severity }}" class="px-3 py-1 bg-gray-100 rounded">← Prev</a>@endif
            @if($pagination['has_next'])<a href="?page={{ $pagination['next_page'] }}&search={{ $search }}&severity={{ $severity }}" class="px-3 py-1 bg-gray-100 rounded">Next →</a>@endif
        </div>
    </div>
    @endif
</div>

{{-- Add Incident Modal --}}
<div id="addIncidentModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Log Driver Incident</h3>
            <button onclick="document.getElementById('addIncidentModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ route('driver-behavior.store') }}">
            @csrf
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Driver *</label>
                        <select name="driver_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                            <option value="">Select driver...</option>
                            @foreach($drivers as $d)
                            <option value="{{ $d->id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                        <select name="unit_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">Select unit...</option>
                            @foreach($units as $u)
                            <option value="{{ $u->id }}">{{ $u->unit_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Incident Type *</label>
                        <select name="incident_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                            <option value="speeding">Speeding</option>
                            <option value="hard_braking">Hard Braking</option>
                            <option value="rapid_acceleration">Rapid Acceleration</option>
                            <option value="cornering">Cornering</option>
                            <option value="idle">Excessive Idle</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Severity *</label>
                        <select name="severity" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Incident Date *</label>
                    <input type="date" name="incident_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm">Log Incident</button>
                <button type="button" onclick="document.getElementById('addIncidentModal').classList.add('hidden')" class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection