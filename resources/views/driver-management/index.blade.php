@extends('layouts.app')

@section('title', 'Driver Management - Euro System')
@section('page-heading', 'Driver Management')
@section('page-subheading', 'Manage all registered drivers')

@section('content')
{{-- Expiring Licenses Alert --}}
@if($expiring_licenses->count())
<div class="mb-4 p-4 bg-orange-50 border border-orange-300 rounded-lg">
    <p class="text-sm font-semibold text-orange-800 mb-2"><i data-lucide="alert-triangle" class="w-4 h-4 inline mr-1"></i>{{ $expiring_licenses->count() }} driver license(s) expiring within 30 days:</p>
    <ul class="text-sm text-orange-700 list-disc list-inside">
        @foreach($expiring_licenses as $el)
        <li>{{ $el->full_name }} — {{ $el->license_number }} (expires {{ formatDate($el->license_expiry) }})</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total Drivers</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-green-600">{{ $stats['available'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Available</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-blue-600">{{ $stats['assigned'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Assigned</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ $stats['on_leave'] }}</p>
        <p class="text-xs text-gray-500 mt-1">On Leave</p>
    </div>
</div>

{{-- Search + Add --}}
<div class="bg-white rounded-lg shadow p-4 mb-5">
    <form method="GET" action="{{ route('driver-management.index') }}" class="flex gap-3">
        <input type="text" name="search" value="{{ $search }}" placeholder="Search name, license, email..."
            class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:outline-none">
        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm flex items-center gap-2">
            <i data-lucide="search" class="w-4 h-4"></i> Search
        </button>
        <button type="button" onclick="document.getElementById('addDriverModal').classList.remove('hidden')"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Add Driver
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">License</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assigned Unit</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Daily Target</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($drivers as $d)
                @php
                    $expiringSoon = $d->license_expiry && $d->license_expiry <= date('Y-m-d', strtotime('+30 days'));
                    $statusClass = match($d->driver_status ?? 'available') {
                        'available' => 'bg-green-100 text-green-800',
                        'assigned'  => 'bg-blue-100 text-blue-800',
                        'on_leave'  => 'bg-yellow-100 text-yellow-800',
                        'suspended' => 'bg-red-100 text-red-800',
                        default     => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-gray-900">{{ $d->full_name }}</p>
                        <p class="text-xs text-gray-500">{{ $d->email }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-gray-700">{{ $d->license_number }}</p>
                        <p class="text-xs {{ $expiringSoon ? 'text-red-600 font-semibold' : 'text-gray-400' }}">
                            Exp: {{ formatDate($d->license_expiry) }}
                            @if($expiringSoon) ⚠️ @endif
                        </p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $d->contact_number ?? $d->phone ?? '—' }}</td>
                    <td class="px-4 py-3">
                        @if($d->unit_number)
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ $d->unit_number }}</span>
                        @else
                        <span class="text-gray-400 text-xs">Unassigned</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ ucfirst($d->driver_type ?? '—') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                            {{ ucwords(str_replace('_', ' ', $d->driver_status ?? 'available')) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700">{{ formatCurrency($d->daily_boundary_target) }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <button onclick='openEditDriver(@json($d))' class="text-blue-600 hover:text-blue-900">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </button>
                            <form method="POST" action="{{ route('driver-management.destroy', $d->id) }}" onsubmit="return confirm('Delete this driver? This will also remove their user account.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-300"></i>
                        <p>No drivers found.</p>
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
            @if($pagination['has_prev'])<a href="?page={{ $pagination['prev_page'] }}&search={{ $search }}" class="px-3 py-1 bg-gray-100 rounded">← Prev</a>@endif
            @if($pagination['has_next'])<a href="?page={{ $pagination['next_page'] }}&search={{ $search }}" class="px-3 py-1 bg-gray-100 rounded">Next →</a>@endif
        </div>
    </div>
    @endif
</div>

{{-- Add Driver Modal --}}
<div id="addDriverModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Add New Driver</h3>
            <button onclick="document.getElementById('addDriverModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form method="POST" action="{{ route('driver-management.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="full_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Username *</label>
                    <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Password *</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">License No. *</label>
                    <input type="text" name="license_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">License Expiry *</label>
                    <input type="date" name="license_expiry" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Hire Date</label>
                    <input type="date" name="hire_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Driver Type</label>
                    <select name="driver_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="regular">Regular</option>
                        <option value="senior">Senior</option>
                        <option value="trainee">Trainee</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Daily Boundary Target (₱)</label>
                    <input type="number" name="daily_boundary_target" value="1100" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Emergency Contact</label>
                    <input type="text" name="emergency_contact" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Emergency Phone</label>
                    <input type="text" name="emergency_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">Save Driver</button>
                <button type="button" onclick="document.getElementById('addDriverModal').classList.add('hidden')" class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Driver Modal --}}
<div id="editDriverModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-xl p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold">Edit Driver</h3>
            <button onclick="document.getElementById('editDriverModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i data-lucide="x" class="w-5 h-5"></i></button>
        </div>
        <form id="editDriverForm" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div class="col-span-2">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="full_name" id="ed_full_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">License No. *</label>
                    <input type="text" name="license_number" id="ed_license" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">License Expiry *</label>
                    <input type="date" name="license_expiry" id="ed_expiry" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" id="ed_contact" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Daily Target (₱)</label>
                    <input type="number" name="daily_boundary_target" id="ed_target" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Driver Type</label>
                    <select name="driver_type" id="ed_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="regular">Regular</option>
                        <option value="senior">Senior</option>
                        <option value="trainee">Trainee</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="driver_status" id="ed_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="available">Available</option>
                        <option value="assigned">Assigned</option>
                        <option value="on_leave">On Leave</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Emergency Contact</label>
                    <input type="text" name="emergency_contact" id="ed_emergency_contact" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Emergency Phone</label>
                    <input type="text" name="emergency_phone" id="ed_emergency_phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="flex-1 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Update</button>
                <button type="button" onclick="document.getElementById('editDriverModal').classList.add('hidden')" class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditDriver(d) {
    const base = "{{ url('driver-management') }}";
    document.getElementById('editDriverForm').action = base + '/' + d.id;
    document.getElementById('ed_full_name').value        = d.full_name;
    document.getElementById('ed_license').value          = d.license_number;
    document.getElementById('ed_expiry').value           = d.license_expiry;
    document.getElementById('ed_contact').value          = d.contact_number || '';
    document.getElementById('ed_target').value           = d.daily_boundary_target;
    document.getElementById('ed_type').value             = d.driver_type || 'regular';
    document.getElementById('ed_status').value           = d.driver_status || 'available';
    document.getElementById('ed_emergency_contact').value = d.emergency_contact || '';
    document.getElementById('ed_emergency_phone').value  = d.emergency_phone || '';
    document.getElementById('editDriverModal').classList.remove('hidden');
}
</script>
@endpush
@endsection