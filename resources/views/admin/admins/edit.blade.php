@extends('admin.layouts.app')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin User')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Edit Admin User</h2>
                    <p class="text-sm text-gray-600 mt-1">Update admin user information and permissions</p>
                </div>
                <a href="{{ route('admin.admins.index') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="m-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('admin.admins.update', $admin->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Current Admin Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                            <span class="text-white font-medium text-lg">{{ substr($admin->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-lg font-medium text-blue-900">{{ $admin->name }}</div>
                        <div class="text-blue-700">{{ $admin->email }}</div>
                        <div class="text-sm text-blue-600">Created: {{ $admin->created_at->format('M d, Y g:i A') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user mr-2 text-blue-600"></i>Full Name
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $admin->name) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                    placeholder="Enter admin's full name"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-envelope mr-2 text-blue-600"></i>Email Address
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $admin->email) }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                    placeholder="Enter admin's email address"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Fields -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="text-sm font-medium text-yellow-800 mb-3">
                    <i class="fas fa-info-circle mr-2"></i>Password Update
                </h4>
                <p class="text-sm text-yellow-700 mb-4">Leave password fields empty to keep current password unchanged.</p>
                
                <!-- New Password Field -->
                <div class="space-y-2 mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12 @error('password') border-red-500 @enderror"
                            placeholder="Enter new password (minimum 6 characters)"
                        >
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword('password')"
                        >
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="toggleIcon-password"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>Confirm New Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12"
                            placeholder="Confirm the new password"
                        >
                        <button 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                            onclick="togglePassword('password_confirmation')"
                        >
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="toggleIcon-password_confirmation"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Role Field -->
            <div class="space-y-2">
                <label for="role" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-shield-alt mr-2 text-blue-600"></i>Admin Role
                </label>
                <select 
                    id="role" 
                    name="role" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('role') border-red-500 @enderror"
                    required
                >
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role', $admin->role) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Role Descriptions -->
                <div class="mt-2 text-sm text-gray-600 space-y-1">
                    <div><strong>Super Admin:</strong> Full access to all features including admin management</div>
                    <div><strong>Admin:</strong> Access to most features except admin management</div>
                    <div><strong>Moderator:</strong> Limited access to content management only</div>
                </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center space-x-3">
                <input 
                    type="checkbox" 
                    id="is_active" 
                    name="is_active" 
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    {{ old('is_active', $admin->is_active) ? 'checked' : '' }}
                >
                <label for="is_active" class="text-sm font-medium text-gray-700">
                    <i class="fas fa-toggle-on mr-2 text-green-600"></i>Active Account
                </label>
            </div>
            <p class="text-sm text-gray-500 ml-7">Uncheck to disable this admin account</p>

            @if($admin->id === Auth::guard('admin')->id())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                    <div>
                        <h4 class="text-red-800 font-medium">Editing Your Own Account</h4>
                        <p class="text-red-700 text-sm mt-1">Be careful when changing your role or status as it may affect your access.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex items-center justify-center space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.admins.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition duration-200">
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-200 flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Update
                </button>
            </div>
        </form>
    </div><br> 
</div><br> <br><br>

<script>
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(`toggleIcon-${fieldId}`);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection