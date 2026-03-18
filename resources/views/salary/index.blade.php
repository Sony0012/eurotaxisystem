@extends('layouts.app')

@section('title', 'Salary Management - Euro System')
@section('page-heading', 'Salary Management')
@section('page-subheading', 'Manage employee salary records')

@section('content')
<!-- Month Filter -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <form method="GET" action="{{ route('salary.index') }}" class="flex flex-col md:flex-row gap-4">
        <div class="md:w-48">
            <input type="month" name="month" value="{{ $month }}"
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
        </div>
        <div class="flex-1">
            <input type="text" name="search" value="{{ $search }}" placeholder="Search employee name..."
                class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Search
            </button>
            <button type="button" onclick="document.getElementById('addSalaryModal').classList.remove('hidden')"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Add Record
            </button>
        </div>
    </form>
</div>

<!-- Summary -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5 card-hover">
        <p class="text-sm text-gray-500">Total Basic Salary</p>
        <p class="text-2xl font-bold text-gray-900">{{ formatCurrency($totals->total_gross ?? 0) }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 card-hover">
        <p class="text-sm text-gray-500">Total Records</p>
        <p class="text-2xl font-bold text-blue-600">{{ $records->count() }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5 card-hover">
        <p class="text-sm text-gray-500">Total Net Pay</p>
        <p class="text-2xl font-bold text-green-600">{{ formatCurrency($totals->total_net ?? 0) }}</p>
    </div>
</div>

<!-- Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Basic Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Allowances</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Pay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pay Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($records as $r)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $r->full_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                {{ ucwords(str_replace('_', ' ', $r->emp_type ?? $r->employee_type ?? '')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ date('F Y', mktime(0, 0, 0, $r->month, 1, $r->year)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ formatCurrency($r->basic_salary) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ formatCurrency(($r->overtime_pay ?? 0) + ($r->holiday_pay ?? 0) + ($r->night_differential ?? 0) + ($r->allowance ?? 0)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">{{ formatCurrency($r->total_salary) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ formatDate($r->pay_date) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <form method="POST" action="{{ route('salary.destroy', $r->id) }}" onsubmit="return confirm('Delete this record?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <i data-lucide="calculator" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                            <p>No salary records for this period</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Salary Modal -->
<div id="addSalaryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6 max-h-screen overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Add Salary Record</h3>
            <button onclick="document.getElementById('addSalaryModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('salary.store') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                    <select name="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                        <option value="">Select employee...</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Employee Type</label>
                    <select name="employee_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                        <option value="office_staff">Office Staff</option>
                        <option value="mechanic">Mechanic</option>
                        <option value="driver">Driver</option>
                        <option value="manager">Manager</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Basic Salary (₱)</label>
                        <input type="number" name="basic_salary" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Overtime Pay (₱)</label>
                        <input type="number" name="overtime_pay" step="0.01" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Holiday Pay (₱)</label>
                        <input type="number" name="holiday_pay" step="0.01" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Allowance (₱)</label>
                        <input type="number" name="allowance" step="0.01" value="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pay Date</label>
                    <input type="date" name="pay_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none" required>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save</button>
                <button type="button" onclick="document.getElementById('addSalaryModal').classList.add('hidden')"
                    class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection