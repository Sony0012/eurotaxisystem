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
                                <span class="text-sm font-semibold text-yellow-700 bg-yellow-100 px-3 py-1 rounded-full">{{ $user->role === 'super_admin' ? 'Owner' : ucfirst($user->role) }}</span>
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
                            <p class="text-sm font-bold text-blue-600 mt-0.5">{{ $user->role === 'super_admin' ? 'Owner' : ucfirst($user->role) }}</p>
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
                            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" value="{{ $user->email }}" readonly
                                   class="w-full px-3 py-2 text-sm border border-gray-100 bg-gray-50 rounded-xl text-gray-500 cursor-not-allowed font-medium">
                            <p class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                <i data-lucide="info" class="w-3 h-3"></i> Use the "Change Email" section below to update this.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">First Name</label>
                            <input type="text" name="first_name" value="{{ $user->first_name }}" required
                                   maxlength="18" oninput="formatName(this)"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ $user->middle_name }}"
                                   maxlength="18" oninput="formatName(this)"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all font-bold">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">Last Name</label>
                            <input type="text" name="last_name" value="{{ $user->last_name }}" required
                                   maxlength="18" oninput="formatName(this)"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all font-bold">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Phone Number (11 Digits)</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ $user->phone_number ?? '' }}" required
                                   maxlength="11"
                                   oninput="validatePhone(this)"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-yellow-500 focus:border-transparent font-bold tracking-widest transition-all"
                                   placeholder="09XXXXXXXXX">
                            <p class="text-[10px] text-gray-400 mt-1">Must start with 09 (e.g. 09123456789)</p>
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
                <form method="POST" action="{{ route('my-account.change-password') }}" class="p-4 space-y-4">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">Current Password</label>
                            <div class="relative group">
                                <input type="password" name="current_password" id="current_password" required
                                       class="w-full pl-3 pr-10 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                                <button type="button" onclick="togglePassword('current_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-yellow-600 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4" id="eye-current_password"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">New Password</label>
                            <div class="relative group">
                                <input type="password" name="password" id="new_password" required
                                       class="w-full pl-3 pr-10 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                                <button type="button" onclick="togglePassword('new_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-yellow-600 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4" id="eye-new_password"></i>
                                </button>
                            </div>
                            {{-- Password Criteria --}}
                            <div class="mt-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 flex items-center gap-1.5">
                                    <i data-lucide="shield-check" class="w-3 h-3 text-yellow-500"></i> Required Criteria:
                                </p>
                                <ul class="space-y-1.5" id="password-requirements">
                                    <li id="req-length" class="flex items-center gap-2 text-[10px] font-bold text-gray-400 transition-colors">
                                        <i data-lucide="circle" class="w-2.5 h-2.5 req-icon"></i> Minimum 8 characters
                                    </li>
                                    <li id="req-alphanumeric" class="flex items-center gap-2 text-[10px] font-bold text-gray-400 transition-colors">
                                        <i data-lucide="circle" class="w-2.5 h-2.5 req-icon"></i> Mix of letters and numbers
                                    </li>
                                    <li id="req-special" class="flex items-center gap-2 text-[10px] font-bold text-gray-400 transition-colors">
                                        <i data-lucide="circle" class="w-2.5 h-2.5 req-icon"></i> At least one special character
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">Confirm New Password</label>
                            <div class="relative group">
                                <input type="password" name="password_confirmation" id="confirm_password" required
                                       class="w-full pl-3 pr-10 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                                <button type="button" onclick="togglePassword('confirm_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-yellow-600 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4" id="eye-confirm_password"></i>
                                </button>
                            </div>
                            <p id="match-error" class="hidden text-[10px] font-bold text-red-500 mt-1.5 flex items-center gap-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i> Passwords do not match
                            </p>
                            <p id="match-success" class="hidden text-[10px] font-bold text-green-500 mt-1.5 flex items-center gap-1">
                                <i data-lucide="check-circle" class="w-3 h-3"></i> Passwords match
                            </p>
                        </div>
                    </div>
                    <button type="submit" id="submit-btn" disabled
                            class="w-full mt-2 px-4 py-2.5 bg-gray-200 text-gray-400 text-xs font-black uppercase tracking-widest rounded-xl cursor-not-allowed transition-all flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Update Account Password
                    </button>
                </form>
            </div>

            <!-- Change Email -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-2 border-b">
                    <h2 class="text-sm font-semibold text-gray-900 flex items-center gap-1">
                        <i data-lucide="mail" class="w-4 h-4 text-blue-600"></i>
                        Change Email Address
                    </h2>
                </div>
                <form method="POST" action="{{ route('my-account.request-email-change') }}" class="p-4 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">New Email Address</label>
                        <input type="email" name="new_email" required placeholder="newemail@gmail.com"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1.5">Verify with Password</label>
                        <input type="password" name="current_password" required placeholder="Enter current password"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest mb-1.5 flex items-center gap-1.5">
                            <i data-lucide="shield-alert" class="w-3 h-3"></i> Security Step:
                        </p>
                        <p class="text-[10px] font-bold text-blue-500 leading-relaxed">
                            A confirmation link will be sent to your **current email ({{ $user->email }})**. You must click that link to authorize the change.
                        </p>
                    </div>

                    <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i>
                        Request Email Change
                    </button>
                </form>
            </div>


        </div>
    </div>
</div>

<!-- ─── Profile Image Modal (21st.dev Luxury Redesign) ─── -->
<div id="profileModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity" aria-hidden="true" onclick="closeProfileModal()"></div>

        <!-- Modal Card Container -->
        <div class="relative inline-block w-full max-w-xl overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-b from-white via-amber-50/20 to-slate-50 p-6 sm:p-8 text-left align-middle shadow-2xl transition-all z-10 animate-bounce-in">
            <!-- Close Button -->
            <button type="button" onclick="closeProfileModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 p-2 rounded-xl bg-slate-100/80 hover:bg-slate-200 transition-all cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>

            <!-- Header -->
            <div class="flex items-center gap-3.5 mb-6">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-400 to-amber-600 text-white flex items-center justify-center shadow-md shadow-amber-500/20 shrink-0">
                    <i data-lucide="user-round" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-lg sm:text-xl font-black text-slate-900 tracking-tight" id="modal-title">
                        Update Profile Avatar
                    </h3>
                    <p class="text-xs text-slate-500 font-medium">Upload your personal photo or select an official role avatar</p>
                </div>
            </div>

            <!-- Upload Custom File Dropzone Area -->
            <div class="mb-6">
                <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-2">Upload Custom Image</span>
                <form id="uploadForm" action="{{ route('my-account.update-profile-image') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="relative flex flex-col items-center justify-center p-5 rounded-2xl border-2 border-dashed border-slate-300 hover:border-amber-500 bg-white hover:bg-amber-50/40 transition-all cursor-pointer group text-center shadow-xs">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                            <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-800 group-hover:text-amber-700">Click to choose image or drag & drop</span>
                        <span class="text-[11px] text-slate-400 mt-0.5 font-medium">PNG, JPG, JPEG, WEBP or GIF (Max 2MB)</span>
                        <input type="file" name="profile_image" accept="image/*" onchange="submitUpload()" class="hidden">
                    </label>
                </form>
            </div>

            <!-- Stylish Divider -->
            <div class="relative py-2 mb-4">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="px-3 bg-gradient-to-b from-white to-amber-50/20 text-slate-400 uppercase tracking-wider text-[10px] font-black">Or choose an official role avatar</span>
                </div>
            </div>

            <!-- 3D Role SVG Avatars Grid -->
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-6">
                @php
                    $roleAvatars = [
                        ['name' => 'Owner',      'role' => 'Super Admin', 'file' => 'avatars/owner.svg'],
                        ['name' => 'Manager',    'role' => 'Operations',  'file' => 'avatars/manager.svg'],
                        ['name' => 'Dispatcher', 'role' => 'Radio Ops',   'file' => 'avatars/dispatcher.svg'],
                        ['name' => 'Secretary',  'role' => 'Admin Staff', 'file' => 'avatars/secretary.svg'],
                        ['name' => 'Mechanic',   'role' => 'Technical',   'file' => 'avatars/mechanic.svg'],
                        ['name' => 'Cashier',    'role' => 'Finance',     'file' => 'avatars/cashier.svg'],
                    ];
                @endphp
                @foreach($roleAvatars as $icon)
                    <div class="group relative flex flex-col items-center p-2.5 rounded-2xl bg-white border-2 border-slate-100 hover:border-amber-500 hover:bg-amber-50/50 hover:shadow-md transition-all cursor-pointer active:scale-95 text-center shadow-xs"
                         onclick="selectIcon('image/{{ $icon['file'] }}')">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden p-1 mb-1.5 group-hover:scale-105 transition-transform">
                            <img src="{{ asset('image/' . $icon['file']) }}" alt="{{ $icon['name'] }}" class="w-full h-full object-contain filter drop-shadow-sm">
                        </div>
                        <span class="text-[11px] font-black text-slate-800 group-hover:text-amber-700 leading-tight block truncate w-full">{{ $icon['name'] }}</span>
                        <span class="text-[9px] font-semibold text-slate-400 block leading-none mt-0.5 truncate w-full">{{ $icon['role'] }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Footer Action -->
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="closeProfileModal()"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 bg-white hover:bg-slate-50 text-xs font-black uppercase tracking-wider shadow-xs transition-all cursor-pointer">
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

    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = document.getElementById('eye-' + id);
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        if (window.lucide) lucide.createIcons();
    }

    function validatePhone(input) {
        // Remove non-digits
        let val = input.value.replace(/\D/g, '');
        
        // If empty, keep empty
        if (val === "") {
            input.value = "";
            return;
        }

        // Handle prepending 09
        if (val.length === 1) {
            // If they typed 9, make it 09
            if (val === "9") {
                val = "09";
            } else if (val !== "0") {
                // If they typed any other number (e.g. 8), make it 098
                val = "09" + val;
            }
        } else if (val.length >= 2) {
            // Ensure starts with 09
            if (!val.startsWith('09')) {
                // If it starts with 9 (but not 09), prepend 0
                if (val.startsWith('9')) {
                    val = '0' + val;
                } else {
                    // Otherwise force 09 prefix
                    val = '09' + val;
                }
            }
        }
        
        // Max 11 digits
        if (val.length > 11) {
            val = val.slice(0, 11);
        }
        
        input.value = val;
    }

    function formatName(input) {
        // 1. Remove numbers and symbols (allow only letters and spaces)
        let val = input.value.replace(/[^a-zA-Z\s]/g, '');
        
        // 2. Prohibit multiple spaces (allow only single space)
        val = val.replace(/\s\s+/g, ' ');
        
        // 3. Prohibit leading space
        if (val === ' ') {
            val = '';
        }

        // 4. Auto-correct: Title Case (First letter Caps, others small)
        // If it's a multi-word name, capitalize each word
        let words = val.split(' ');
        for (let i = 0; i < words.length; i++) {
            if (words[i].length > 0) {
                words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
            }
        }
        val = words.join(' ');

        // 5. Max 18 characters
        if (val.length > 18) {
            val = val.slice(0, 18);
        }

        input.value = val;
    }

    // Password Validation Logic
    const newPass = document.getElementById('new_password');
    const confirmPass = document.getElementById('confirm_password');
    const submitBtn = document.getElementById('submit-btn');

    const reqs = {
        length: { el: document.getElementById('req-length'), regex: /.{8,}/ },
        alphanumeric: { el: document.getElementById('req-alphanumeric'), regex: /^(?=.*[a-zA-Z])(?=.*[0-9])/ },
        special: { el: document.getElementById('req-special'), regex: /[!@#$%^&*(),.?":{}|<>]/ }
    };

    function validatePassword() {
        // Strip spaces in real-time
        newPass.value = newPass.value.replace(/\s/g, '');
        confirmPass.value = confirmPass.value.replace(/\s/g, '');

        const val = newPass.value;
        const confirmVal = confirmPass.value;
        let allValid = true;

        // Check each requirement
        for (const key in reqs) {
            const isValid = reqs[key].regex.test(val);
            const el = reqs[key].el;
            const icon = el.querySelector('.req-icon');
            
            if (isValid && val.length > 0) {
                el.classList.remove('text-gray-400');
                el.classList.add('text-green-500');
                icon.setAttribute('data-lucide', 'check-circle-2');
            } else {
                el.classList.remove('text-green-500');
                el.classList.add('text-gray-400');
                icon.setAttribute('data-lucide', 'circle');
                allValid = false;
            }
        }

        // Check Match
        const matchError = document.getElementById('match-error');
        const matchSuccess = document.getElementById('match-success');
        
        if (confirmVal.length > 0) {
            if (val === confirmVal) {
                matchError.classList.add('hidden');
                matchSuccess.classList.remove('hidden');
            } else {
                matchError.classList.remove('hidden');
                matchSuccess.classList.add('hidden');
                allValid = false;
            }
        } else {
            matchError.classList.add('hidden');
            matchSuccess.classList.add('hidden');
            allValid = false;
        }

        // Update Submit Button
        if (allValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-yellow-600', 'text-white', 'hover:bg-yellow-700', 'shadow-lg', 'shadow-yellow-100');
            submitBtn.innerHTML = '<i data-lucide="key" class="w-4 h-4"></i> Update Account Password';
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-gray-200', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-yellow-600', 'text-white', 'hover:bg-yellow-700', 'shadow-lg', 'shadow-yellow-100');
            submitBtn.innerHTML = '<i data-lucide="lock" class="w-4 h-4"></i> Update Account Password';
        }

        if (window.lucide) lucide.createIcons();
    }

    newPass.addEventListener('input', validatePassword);
    confirmPass.addEventListener('input', validatePassword);

    // Send Test Push Notification via web route
    async function sendTestPush() {
        const btn = document.getElementById('btnTestPush');
        if (!btn) return;
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Sending...';
        if (window.lucide) lucide.createIcons();

        try {
            const response = await fetch('{{ route("my-account.test-push") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: '🚨 Euro Taxi Test Notification',
                    body: 'Congratulations! Your Android device has been registered successfully and can receive real-time push notifications!',
                    type: 'system_alert'
                })
            });
            const data = await response.json();
            if (data.success) {
                alert('Success! Test push notification has been sent via Firebase.');
            } else {
                alert('Failed to send test push: ' + (data.message || 'Unknown error'));
            }
        } catch (e) {
            console.error(e);
            alert('An error occurred while sending test push notification.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
            if (window.lucide) lucide.createIcons();
        }
    }

    // Dynamic Real-time FCM Device Registration UI Handler
    window.addEventListener('fcm_token_synced_event', function(e) {
        const container = document.getElementById('fcm-status-container');
        const btn = document.getElementById('btnTestPush');
        if (container) {
            const tokenAbbrev = e.detail.token.substring(0, 20) + '...';
            container.innerHTML = `
                <div class="p-3 bg-purple-50 rounded-xl border border-purple-100 flex items-start gap-2 animate-bounce">
                    <i data-lucide="check-circle" class="w-4 h-4 text-purple-600 shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-[10px] font-bold text-purple-700 uppercase tracking-wider">Device Registered (Just Now!)</p>
                        <p class="text-[9px] text-purple-600 font-semibold truncate max-w-[200px]">Token: ${tokenAbbrev}</p>
                    </div>
                </div>
            `;
            if (window.lucide) lucide.createIcons();
        }
        if (btn) {
            btn.removeAttribute('disabled');
            btn.disabled = false;
        }
    });

    // Close on escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeProfileModal();
        }
    });
</script>
@endsection
