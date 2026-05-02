@extends('layouts.app')

@section('page-heading', 'My Account')
@section('page-subheading', 'Manage your profile and account settings')

@section('content')
<div class="max-w-7xl mx-auto px-3 py-4">
    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        
        <!-- Left Column: Profile Info + Account Stats -->
        <div class="lg:col-span-2 space-y-3">
            
            <!-- Profile Header with Account Stats -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <!-- Profile Info -->
                    <div class="flex items-center gap-5">
                        <div class="flex flex-col items-center gap-1">
                            <div class="relative group cursor-pointer" onclick="openProfileModal()">
                                @if($user->profile_image)
                                    @php
                                        $imagePath = str_replace('resources/', '', $user->profile_image);
                                        $isIcon = str_contains($imagePath, 'image/') && !str_contains($imagePath, 'storage/');
                                    @endphp
                                    @if($isIcon)
                                        <img src="{{ asset($imagePath) }}" alt="Profile" class="w-20 h-20 rounded-full object-cover shadow-sm border-2 border-yellow-50 group-hover:opacity-75 transition-opacity">
                                    @else
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="w-20 h-20 rounded-full object-cover shadow-sm border-2 border-yellow-50 group-hover:opacity-75 transition-opacity">
                                    @endif
                                @else
                                    <div class="w-20 h-20 bg-yellow-600 rounded-full flex items-center justify-center text-white text-2xl font-semibold flex-shrink-0 shadow-sm border-2 border-yellow-50 group-hover:bg-yellow-700 transition-colors">
                                        {{ strtoupper(substr($user->full_name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="camera" class="w-6 h-6 text-white drop-shadow-md"></i>
                                </div>
                            </div>
                            <span class="text-[10px] text-gray-500 font-bold uppercase tracking-wider cursor-pointer hover:text-yellow-600 transition-colors leading-none" onclick="openProfileModal()">Change Profile</span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 leading-tight">{{ $user->full_name ?? 'User' }}</h1>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-sm font-semibold text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full">{{ ucfirst($user->role) }}</span>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                    Joined {{ $user->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Statistics -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100/50 shadow-sm">
                            <div class="flex items-center justify-center gap-1 text-yellow-600 mb-2">
                                <i data-lucide="history" class="w-5 h-5"></i>
                            </div>
                            <p class="text-[11px] uppercase font-bold text-gray-400 tracking-widest">Last Login</p>
                            <p class="text-sm font-bold text-gray-900 mt-0.5">{{ $user->last_login ? $user->last_login->format('M d, Y') : 'First time' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100/50 shadow-sm">
                            <div class="flex items-center justify-center gap-1 text-green-600 mb-2">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <p class="text-[11px] uppercase font-bold text-gray-400 tracking-widest">Status</p>
                            <p class="text-sm font-bold text-green-600 mt-0.5">{{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 text-center border border-gray-100/50 shadow-sm">
                            <div class="flex items-center justify-center gap-1 text-blue-600 mb-2">
                                <i data-lucide="shield-check" class="w-5 h-5"></i>
                            </div>
                            <p class="text-[11px] uppercase font-bold text-gray-400 tracking-widest">Role</p>
                            <p class="text-sm font-bold text-blue-600 mt-0.5">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Form -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-2 border-b">
                    <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Profile Information
                    </h2>
                </div>
                <form method="POST" action="{{ route('my-account.update-profile') }}" class="p-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ $user->first_name ?? '' }}" required
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ $user->middle_name ?? '' }}"
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ $user->last_name ?? '' }}" required
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email }}" required
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                    </div>
                    <div class="flex justify-end mt-2">
                        <button type="submit" class="px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center gap-1">
                            <i data-lucide="save" class="w-3 h-3"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Password Settings -->
        <div class="space-y-3">
            
            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-2 border-b">
                    <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Change Password
                    </h2>
                </div>
                <form method="POST" action="{{ route('my-account.change-password') }}" class="p-3">
                    @csrf
                    <div class="space-y-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" name="current_password" required
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">New Password</label>
                            <input type="password" name="password" required
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                   class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-yellow-500 focus:border-yellow-500">
                        </div>
                    </div>
                    <button type="submit" class="w-full mt-2 px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700 flex items-center justify-center gap-1">
                        <i data-lucide="key" class="w-3 h-3"></i>
                        Change Password
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<!-- Profile Image Modal -->
<div id="profileModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeProfileModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center gap-2" id="modal-title">
                            <i data-lucide="user-circle" class="w-5 h-5 text-yellow-600"></i>
                            Update Profile Image
                        </h3>
                        <div class="mt-4 space-y-6">
                            <!-- Upload Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload custom image</label>
                                <form id="uploadForm" action="{{ route('my-account.update-profile-image') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="profile_image" accept="image/*" onchange="submitUpload()"
                                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100 cursor-pointer">
                                </form>
                            </div>

                            <div class="relative py-2">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-2 bg-white text-gray-500 uppercase tracking-wider text-[10px] font-bold">Or choose an icon</span>
                                </div>
                            </div>

                            <!-- Icon Grid -->
                            <div class="grid grid-cols-3 sm:grid-cols-5 gap-4">
                                @php
                                    $icons = [
                                        ['name' => 'Manager', 'file' => 'Manager.png'],
                                        ['name' => 'Mechanic', 'file' => 'Mechanic.png'],
                                        ['name' => 'Secretary', 'file' => 'secretary.png'],
                                        ['name' => 'Secretary 2', 'file' => 'secretary2.png'],
                                        ['name' => 'Manager 2', 'file' => 'manager2.png'],
                                    ];
                                @endphp
                                @foreach($icons as $icon)
                                    <div class="group cursor-pointer text-center" onclick="selectIcon('image/{{ $icon['file'] }}')">
                                        <div class="aspect-square rounded-lg border-2 border-gray-100 p-2 group-hover:border-yellow-500 group-hover:bg-yellow-50 transition-all mb-1">
                                            <img src="{{ asset('image/' . $icon['file']) }}" alt="{{ $icon['name'] }}" class="w-full h-full object-contain">
                                        </div>
                                        <span class="text-[10px] font-medium text-gray-500 group-hover:text-yellow-700">{{ $icon['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeProfileModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
                <form id="iconForm" action="{{ route('my-account.update-profile-image') }}" method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="icon_path" id="iconPathInput">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }

    function closeProfileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }

    function submitUpload() {
        document.getElementById('uploadForm').submit();
    }

    function selectIcon(path) {
        document.getElementById('iconPathInput').value = path;
        document.getElementById('iconForm').submit();
    }

    // Close on escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeProfileModal();
        }
    });
</script>
@endsection
