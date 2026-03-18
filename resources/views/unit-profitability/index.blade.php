@extends('layouts.app')

@section('title', 'Unit Profitability - Euro System')
@section('page-heading', 'Unit Profitability')
@section('page-subheading', 'ROI and net income analysis per unit')

@section('content')
    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('unit-profitability.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label class="text-sm text-gray-600 block mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ $date_from }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex-1">
                <label class="text-sm text-gray-600 block mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ $date_to }}"
                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:outline-none">
            </div>
            <div class="flex items-end">
                <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                    <i data-lucide="trending-up" class="w-4 h-4 inline mr-1"></i> Compute
                </button>
            </div>
        </form>
    </div>

    <!-- Totals -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5 card-hover">
            <p class="text-sm text-gray-500">Total Boundary</p>
            <p class="text-2xl font-bold text-green-600">{{ formatCurrency($totals['total_boundary']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 card-hover">
            <p class="text-sm text-gray-500">Total Maintenance</p>
            <p class="text-2xl font-bold text-red-600">{{ formatCurrency($totals['total_maintenance']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 card-hover">
            <p class="text-sm text-gray-500">Net Income</p>
            <p class="text-2xl font-bold {{ $totals['net_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ formatCurrency($totals['net_income']) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-5 card-hover">
            <p class="text-sm text-gray-500">ROI Achieved Units</p>
            <p class="text-2xl font-bold text-blue-600">{{ $totals['roi_units'] }}</p>
        </div>
    </div>

    <!-- Units Profitability Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Boundary Collected</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maintenance Cost</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Income</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ROI %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ROI Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $unit->unit_number }}</div>
                                <div class="text-xs text-gray-500">{{ $unit->plate_number }} / {{ $unit->make }}
                                    {{ $unit->model }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                {{ formatCurrency($unit->total_boundary) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                {{ formatCurrency($unit->total_maintenance) }}</td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $unit->net_income >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ formatCurrency($unit->net_income) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                                {{ number_format($unit->roi_percentage, 1) }}%</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($unit->roi_achieved)
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 flex items-center gap-1 w-fit">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Achieved
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 w-fit">In
                                        Progress</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="trending-up" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p>No profitability data for this period</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection