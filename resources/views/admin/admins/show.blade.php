@extends('admin.layouts.app')

@section('title', 'View Admin')
@section('page-title', 'Admin Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Admin User Details</h2>
                    <p class="text-sm text-gray-600 mt-1">View admin user information and permissions</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Admin
                    </a>
                    <a href="{{ route('admin.admins.index') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Admin Profile -->
                <div class="space-y-6">
                    <!-- Profile Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-20 w-20">
                                <div class="h-20 w-20 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-2xl">{{ substr($admin->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-6">
                                <div class="text-2xl font-bold text-blue-900">{{ $admin->name }}</div>
                                <div class="text-blue-700 text-lg">{{ $admin->email }}</div>
                                <div class="flex items-center mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($admin->role === 'super_admin') bg-purple-100 text-purple-800
                                        @elseif($admin->role === 'admin') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        @if($admin->role === 'super_admin')
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                        {{ $admin->role_display }}
                                    </span>
                                    <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($admin->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                        <span class="w-2 h-2 rounded-full mr-2 @if($admin->is_active) bg-green-600 @else bg-red-600 @endif"></span>
                                        {{ $admin->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-info-circle mr-2 text-blue-600"></i>Account Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">User ID:</span>
                                <span class="font-medium">#{{ $admin->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email Verified:</span>
                                <span class="font-medium">
                                    @if($admin->email_verified_at)
                                        <span class="text-green-600">✓ Verified</span>
                                    @else
                                        <span class="text-yellow-600">⚠ Not Verified</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Account Created:</span>
                                <span class="font-medium">{{ $admin->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated:</span>
                                <span class="font-medium">{{ $admin->updated_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Time Since Created:</span>
                                <span class="font-medium">{{ $admin->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions & Access -->
                <div class="space-y-6">
                    <!-- Role Permissions -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-shield-alt mr-2 text-green-600"></i>Role Permissions
                        </h3>
                        
                        @if($admin->role === 'super_admin')
                        <div class="space-y-3">
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Full system access</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Admin user management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Product management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Order management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Customer management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Category management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Coupon management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>System configuration</span>
                            </div>
                        </div>
                        @elseif($admin->role === 'admin')
                        <div class="space-y-3">
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-times-circle mr-3"></i>
                                <span>Admin user management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Product management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Order management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Customer management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Category management</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Coupon management</span>
                            </div>
                            <div class="flex items-center text-yellow-600">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                <span>Limited system configuration</span>
                            </div>
                        </div>
                        @else
                        <div class="space-y-3">
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-times-circle mr-3"></i>
                                <span>Admin user management</span>
                            </div>
                            <div class="flex items-center text-yellow-600">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                <span>Limited product management</span>
                            </div>
                            <div class="flex items-center text-yellow-600">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                <span>View-only order access</span>
                            </div>
                            <div class="flex items-center text-yellow-600">
                                <i class="fas fa-exclamation-circle mr-3"></i>
                                <span>View-only customer access</span>
                            </div>
                            <div class="flex items-center text-green-600">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>Category management</span>
                            </div>
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-times-circle mr-3"></i>
                                <span>Coupon management</span>
                            </div>
                            <div class="flex items-center text-red-600">
                                <i class="fas fa-times-circle mr-3"></i>
                                <span>System configuration</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white border border-gray-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-bolt mr-2 text-yellow-600"></i>Quick Actions
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                               class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-edit text-blue-600 mr-3"></i>
                                <span class="font-medium text-gray-700">Edit Admin Details</span>
                            </a>
                            
                            @if($admin->id !== Auth::guard('admin')->id())
                            <button onclick="toggleStatus({{ $admin->id }})" 
                                    class="w-full flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                                <i class="fas fa-toggle-{{ $admin->is_active ? 'off' : 'on' }} text-{{ $admin->is_active ? 'red' : 'green' }}-600 mr-3"></i>
                                <span class="font-medium text-gray-700">{{ $admin->is_active ? 'Deactivate' : 'Activate' }} Account</span>
                            </button>
                            
                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this admin? This action cannot be undone.')"
                                  class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full flex items-center p-3 border border-red-200 rounded-lg hover:bg-red-50 transition duration-200">
                                    <i class="fas fa-trash text-red-600 mr-3"></i>
                                    <span class="font-medium text-red-700">Delete Admin Account</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleStatus(adminId) {
    fetch(`/admin/admins/${adminId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update the UI
        } else {
            alert(data.error || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status');
    });
}
</script>
@endsection