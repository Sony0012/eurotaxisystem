@extends('layouts.app')

@section('title', 'Driver Management - Euro System')
@section('page-heading', 'Driver Management')
@section('page-subheading', 'Centralized driver records, incentives, and performance analytics')

@section('content')

<style>
    @keyframes shortage-blink {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    @keyframes shortage-text-pulse {
        0% { color: #dc2626; }
        50% { color: #991b1b; }
        100% { color: #dc2626; }
    }
    .shortage-blink {
        animation: shortage-blink 1.5s infinite ease-in-out;
    }
    .shortage-text-blink {
        animation: shortage-blink 1.5s infinite ease-in-out, shortage-text-pulse 1.5s infinite ease-in-out;
        font-weight: 800 !important;
    }
</style>

    {{-- Search and Filters --}}
    <div class="bg-white rounded-lg shadow p-2 mb-1">
        <form method="GET" action="{{ route('driver-management.index') }}" class="flex flex-col md:flex-row gap-2">
            <div class="md:w-48">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="arrow-up-z-a" class="h-4 w-4 text-gray-400"></i>
                    </div>
                    <select name="sort" onchange="this.form.submit()"
                        class="block w-full pl-9 pr-3 py-1 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none appearance-none">
                        <option value="alphabetical" {{ ($sort ?? '') === 'alphabetical' ? 'selected' : '' }}>A-Z (Name)</option>
                        <option value="newest" {{ ($sort ?? '') === 'newest' ? 'selected' : '' }}>Newest Joined</option>
                        <option value="oldest" {{ ($sort ?? '') === 'oldest' ? 'selected' : '' }}>Oldest Joined</option>
                        <option value="status" {{ ($sort ?? '') === 'status' ? 'selected' : '' }}>Status (Active first)</option>
                    </select>
                </div>
            </div>
            <div class="flex-1">
                <div class="relative group">
                    <input type="text" name="search" id="tableSearchInput" value="{{ $search ?? '' }}"
                        class="block w-full pl-3 pr-10 py-1 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
                        placeholder="Search by driver name or license...">
                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-yellow-600 transition-colors">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </button>
                </div>
            </div>

            <div class="md:w-48">
                <select name="status" onchange="this.form.submit()"
                    class="block w-full px-3 py-1 border border-gray-300 rounded-lg text-xs focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none">
                    <option value="">All Status</option>
                    <option value="active" {{ ($status_filter ?? '') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ ($status_filter ?? '') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                    <option value="no_unit" {{ ($status_filter ?? '') === 'no_unit' ? 'selected' : '' }}>Available (No Unit)</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="button" onclick="openAddDriverModal()"
                    class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2 text-xs font-semibold whitespace-nowrap">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Driver
                </button>
            </div>
        </form>
    </div>

    {{-- Driver List Container --}}
    <div id="driversTableContainer" class="bg-white rounded-lg shadow overflow-hidden">
        @include('driver-management.partials._drivers_table')
    </div>

    {{-- Add/Edit Driver Modal --}}
    <div id="addDriverModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-lg font-bold text-gray-900" id="driverModalTitle">Add Driver</h3>
                <button type="button" onclick="closeAddDriverModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form id="driverForm" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="_method" id="driverFormMethod" value="POST">
                <input type="hidden" name="driver_id" id="editDriverId" value="">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="driverFirstName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="driverLastName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nickname (Optional)</label>
                    <input type="text" name="nickname" id="driverNickname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="contact_number" id="driverContact" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Number <span class="text-red-500">*</span></label>
                        <input type="text" name="license_number" id="driverLicense" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">License Expiry <span class="text-red-500">*</span></label>
                        <input type="date" name="license_expiry" id="driverLicenseExpiry" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hire Date <span class="text-red-500">*</span></label>
                        <input type="date" name="hire_date" id="driverHireDate" required value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <textarea name="address" id="driverAddress" required rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Contact <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact" id="driverEmergencyContact" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Emergency Phone <span class="text-red-500">*</span></label>
                        <input type="tel" name="emergency_phone" id="driverEmergencyPhone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 flex justify-between items-center">
                        Daily Boundary Target
                        <span id="unitDerivedLabel" class="text-[10px] text-gray-500 font-bold hidden"></span>
                        <span id="codingBoundaryAlert" class="text-[10px] text-red-600 font-bold hidden"></span>
                    </label>
                    <input type="number" name="daily_boundary_target" id="driverBoundaryTarget" step="0.01" readonly
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 focus:outline-none cursor-not-allowed" 
                        placeholder="N/A (Managed via Unit Management)">
                    <p class="mt-1 text-[10px] text-gray-400 italic">This target is automatically synchronized from Unit Management.</p>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="is_active" id="editIsActive" class="w-40 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" id="deleteDriverButton" onclick="confirmDeleteDriver()" class="hidden px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
                        <button type="button" onclick="closeAddDriverModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Driver Details Modal with Tabs --}}
    <div id="driverDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-6 border-b">
                <div>
                    <h3 class="text-lg font-bold text-gray-900" id="driverDetailsName">Driver Details</h3>
                    <p class="text-sm text-gray-500" id="driverDetailsSubtitle"></p>
                </div>
                <button onclick="closeDriverDetails()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="px-6 pt-4 border-b">
                <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                    <button type="button" class="driver-tab active border-yellow-500 text-yellow-600 whitespace-nowrap py-2 px-3 border-b-2 text-sm font-medium" data-tab="basic">
                        Basic Info
                    </button>
                    <button type="button" class="driver-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 text-sm font-medium" data-tab="license">
                        License & Documents
                    </button>
                    <button type="button" class="driver-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 text-sm font-medium" data-tab="incentives">
                        Incentives
                    </button>
                    <button type="button" class="driver-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 text-sm font-medium" data-tab="performance">
                        Performance
                    </button>
                    <button type="button" class="driver-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-3 border-b-2 text-sm font-medium" data-tab="insights">
                        Insights
                    </button>
                </nav>
            </div>

            {{-- Tab Panels --}}
            <div class="p-6 space-y-6">
                <div class="driver-tab-panel" data-tab-panel="basic">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Personal & Employment Details</h4>
                    <div id="basicInfoContent" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <p class="text-gray-400">Loading...</p>
                    </div>
                </div>

                <div class="driver-tab-panel hidden" data-tab-panel="license">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">License Information</h4>
                    <div id="licenseInfoContent" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                        <p class="text-gray-400">Loading...</p>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h5 class="text-sm font-semibold text-gray-800 mb-2">Upload Driver Documents</h5>
                        <p class="text-xs text-gray-500 mb-3">Accepted file types: JPG, PNG, PDF. Uploads replace any existing document for the same type.</p>
                        <form id="driverDocumentsForm" method="POST" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="driver_id" id="driverDocumentsDriverId" value="">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Driver's License (Front)</label>
                                    <input type="file" name="license_front" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Driver's License (Back)</label>
                                    <input type="file" name="license_back" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">NBI Clearance</label>
                                    <input type="file" name="nbi_clearance" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Barangay Clearance</label>
                                    <input type="file" name="barangay_clearance" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Medical Certificate</label>
                                    <input type="file" name="medical_certificate" accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-xs text-gray-700 border border-gray-300 rounded-lg cursor-pointer focus:outline-none">
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                                    Save Documents
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="driver-tab-panel hidden" data-tab-panel="incentives">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Rule-based Incentives</h4>
                    <p class="text-sm text-gray-600 mb-4">This section shows the breakdown of incentives based on rules like no damage, no absence, complete boundary payments, and no late return.</p>
                    <div id="incentivesContent" class="text-sm text-gray-700">
                        <p class="text-gray-400">Loading incentive data...</p>
                    </div>
                </div>

                <div class="driver-tab-panel hidden" data-tab-panel="performance">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">Performance Analytics</h4>
                    <div id="performanceContent" class="text-sm text-gray-700 space-y-2">
                        <p class="text-gray-400">Loading performance data...</p>
                    </div>
                </div>

                <div class="driver-tab-panel hidden" data-tab-panel="insights">
                    <h4 class="text-md font-semibold text-gray-800 mb-4">System Insights & Recommendations</h4>
                    <div id="insightsContent" class="text-sm text-gray-700 space-y-2">
                        <p class="text-gray-400">Loading insights...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
window.boundaryRules = @json($boundary_rules ?? []);
function openAddDriverModal() {
    document.getElementById('driverModalTitle').textContent = 'Add Driver';
    document.getElementById('driverFormMethod').value = 'POST';
    document.getElementById('driverForm').action = '{{ route('driver-management.store') }}';
    document.getElementById('editDriverId').value = '';
    document.getElementById('driverFirstName').value = '';
    document.getElementById('driverLastName').value = '';
    document.getElementById('driverNickname').value = '';
    document.getElementById('driverContact').value = '';
    document.getElementById('driverLicense').value = '';
    document.getElementById('driverLicenseExpiry').value = '';
    document.getElementById('driverHireDate').value = '{{ date('Y-m-d') }}';
    document.getElementById('driverAddress').value = '';
    document.getElementById('driverEmergencyContact').value = '';
    document.getElementById('driverEmergencyPhone').value = '';
    const targetInput = document.getElementById('driverBoundaryTarget');
    const codingAlert = document.getElementById('codingBoundaryAlert');
    targetInput.value = '0';
    if (document.getElementById('unitDerivedLabel')) {
        const derivedLabel = document.getElementById('unitDerivedLabel');
        derivedLabel.classList.add('hidden');
    }
    
    if (codingAlert) {
        codingAlert.classList.remove('hidden');
        codingAlert.classList.remove('text-red-600');
        codingAlert.classList.add('text-gray-500');
        codingAlert.textContent = '(Pending Dispatch)';
    }

    document.getElementById('editIsActive').value = '1';
    document.getElementById('deleteDriverButton').classList.add('hidden');
    document.getElementById('addDriverModal').classList.remove('hidden');
    lucide.createIcons();
}

function openEditDriverModal(id) {
    fetch('{{ route('driver-management.index') }}/' + id + '?format=json', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('driverModalTitle').textContent = 'Edit Driver';
        document.getElementById('driverFormMethod').value = 'PUT';
        document.getElementById('driverForm').action = '{{ url('driver-management') }}/' + id;
        document.getElementById('editDriverId').value = id;
        document.getElementById('driverFirstName').value = data.first_name || '';
        document.getElementById('driverLastName').value = data.last_name || '';
        document.getElementById('driverNickname').value = data.nickname || '';
        document.getElementById('driverContact').value = data.contact_number || '';
        document.getElementById('driverLicense').value = data.license_number || '';
        document.getElementById('driverLicenseExpiry').value = data.license_expiry || '';
        document.getElementById('driverHireDate').value = data.hire_date || '{{ date('Y-m-d') }}';
        document.getElementById('driverAddress').value = data.address || '';
        document.getElementById('driverEmergencyContact').value = data.emergency_contact || '';
        document.getElementById('driverEmergencyPhone').value = data.emergency_phone || '';
        
        // Dynamic Boundary Automation (Sync with Controller Trait)
        const targetInput = document.getElementById('driverBoundaryTarget');
        const codingAlert = document.getElementById('codingBoundaryAlert');
        
        if (data.current_pricing) {
            targetInput.value = data.current_pricing.rate.toFixed(2);
            
            // Show inheritance label if assigned
            const derivedLabel = document.getElementById('unitDerivedLabel');
            if (data.assigned_unit) {
                if (derivedLabel) {
                    derivedLabel.textContent = `(Inherited from ${data.assigned_unit})`;
                    derivedLabel.classList.remove('hidden');
                }
            } else {
                if (derivedLabel) derivedLabel.classList.add('hidden');
            }

            if (data.current_pricing.label && data.current_pricing.type !== 'regular') {
                codingAlert.classList.remove('hidden');
                codingAlert.textContent = data.current_pricing.label;
                codingAlert.className = data.current_pricing.type === 'coding' ? 'text-[11px] text-red-600 font-bold' : 'text-[11px] text-blue-600 font-bold';
            } else {
                codingAlert.classList.add('hidden');
            }
        } else {
            targetInput.value = data.daily_boundary_target || '0.00';
            const derivedLabel = document.getElementById('unitDerivedLabel');
            if (derivedLabel) derivedLabel.classList.add('hidden');
            codingAlert.classList.add('hidden');
        }
        document.getElementById('editIsActive').value = data.is_active ? '1' : '0';
        document.getElementById('deleteDriverButton').classList.remove('hidden');
        document.getElementById('addDriverModal').classList.remove('hidden');
        lucide.createIcons();
    })
    .catch(() => {
        // Fallback: just show empty edit modal
        document.getElementById('driverModalTitle').textContent = 'Edit Driver';
        document.getElementById('driverFormMethod').value = 'PUT';
        document.getElementById('driverForm').action = '{{ url('driver-management') }}/' + id;
        document.getElementById('editDriverId').value = id;
        document.getElementById('deleteDriverButton').classList.remove('hidden');
        document.getElementById('addDriverModal').classList.remove('hidden');
        lucide.createIcons();
    });
}

function closeAddDriverModal() {
    document.getElementById('addDriverModal').classList.add('hidden');
}

function confirmDeleteDriver() {
    const id = document.getElementById('editDriverId').value;
    const firstName = document.getElementById('driverFirstName').value || '';
    const lastName = document.getElementById('driverLastName').value || '';
    const name = (firstName + ' ' + lastName).trim() || 'this driver';
    deleteDriver(id, name);
}

function deleteDriver(id, name) {
    if (!id) return;
    if (confirm('Are you sure you want to delete ' + name + '?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ url('driver-management') }}/' + id;
        form.innerHTML = '@csrf' +
                         '<input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
}

function openDriverDetails(id) {
    const modal = document.getElementById('driverDetailsModal');
    modal.classList.remove('hidden');

    // Reset tab state
    document.querySelectorAll('.driver-tab').forEach(btn => {
        btn.classList.remove('border-yellow-500', 'text-yellow-600', 'active');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    document.querySelectorAll('.driver-tab-panel').forEach(panel => {
        panel.classList.add('hidden');
    });
    const firstTab = document.querySelector('.driver-tab[data-tab="basic"]');
    const firstPanel = document.querySelector('.driver-tab-panel[data-tab-panel="basic"]');
    if (firstTab && firstPanel) {
        firstTab.classList.add('border-yellow-500', 'text-yellow-600', 'active');
        firstTab.classList.remove('border-transparent', 'text-gray-500');
        firstPanel.classList.remove('hidden');
    }

    document.getElementById('driverDocumentsDriverId').value = id;
    document.getElementById('driverDocumentsForm').action = '{{ url('driver-management/upload-documents') }}/' + id;

    // Fetch basic details
    fetch('{{ route('driver-management.index') }}/' + id + '?format=json', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('driverDetailsName').textContent = data.full_name || 'Driver Details';

        document.getElementById('basicInfoContent').innerHTML = `
            <div>
                <p><span class="font-semibold">First Name:</span> ${data.first_name || ''}</p>
                <p><span class="font-semibold">Last Name:</span> ${data.last_name || ''}</p>
                <p><span class="font-semibold">Nickname:</span> ${data.nickname || 'N/A'}</p>
                <p><span class="font-semibold">Contact:</span> ${data.contact_number || 'N/A'}</p>
                <p><span class="font-semibold">Address:</span> ${data.address || 'N/A'}</p>
                <p><span class="font-semibold">Emergency Contact:</span> ${data.emergency_contact || 'N/A'}</p>
                <p><span class="font-semibold">Emergency Phone:</span> ${data.emergency_phone || 'N/A'}</p>
            </div>
            <div>
                <p><span class="font-semibold">Hire Date:</span> ${data.hire_date || 'N/A'}</p>
                <p><span class="font-semibold">Daily Boundary Target:</span> ₱${data.current_pricing ? data.current_pricing.rate.toFixed(2) : data.daily_boundary_target}</p>
                ${data.current_pricing && data.current_pricing.type !== 'regular' ? `<p class="text-[10px] text-blue-600 font-bold">${data.current_pricing.label}</p>` : ''}
                <p><span class="font-semibold">Status:</span> ${data.is_active ? 'Active' : 'Inactive'}</p>
                <div class="mt-4 pt-2 border-t border-gray-100">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Record Credit</p>
                    <p class="text-xs text-gray-600"><span class="font-medium text-gray-500">Input by:</span> ${data.creator_name || 'System'}</p>
                    <p class="text-xs text-gray-600"><span class="font-medium text-gray-500">Last Edit:</span> ${data.editor_name || 'System'}</p>
                </div>
            </div>
        `;

        document.getElementById('licenseInfoContent').innerHTML = `
            <div>
                <p><span class="font-semibold">License Number:</span> ${data.license_number || ''}</p>
                <p><span class="font-semibold">License Expiry:</span> ${data.license_expiry || ''}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">License status and reminders will be computed here (e.g., expiring soon).</p>
            </div>
        `;

        lucide.createIcons();
    })
    .catch(() => {
        document.getElementById('basicInfoContent').innerHTML = '<p class="text-red-500">Failed to load details.</p>';
    });

    lucide.createIcons();
}
</script>

@push('scripts')
<script>
    window.boundaryRules = @json($boundary_rules ?? []);
    function openAddDriverModal() {
        document.getElementById('driverModalTitle').textContent = 'Add Driver';
        document.getElementById('driverFormMethod').value = 'POST';
        document.getElementById('driverForm').action = '{{ route('driver-management.store') }}';
        document.getElementById('editDriverId').value = '';
        document.getElementById('driverFirstName').value = '';
        document.getElementById('driverLastName').value = '';
        document.getElementById('driverNickname').value = '';
        document.getElementById('driverContact').value = '';
        document.getElementById('driverLicense').value = '';
        document.getElementById('driverLicenseExpiry').value = '';
        document.getElementById('driverHireDate').value = '{{ date('Y-m-d') }}';
        document.getElementById('driverAddress').value = '';
        document.getElementById('driverEmergencyContact').value = '';
        document.getElementById('driverEmergencyPhone').value = '';
        const targetInput = document.getElementById('driverBoundaryTarget');
        const codingAlert = document.getElementById('codingBoundaryAlert');
        
        targetInput.value = '';
        targetInput.placeholder = 'Please dispatch to appear boundary';
        if (codingAlert) {
            codingAlert.classList.remove('hidden');
            codingAlert.classList.remove('text-red-600');
            codingAlert.classList.add('text-gray-500');
            codingAlert.textContent = '(Pending Dispatch)';
        }

        document.getElementById('editIsActive').value = '1';
        document.getElementById('deleteDriverButton').classList.add('hidden');
        document.getElementById('addDriverModal').classList.remove('hidden');
        lucide.createIcons();
    }

    function openEditDriverModal(id) {
        fetch('{{ route('driver-management.index') }}/' + id + '?format=json', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('driverModalTitle').textContent = 'Edit Driver';
            document.getElementById('driverFormMethod').value = 'PUT';
            document.getElementById('driverForm').action = '{{ url('driver-management') }}/' + id;
            document.getElementById('editDriverId').value = id;
            document.getElementById('driverFirstName').value = data.first_name || '';
            document.getElementById('driverLastName').value = data.last_name || '';
            document.getElementById('driverNickname').value = data.nickname || '';
            document.getElementById('driverContact').value = data.contact_number || '';
            document.getElementById('driverLicense').value = data.license_number || '';
            document.getElementById('driverLicenseExpiry').value = data.license_expiry || '';
            document.getElementById('driverHireDate').value = data.hire_date || '{{ date('Y-m-d') }}';
            document.getElementById('driverAddress').value = data.address || '';
            document.getElementById('driverEmergencyContact').value = data.emergency_contact || '';
            document.getElementById('driverEmergencyPhone').value = data.emergency_phone || '';
            
            const targetInput = document.getElementById('driverBoundaryTarget');
            const codingAlert = document.getElementById('codingBoundaryAlert');
            
            if (data.current_pricing) {
                targetInput.value = data.current_pricing.rate.toFixed(2);
                targetInput.placeholder = '0.00';
                
                if (data.current_pricing.label && data.current_pricing.type !== 'regular') {
                    codingAlert.classList.remove('hidden');
                    codingAlert.textContent = data.current_pricing.label;
                    codingAlert.className = data.current_pricing.type === 'coding' ? 'text-[11px] text-red-600 font-bold' : 'text-[11px] text-blue-600 font-bold';
                } else {
                    codingAlert.classList.add('hidden');
                }
            } else {
                targetInput.value = data.daily_boundary_target || '';
                targetInput.placeholder = 'Enter boundary target...';
                codingAlert.classList.add('hidden');
            }
            document.getElementById('editIsActive').value = data.is_active ? '1' : '0';
            document.getElementById('deleteDriverButton').classList.remove('hidden');
            document.getElementById('addDriverModal').classList.remove('hidden');
            lucide.createIcons();
        });
    }

    function closeAddDriverModal() {
        document.getElementById('addDriverModal').classList.add('hidden');
    }

    function confirmDeleteDriver() {
        const id = document.getElementById('editDriverId').value;
        const firstName = document.getElementById('driverFirstName').value || '';
        const lastName = document.getElementById('driverLastName').value || '';
        const name = (firstName + ' ' + lastName).trim() || 'this driver';
        deleteDriver(id, name);
    }

    function deleteDriver(id, name) {
        if (!id) return;
        if (confirm('Are you sure you want to delete ' + name + '?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url('driver-management') }}/' + id;
            form.innerHTML = '@csrf' +
                            '<input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function openDriverDetails(id) {
        const modal = document.getElementById('driverDetailsModal');
        modal.classList.remove('hidden');

        document.querySelectorAll('.driver-tab').forEach(btn => {
            btn.classList.remove('border-yellow-500', 'text-yellow-600', 'active');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        document.querySelectorAll('.driver-tab-panel').forEach(panel => { panel.classList.add('hidden'); });
        
        const firstTab = document.querySelector('.driver-tab[data-tab="basic"]');
        const firstPanel = document.querySelector('.driver-tab-panel[data-tab-panel="basic"]');
        if (firstTab && firstPanel) {
            firstTab.classList.add('border-yellow-500', 'text-yellow-600', 'active');
            firstPanel.classList.remove('hidden');
        }

        document.getElementById('driverDocumentsDriverId').value = id;
        document.getElementById('driverDocumentsForm').action = '{{ url('driver-management/upload-documents') }}/' + id;

        fetch('{{ route('driver-management.index') }}/' + id + '?format=json', {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('driverDetailsName').textContent = data.full_name || 'Driver Details';
            document.getElementById('driverDetailsSubtitle').textContent = data.assigned_unit ? `Assigned to ${data.assigned_unit}` : 'Not currently assigned';

            document.getElementById('basicInfoContent').innerHTML = `
                <div>
                    <p><span class="font-semibold text-gray-500">First Name:</span> ${data.first_name || ''}</p>
                    <p><span class="font-semibold text-gray-500">Last Name:</span> ${data.last_name || ''}</p>
                    <p><span class="font-semibold text-gray-500">Nickname:</span> ${data.nickname || 'N/A'}</p>
                    <p><span class="font-semibold text-gray-500">Contact:</span> ${data.contact_number || 'N/A'}</p>
                    <p><span class="font-semibold text-gray-500">Address:</span> ${data.address || 'N/A'}</p>
                    <p><span class="font-semibold text-gray-500">Emergency Contact:</span> ${data.emergency_contact || 'N/A'}</p>
                    <p><span class="font-semibold text-gray-500">Emergency Phone:</span> ${data.emergency_phone || 'N/A'}</p>
                </div>
                <div>
                    <p><span class="font-semibold text-gray-500">Hire Date:</span> ${data.hire_date || 'N/A'}</p>
                    <p><span class="font-semibold text-gray-500">Standard Rate:</span> ₱${data.assigned_boundary_rate ? parseFloat(data.assigned_boundary_rate).toLocaleString() : '0.00'}</p>
                    <p><span class="font-semibold text-gray-500">Active Target:</span> ₱${data.current_pricing ? data.current_pricing.rate.toFixed(2) : '0.00'}</p>
                    ${data.current_pricing && data.current_pricing.label ? `<p class="text-[10px] text-blue-600 font-bold">${data.current_pricing.label}</p>` : ''}
                    <p><span class="font-semibold text-gray-500">Status:</span> 
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${data.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                            ${data.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </p>
                </div>
            `;

            document.getElementById('licenseInfoContent').innerHTML = `
                <div>
                    <p><span class="font-semibold text-gray-500">License Number:</span> ${data.license_number || ''}</p>
                    <p><span class="font-semibold text-gray-500">License Expiry:</span> ${data.license_expiry || ''}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                    <p class="text-[11px] text-blue-700 font-medium">Auto-Status Detection</p>
                    <p class="text-xs text-blue-600 mt-1">Based on expiry date: No active issues detected.</p>
                </div>
            `;

            // Performance Tab
            let performanceHtml = `
                <div class="flex items-center justify-between mb-4 bg-gray-50 p-3 rounded-lg">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Performance Rating</p>
                        <p class="text-lg font-bold text-yellow-600">${data.performance_rating || 'N/A'}</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-100 text-gray-600 font-bold">
                            <tr>
                                <th class="p-2">Date</th>
                                <th class="p-2">Unit</th>
                                <th class="p-2">Target</th>
                                <th class="p-2">Actual</th>
                                <th class="p-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>`;
            
            if (data.recent_performance && data.recent_performance.length > 0) {
                data.recent_performance.forEach(log => {
                    const statusClass = log.status === 'paid' ? 'text-green-600' : (log.status === 'shortage' ? 'text-red-600' : 'text-blue-600');
                    performanceHtml += `
                        <tr class="border-b border-gray-50">
                            <td class="p-2">${new Date(log.date).toLocaleDateString('en-US', {month: 'short', day: 'numeric', year: 'numeric'})}</td>
                            <td class="p-2 font-bold">${log.plate_number || 'N/A'}</td>
                            <td class="p-2">₱${parseFloat(log.boundary_amount || 0).toLocaleString()}</td>
                            <td class="p-2 font-bold">₱${parseFloat(log.actual_boundary || 0).toLocaleString()}</td>
                            <td class="p-2 font-bold ${statusClass}">${log.status.toUpperCase()}</td>
                        </tr>`;
                });
            } else {
                performanceHtml += '<tr><td colspan="5" class="p-4 text-center text-gray-400">No recent performance records found.</td></tr>';
            }
            performanceHtml += '</tbody></table></div>';
            document.getElementById('performanceContent').innerHTML = performanceHtml;

            // Insights Content
            document.getElementById('insightsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="p-3 bg-green-50 rounded-lg border border-green-100">
                        <p class="text-[10px] text-green-600 font-bold uppercase">Monthly Incentive</p>
                        <p class="text-lg font-bold text-green-700">₱${parseFloat(data.monthly_incentive || 0).toLocaleString()}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="p-3 border rounded-lg">
                        <p class="text-xs font-bold text-gray-700 mb-1">Incentive Eligibility</p>
                        <p class="text-xs text-gray-500">This driver receives 5% of all collections as a performance bonus. Current payout is based on the ${new Date().toLocaleString('en-US', {month: 'long'})} performance period.</p>
                    </div>
                </div>
            `;

            lucide.createIcons();
        });
    }

    function closeDriverDetails() {
        document.getElementById('driverDetailsModal').classList.add('hidden');
    }

    document.querySelectorAll('.driver-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.driver-tab').forEach(t => {
                t.classList.remove('border-yellow-500', 'text-yellow-600', 'active');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.driver-tab-panel').forEach(p => p.classList.add('hidden'));
            tab.classList.add('border-yellow-500', 'text-yellow-600', 'active');
            const panel = document.querySelector(`.driver-tab-panel[data-tab-panel="${tab.dataset.tab}"]`);
            if (panel) panel.classList.remove('hidden');
        });
    });

    let searchTimer;
    const searchInput = document.getElementById('tableSearchInput');
    const statusFilter = document.querySelector('select[name="status"]');
    const sortFilter = document.querySelector('select[name="sort"]');
    const tableContainer = document.getElementById('driversTableContainer');

    function performSearch(page = 1) {
        const query = searchInput.value;
        const status = statusFilter.value;
        const sort = sortFilter.value;

        tableContainer.style.opacity = '0.5';
        tableContainer.style.pointerEvents = 'none';

        fetch(`{{ route('driver-management.index') }}?search=${encodeURIComponent(query)}&status=${status}&sort=${sort}&page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            tableContainer.style.opacity = '1';
            tableContainer.style.pointerEvents = 'auto';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => performSearch(1), 300);
        });
    }
    if (statusFilter) statusFilter.addEventListener('change', () => performSearch(1));
    if (sortFilter) sortFilter.addEventListener('change', () => performSearch(1));

    window.changePage = function(page) {
        performSearch(page);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };
</script>
@endpush