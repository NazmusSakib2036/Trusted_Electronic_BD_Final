@extends('admin.layouts.app')

@section('title', 'Admin Management')
@section('page-title', 'Admin Management')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Admin Users</h2>
                <p class="text-sm text-gray-600 mt-1">Manage admin users and their access levels</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add New Admin
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="m-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="m-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Admin Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admin</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr($admin->name, 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $admin->name }}</div>
                                <div class="text-sm text-gray-500">{{ $admin->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($admin->role === 'super_admin') bg-purple-100 text-purple-800
                            @elseif($admin->role === 'admin') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800 @endif">
                            @if($admin->role === 'super_admin')
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                            {{ $admin->role_display }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="toggleStatus({{ $admin->id }})" 
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium cursor-pointer transition-colors
                                @if($admin->is_active) bg-green-100 text-green-800 hover:bg-green-200
                                @else bg-red-100 text-red-800 hover:bg-red-200 @endif">
                            <span class="w-2 h-2 rounded-full mr-1.5 @if($admin->is_active) bg-green-600 @else bg-red-600 @endif"></span>
                            <span id="status-{{ $admin->id }}">{{ $admin->is_active ? 'Active' : 'Inactive' }}</span>
                        </button>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $admin->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.admins.show', $admin->id) }}" 
                               class="text-blue-600 hover:text-blue-900 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @if($admin->id !== Auth::guard('admin')->id())
                            <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No admin users found</h3>
                            <p class="text-gray-500">Get started by creating your first admin user.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($admins->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $admins->links() }}
    </div>
    @endif
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
            const statusElement = document.getElementById(`status-${adminId}`);
            const buttonElement = statusElement.parentElement;
            
            if (data.is_active) {
                statusElement.textContent = 'Active';
                buttonElement.className = buttonElement.className.replace('bg-red-100 text-red-800 hover:bg-red-200', 'bg-green-100 text-green-800 hover:bg-green-200');
                statusElement.previousElementSibling.className = statusElement.previousElementSibling.className.replace('bg-red-600', 'bg-green-600');
            } else {
                statusElement.textContent = 'Inactive';
                buttonElement.className = buttonElement.className.replace('bg-green-100 text-green-800 hover:bg-green-200', 'bg-red-100 text-red-800 hover:bg-red-200');
                statusElement.previousElementSibling.className = statusElement.previousElementSibling.className.replace('bg-green-600', 'bg-red-600');
            }
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