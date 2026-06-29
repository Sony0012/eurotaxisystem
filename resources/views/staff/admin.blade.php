@extends('layouts.app')

@section('page-heading', 'Admin Staff Records')
@section('page-subheading', 'Personnel with web system accounts')

@section('content')
<div class="space-y-6">
    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border">
        <form action="{{ route('staff.admin') }}" method="GET" class="relative max-w-md">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Search admin staff by name or role..." 
                class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 outline-none text-sm">
        </form>
    </div>

    <!-- Admin Staff Table -->
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <div class="p-2 bg-blue-100 rounded-lg">
                <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Admin Staff</h2>
                <p class="text-sm text-gray-500">Personnel with web system accounts</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Name</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($adminStaff as $admin)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-xs uppercase">
                                        {{ substr($admin->full_name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ $admin->full_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $admin->role }}</td>
                            <td class="px-6 py-4 text-right">
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $admin->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $admin->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm italic">No admin staff found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
