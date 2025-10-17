@extends('admin.layouts.app')

@section('title', 'Create Admin')
@section('page-title', 'Create New Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Create New Admin</h2>
                    <p class="text-sm text-gray-600 mt-1">Add a new admin user to the system</p>
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
        <form action="{{ route('admin.admins.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user mr-2 text-blue-600"></i>Full Name
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
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
                    value="{{ old('email') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('email') border-red-500 @enderror"
                    placeholder="Enter admin's email address"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12 @error('password') border-red-500 @enderror"
                        placeholder="Enter password (minimum 6 characters)"
                        required
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
                    <i class="fas fa-lock mr-2 text-blue-600"></i>Confirm Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 pr-12"
                        placeholder="Confirm the password"
                        required
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
                    <option value="">Select a role</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
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
                    {{ old('is_active', true) ? 'checked' : '' }}
                >
                <label for="is_active" class="text-sm font-medium text-gray-700">
                    <i class="fas fa-toggle-on mr-2 text-green-600"></i>Active Account
                </label>
            </div>
            <p class="text-sm text-gray-500 ml-7">Uncheck to create the account in disabled state</p>

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Admin
                </button>
            </div>
        </form>
    </div><br>
</div><br> <br> <br>

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

// Focus on name field when page loads
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('name').focus();
});
</script>
@endsection