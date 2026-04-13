@php
/** @var \Illuminate\Support\Collection $units */
/** @var array $coding_calendar */
/** @var string $date */
/** @var string $search */
/** @var string $today_name */
@endphp
@extends('layouts.app')

@section('title', 'Coding Management - Euro System')
@section('page-heading', 'Coding Schedule Management')
@section('page-subheading', "Today: {{ $today_name }} — Managing number coding restrictions")

@section('content')
    <!-- Date Filter & Actions -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('coding.index') }}" class="flex flex-col md:flex-row gap-4 items-center">
            <div class="w-full md:w-48">
                <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                    class="block w-full px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:outline-none text-sm font-bold text-gray-700">
            </div>
            <div class="flex-1 w-full">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="h-4 w-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search plate..."
                        class="block w-full pl-10 pr-3 py-2 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:outline-none text-sm font-bold text-gray-700">
                </div>
            </div>
            <a href="{{ route('coding.violations') }}" class="w-full md:w-auto px-6 py-2 bg-red-600 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-sm hover:bg-red-700 transition-all flex items-center justify-center gap-2">
                <i data-lucide="history" class="w-4 h-4"></i>
                Violation History
            </a>
        </form>
    </div>

    <!-- Today's Coding Units -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b flex items-center gap-2">
            <i data-lucide="calendar" class="w-5 h-5 text-yellow-600"></i>
            <h3 class="text-lg font-semibold text-gray-900">Coding Today ({{ $today_name }})</h3>
            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">{{ $units->count() }} units</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plate Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Make / Model</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver 1</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Driver 2</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">{{ $unit->plate_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $unit->make }} {{ $unit->model }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $unit->driver1_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $unit->driver2_name ?? '—' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Coding</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-4 text-green-300"></i>
                                <p>No units on coding today</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Weekly Coding Calendar -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">Weekly Coding Calendar</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($coding_calendar as $day => $day_units)
                <div class="border rounded-lg p-4 {{ $day === $today_name ? 'border-yellow-500 bg-yellow-50' : '' }}">
                    <div class="flex items-center gap-2 mb-3">
                        <h4 class="font-semibold text-gray-800">{{ $day }}</h4>
                        <span
                            class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $day_units->count() }}</span>
                        @if($day === $today_name)
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs rounded-full">TODAY</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        @forelse($day_units as $u)
                            <div class="text-xs p-2 bg-white rounded border text-gray-700">
                                <div class="font-medium text-blue-600">{{ $u->plate_number }}</div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">No units</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection