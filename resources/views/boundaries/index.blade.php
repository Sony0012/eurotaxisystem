@php
/** @var \Illuminate\Support\Collection $boundaries */
/** @var array $pagination */
/** @var string $search */
/** @var string $date_from */
/** @var string $date_to */
/** @var object $totals */
/** @var \Illuminate\Support\Collection $units */
/** @var \Illuminate\Support\Collection $drivers */
@endphp
@extends('layouts.app')

@section('title', 'Boundaries - Euro System')
@section('page-heading', 'Boundary Collection')
@section('page-subheading', 'Track daily boundary payments from drivers')

@section('content')
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('boundaries.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ $search }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 focus:outline-none"
                    placeholder="Search unit, plate, driver...">
            </div>
            <div class="md:w-40">
                <input type="date" name="date_from" value="{{ $date_from }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="md:w-40">
                <input type="date" name="date_to" value="{{ $date_to }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i> Search
                </button>
                <button type="button" onclick="document.getElementById('addBoundaryModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Record Boundary
                </button>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Total Collected</p>
            <p class="text-2xl font-bold text-green-600">{{ formatCurrency($totals->total_amount ?? 0) }}</p>
            <p class="text-xs text-gray-400">{{ $totals->total_records ?? 0 }} records</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Paid Boundaries</p>
            <p class="text-2xl font-bold text-blue-600">{{ formatCurrency($totals->paid_total ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5">
            <p class="text-sm text-gray-500">Period</p>
            <p class="text-lg font-bold text-gray-900">{{ formatDate($date_from) }} – {{ formatDate($date_to) }}</p>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($boundaries as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatDate($b->date) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $b->unit_number ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $b->plate_number ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $b->driver_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ formatCurrency($b->boundary_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                        @if($b->status === 'paid') bg-green-100 text-green-800
                                        @elseif($b->status === 'late') bg-yellow-100 text-yellow-800
                                        @elseif($b->status === 'short') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <form method="POST" action="{{ route('boundaries.destroy', $b->id) }}"
                                    onsubmit="return confirm('Delete this record?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="dollar-sign" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>No boundary records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pagination['total_pages'] > 1)
            <div class="px-6 py-4 border-t flex items-center justify-between">
                <p class="text-sm text-gray-700">
                    Showing {{ $pagination['total_items'] }} records / Page {{ $pagination['page'] }} of
                    {{ $pagination['total_pages'] }}
                </p>
                <div class="flex gap-2">
                    @if($pagination['has_prev'])
                        <a href="?page={{ $pagination['prev_page'] }}&search={{ $search }}&date_from={{ $date_from }}&date_to={{ $date_to }}"
                            class="px-3 py-1 border rounded text-sm hover:bg-gray-50">Previous</a>
                    @endif
                    @if($pagination['has_next'])
                        <a href="?page={{ $pagination['next_page'] }}&search={{ $search }}&date_from={{ $date_from }}&date_to={{ $date_to }}"
                            class="px-3 py-1 border rounded text-sm hover:bg-gray-50">Next</a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Add Boundary Modal -->
    <div id="addBoundaryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Record Boundary</h3>
                <button onclick="document.getElementById('addBoundaryModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('boundaries.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <select name="unit_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                            <option value="">Select unit...</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_number }} - {{ $unit->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                        <input type="number" name="boundary_amount" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="paid">Paid</option>
                            <option value="late">Late</option>
                            <option value="short">Short</option>
                            <option value="excess">Excess</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save</button>
                    <button type="button" onclick="document.getElementById('addBoundaryModal').classList.add('hidden')"
                        class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection