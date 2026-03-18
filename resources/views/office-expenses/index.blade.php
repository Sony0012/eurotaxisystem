@extends('layouts.app')

@section('title', 'Office Expenses - Euro System')
@section('page-heading', 'Office Expenses')
@section('page-subheading', 'Track and manage all office-related expenses')

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Month</p>
                        <p class="text-2xl">{{ formatCurrency($stats['this_month'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total expenses</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i data-lucide="calendar" class="h-8 w-8 text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Last Month</p>
                        <p class="text-2xl">{{ formatCurrency($stats['last_month'] ?? 0) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Previous period</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-full">
                        <i data-lucide="trending-down" class="h-8 w-8 text-gray-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Monthly Change</p>
                        <p class="text-2xl {{ ($stats['this_month'] ?? 0) > ($stats['last_month'] ?? 0) ? 'text-red-600' : 'text-green-600' }}">
                        @php
                            $change = ($stats['this_month'] ?? 0) - ($stats['last_month'] ?? 0);
                            $percentage = ($stats['last_month'] ?? 0) > 0 ? round(($change / $stats['last_month']) * 100, 1) : 0;
                        @endphp
                            {{ ($change >= 0 ? '+' : '') . $percentage . '%' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">vs last month</p>
                    </div>
                    <div class="p-3 bg-{{ ($stats['this_month'] ?? 0) > ($stats['last_month'] ?? 0) ? 'red' : 'green' }}-100 rounded-full">
                        <i data-lucide="{{ ($stats['this_month'] ?? 0) > ($stats['last_month'] ?? 0) ? 'trending-up' : 'trending-down' }}" class="h-8 w-8 text-{{ ($stats['this_month'] ?? 0) > ($stats['last_month'] ?? 0) ? 'red' : 'green' }}-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow card-hover">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Records</p>
                        <p class="text-2xl">{{ $totals->total_count ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">All expenses</p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <i data-lucide="file-text" class="h-8 w-8 text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Breakdown by Category</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($stats['by_category'] ?? [] as $category)
                <div class="p-4 border rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            {{ ucfirst($category->category) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $category->count ?? 0 }} items</span>
                    </div>
                    <div class="text-lg font-semibold">{{ formatCurrency($category->total ?? 0) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('office-expenses.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                        placeholder="Search description, category...">
                </div>
            </div>
            <div class="md:w-40">
                <select name="category"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:w-36">
                <input type="date" name="date_from" value="{{ $date_from }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="md:w-36">
                <input type="date" name="date_to" value="{{ $date_to }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center gap-2">
                    <i data-lucide="search" class="w-4 h-4"></i> Search
                </button>
                <button type="button" onclick="document.getElementById('addExpenseModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Expense
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recorded By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($expenses as $exp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatDate($exp->date) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ ucfirst($exp->category) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $exp->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exp->reference_number ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ formatCurrency($exp->amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exp->unit_number ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $exp->recorded_by_name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form method="POST" action="{{ route('office-expenses.destroy', $exp->id) }}"
                                    onsubmit="return confirm('Delete this expense?')">
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
                                <i data-lucide="receipt" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>No expenses recorded</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pagination['total_pages'] > 1)
            <div class="px-6 py-4 border-t flex items-center justify-between">
                <p class="text-sm text-gray-700">Page {{ $pagination['page'] }} of {{ $pagination['total_pages'] }}</p>
                <div class="flex gap-2">
                    @if($pagination['has_prev'])
                        <a href="?page={{ $pagination['prev_page'] }}&search={{ $search }}&category={{ $category }}&date_from={{ $date_from }}&date_to={{ $date_to }}"
                            class="px-3 py-1 border rounded text-sm hover:bg-gray-50">Previous</a>
                    @endif
                    @if($pagination['has_next'])
                        <a href="?page={{ $pagination['next_page'] }}&search={{ $search }}&category={{ $category }}&date_from={{ $date_from }}&date_to={{ $date_to }}"
                            class="px-3 py-1 border rounded text-sm hover:bg-gray-50">Next</a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Add Expense Modal -->
    <div id="addExpenseModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Add Expense</h3>
                <button onclick="document.getElementById('addExpenseModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('office-expenses.store') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="utilities">Utilities</option>
                            <option value="office_supplies">Office Supplies</option>
                            <option value="communication">Communication</option>
                            <option value="fuel">Fuel</option>
                            <option value="miscellaneous">Miscellaneous</option>
                            <option value="repairs">Repairs</option>
                            <option value="insurance">Insurance</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount (₱)</label>
                        <input type="number" name="amount" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reference Number</label>
                        <input type="text" name="reference_number"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                        <select name="unit_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
                            <option value="">Select Unit (Optional)</option>
                            @foreach($units ?? [] as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_number }} - {{ $unit->plate_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none"
                            required>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="submit"
                        class="flex-1 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Save</button>
                    <button type="button" onclick="document.getElementById('addExpenseModal').classList.add('hidden')"
                        class="flex-1 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection
