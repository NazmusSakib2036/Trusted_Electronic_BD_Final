@extends('admin.layouts.app')

@section('title', 'Coupons Management')

@section('content')
<div x-data="couponsManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Coupons</h1>
            <p class="text-gray-600">Manage discount codes and promotional offers</p>
        </div>
        <button @click="showCreateForm = true" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Coupon
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Coupons</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_coupons"></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Coupons</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.active_coupons"></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Times Used</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_used"></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Saved</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="'৳' + (stats.total_discount || 0).toLocaleString()"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       x-model="filters.search" 
                       @input="filterCoupons()"
                       placeholder="Search coupons..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select x-model="filters.status" 
                        @change="filterCoupons()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select x-model="filters.type" 
                        @change="filterCoupons()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Types</option>
                    <option value="fixed">Fixed Amount</option>
                    <option value="percentage">Percentage</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                <select x-model="filters.expiry" 
                        @change="filterCoupons()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Coupons</option>
                    <option value="expiring_soon">Expiring Soon</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="coupon in paginatedCoupons" :key="coupon.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-green-500 to-blue-600 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="coupon.code"></div>
                                        <div class="text-sm text-gray-500" x-text="coupon.description || 'No description'"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <span x-show="coupon.type === 'percentage'" x-text="coupon.value + '%'"></span>
                                    <span x-show="coupon.type === 'fixed'" x-text="'৳' + coupon.value"></span>
                                </div>
                                <div class="text-sm text-gray-500" x-text="coupon.type === 'percentage' ? 'Percentage' : 'Fixed Amount'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <span x-text="(coupon.used_count || 0) + ' / ' + (coupon.usage_limit || '∞')"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-blue-600 h-2 rounded-full" 
                                         :style="'width: ' + (coupon.usage_limit ? (coupon.used_count || 0) / coupon.usage_limit * 100 : 0) + '%'"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full': true,
                                    'bg-green-100 text-green-800': coupon.status === 'active' && (!coupon.expires_at || new Date(coupon.expires_at) > new Date()),
                                    'bg-red-100 text-red-800': coupon.status === 'inactive' || (coupon.expires_at && new Date(coupon.expires_at) <= new Date()),
                                    'bg-yellow-100 text-yellow-800': coupon.status === 'active' && coupon.expires_at && new Date(coupon.expires_at) <= new Date(Date.now() + 7*24*60*60*1000)
                                }" x-text="getStatusText(coupon)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="coupon.expires_at ? formatDate(coupon.expires_at) : 'Never'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="formatDate(coupon.created_at)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button @click="editCoupon(coupon)" 
                                        class="text-blue-600 hover:text-blue-900">
                                    Edit
                                </button>
                                <button @click="toggleStatus(coupon)" 
                                        :class="coupon.status === 'active' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'">
                                    <span x-text="coupon.status === 'active' ? 'Deactivate' : 'Activate'"></span>
                                </button>
                                <button @click="deleteCoupon(coupon)" 
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <button @click="previousPage()" 
                            :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                        Previous
                    </button>
                    <button @click="nextPage()" 
                            :disabled="currentPage === totalPages"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50">
                        Next
                    </button>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span x-text="startIndex"></span> to <span x-text="endIndex"></span> of <span x-text="filteredCoupons.length"></span> coupons
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <button @click="previousPage()" 
                                    :disabled="currentPage === 1"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <template x-for="page in pageNumbers" :key="page">
                                <button @click="goToPage(page)" 
                                        :class="{
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium': true,
                                            'z-10 bg-blue-50 border-blue-500 text-blue-600': page === currentPage,
                                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': page !== currentPage
                                        }"
                                        x-text="page">
                                </button>
                            </template>
                            <button @click="nextPage()" 
                                    :disabled="currentPage === totalPages"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div><br> <br> <br> <br> <br>

    <!-- Create/Edit Modal -->
    <div x-show="showCreateForm || showEditForm" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         x-transition>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="showEditForm ? 'Edit Coupon' : 'Create New Coupon'"></h3>
                
                <form @submit.prevent="showEditForm ? updateCoupon() : createCoupon()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Coupon Code *</label>
                        <div class="flex">
                            <input type="text" 
                                   x-model="couponForm.code" 
                                   required
                                   placeholder="e.g., SAVE20"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" 
                                    @click="generateCode()"
                                    class="px-3 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-lg hover:bg-gray-200 text-sm">
                                Generate
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea x-model="couponForm.description" 
                                  rows="2"
                                  placeholder="Brief description of the coupon"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Discount Type *</label>
                        <select x-model="couponForm.type" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Type</option>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <span x-show="couponForm.type === 'percentage'">Discount Percentage *</span>
                            <span x-show="couponForm.type === 'fixed'">Discount Amount * (৳)</span>
                            <span x-show="!couponForm.type">Discount Value *</span>
                        </label>
                        <input type="number" 
                               x-model="couponForm.value" 
                               required
                               :min="couponForm.type === 'percentage' ? '1' : '0.01'"
                               :max="couponForm.type === 'percentage' ? '100' : ''"
                               step="0.01"
                               :placeholder="couponForm.type === 'percentage' ? 'e.g., 20' : 'e.g., 10.00'"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Order Amount (৳)</label>
                        <input type="number" 
                               x-model="couponForm.minimum_amount" 
                               min="0"
                               step="0.01"
                               placeholder="0.00 (no minimum)"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Usage Limit</label>
                        <input type="number" 
                               x-model="couponForm.usage_limit" 
                               min="1"
                               placeholder="Leave empty for unlimited"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                        <input type="datetime-local" 
                               x-model="couponForm.expires_at" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Leave empty for no expiry</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select x-model="couponForm.status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" 
                                :disabled="isLoading"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg disabled:opacity-50">
                            <span x-show="!isLoading" x-text="showEditForm ? 'Update Coupon' : 'Create Coupon'"></span>
                            <span x-show="isLoading">Processing...</span>
                        </button>
                        <button type="button" 
                                @click="closeModal()"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div x-show="notification.show" 
         x-transition
         :class="{
             'fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50': true,
             'bg-green-500 text-white': notification.type === 'success',
             'bg-red-500 text-white': notification.type === 'error'
         }">
        <span x-text="notification.message"></span>
    </div>
</div>

<script>
function couponsManager() {
    return {
        coupons: [],
        filteredCoupons: [],
        paginatedCoupons: [],
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        isLoading: false,
        showCreateForm: false,
        showEditForm: false,
        editingCoupon: null,
        
        stats: {
            total_coupons: 0,
            active_coupons: 0,
            total_used: 0,
            total_discount: 0
        },
        
        filters: {
            search: '',
            status: '',
            type: '',
            expiry: ''
        },
        
        couponForm: {
            code: '',
            description: '',
            type: '',
            value: '',
            minimum_amount: '',
            usage_limit: '',
            expires_at: '',
            status: 'active'
        },
        
        notification: {
            show: false,
            message: '',
            type: 'success'
        },

        async init() {
            await this.loadCoupons();
            await this.loadStats();
            this.filterCoupons();
        },

        async loadCoupons() {
            try {
                this.isLoading = true;
                console.log('Loading coupons...');
                const response = await fetch(`${window.API_BASE}/coupons`);
                console.log('Response status:', response.status);
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Raw API response:', data);
                    
                    // Check if it's paginated data
                    let coupons = [];
                    if (data.data && data.data.data) {
                        // Laravel paginated response
                        coupons = Array.isArray(data.data.data) ? data.data.data : [];
                        console.log('Using paginated data:', coupons);
                    } else if (data.data) {
                        // Direct data array
                        coupons = Array.isArray(data.data) ? data.data : [];
                        console.log('Using direct data:', coupons);
                    } else if (Array.isArray(data)) {
                        // Array response
                        coupons = data;
                        console.log('Using array data:', coupons);
                    }
                    
                    // Transform is_active boolean to status string for display
                    coupons = coupons.map(coupon => ({
                        ...coupon,
                        status: coupon.is_active ? 'active' : 'inactive'
                    }));
                    
                    console.log('Transformed coupons:', coupons);
                    this.coupons = coupons;
                } else {
                    throw new Error('Failed to load coupons');
                }
            } catch (error) {
                console.error('Error loading coupons:', error);
                this.coupons = []; // Ensure we have an empty array on error
                this.showNotification('Failed to load coupons', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async loadStats() {
            try {
                const response = await fetch('/api/admin/dashboard/stats');
                if (response.ok) {
                    const data = await response.json();
                    this.stats = data.data || data;
                } else {
                    throw new Error('Failed to load stats');
                }
            } catch (error) {
                console.error('Failed to load stats:', error);
            }
        },

        filterCoupons() {
            // Ensure this.coupons is an array before spreading
            if (!Array.isArray(this.coupons)) {
                this.coupons = [];
            }
            
            let filtered = [...this.coupons];
            
            // Search filter
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(coupon => 
                    coupon.code.toLowerCase().includes(search) ||
                    (coupon.description && coupon.description.toLowerCase().includes(search))
                );
            }
            
            // Status filter
            if (this.filters.status) {
                filtered = filtered.filter(coupon => coupon.status === this.filters.status);
            }
            
            // Type filter
            if (this.filters.type) {
                filtered = filtered.filter(coupon => coupon.type === this.filters.type);
            }
            
            // Expiry filter
            if (this.filters.expiry === 'expiring_soon') {
                const weekFromNow = new Date(Date.now() + 7*24*60*60*1000);
                filtered = filtered.filter(coupon => 
                    coupon.expires_at && 
                    new Date(coupon.expires_at) <= weekFromNow && 
                    new Date(coupon.expires_at) > new Date()
                );
            } else if (this.filters.expiry === 'expired') {
                filtered = filtered.filter(coupon => 
                    coupon.expires_at && new Date(coupon.expires_at) <= new Date()
                );
            }
            
            this.filteredCoupons = filtered;
            this.totalPages = Math.ceil(filtered.length / this.perPage);
            this.currentPage = 1;
            this.updatePagination();
        },

        updatePagination() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            this.paginatedCoupons = this.filteredCoupons.slice(start, end);
        },

        get startIndex() {
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get endIndex() {
            return Math.min(this.currentPage * this.perPage, this.filteredCoupons.length);
        },

        get pageNumbers() {
            const pages = [];
            const start = Math.max(1, this.currentPage - 2);
            const end = Math.min(this.totalPages, this.currentPage + 2);
            
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            return pages;
        },

        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.updatePagination();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.updatePagination();
            }
        },

        goToPage(page) {
            this.currentPage = page;
            this.updatePagination();
        },

        generateCode() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = '';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.couponForm.code = result;
        },

        async createCoupon() {
            try {
                this.isLoading = true;
                
                // Transform the form data to match API expectations
                const formData = {
                    ...this.couponForm,
                    is_active: this.couponForm.status === 'active',
                    starts_at: new Date().toISOString() // Set current time as start time
                };
                delete formData.status; // Remove the status field since we're using is_active
                
                const response = await fetch(`${window.API_BASE}/coupons`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    await this.loadCoupons();
                    this.filterCoupons();
                    this.closeModal();
                    this.showNotification('Coupon created successfully!', 'success');
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to create coupon');
                }
            } catch (error) {
                console.error('Error creating coupon:', error);
                this.showNotification('Failed to create coupon: ' + error.message, 'error');
            } finally {
                this.isLoading = false;
            }
        },

        editCoupon(coupon) {
            this.editingCoupon = coupon;
            this.couponForm = {
                code: coupon.code,
                description: coupon.description || '',
                type: coupon.type,
                value: coupon.value,
                minimum_amount: coupon.minimum_amount || '',
                usage_limit: coupon.usage_limit || '',
                expires_at: coupon.expires_at ? this.formatDateTimeLocal(coupon.expires_at) : '',
                status: coupon.status
            };
            this.showEditForm = true;
        },

        async updateCoupon() {
            try {
                this.isLoading = true;
                
                // Transform the form data to match API expectations
                const formData = {
                    ...this.couponForm,
                    is_active: this.couponForm.status === 'active'
                };
                delete formData.status; // Remove the status field since we're using is_active
                
                const response = await fetch(`${window.API_BASE}/coupons/${this.editingCoupon.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    await this.loadCoupons();
                    this.filterCoupons();
                    this.closeModal();
                    this.showNotification('Coupon updated successfully!', 'success');
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Failed to update coupon');
                }
            } catch (error) {
                console.error('Error updating coupon:', error);
                this.showNotification('Failed to update coupon: ' + error.message, 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async toggleStatus(coupon) {
            try {
                const newStatus = coupon.status === 'active' ? 'inactive' : 'active';
                const newIsActive = newStatus === 'active';
                
                const response = await fetch(`${window.API_BASE}/coupons/${coupon.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        ...coupon, 
                        is_active: newIsActive,
                        status: newStatus  // Keep both for compatibility
                    })
                });

                if (response.ok) {
                    coupon.status = newStatus;
                    coupon.is_active = newIsActive;
                    this.showNotification(`Coupon ${newStatus === 'active' ? 'activated' : 'deactivated'} successfully!`, 'success');
                } else {
                    throw new Error('Failed to update coupon status');
                }
            } catch (error) {
                this.showNotification('Failed to update coupon status', 'error');
            }
        },

        async deleteCoupon(coupon) {
            if (!confirm('Are you sure you want to delete this coupon? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`${window.API_BASE}/coupons/${coupon.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    await this.loadCoupons();
                    this.filterCoupons();
                    this.showNotification('Coupon deleted successfully!', 'success');
                } else {
                    throw new Error('Failed to delete coupon');
                }
            } catch (error) {
                this.showNotification('Failed to delete coupon', 'error');
            }
        },

        closeModal() {
            this.showCreateForm = false;
            this.showEditForm = false;
            this.editingCoupon = null;
            this.couponForm = {
                code: '',
                description: '',
                type: '',
                value: '',
                minimum_amount: '',
                usage_limit: '',
                expires_at: '',
                status: 'active'
            };
        },

        getStatusText(coupon) {
            if (coupon.expires_at && new Date(coupon.expires_at) <= new Date()) {
                return 'Expired';
            }
            if (coupon.status === 'active' && coupon.expires_at && new Date(coupon.expires_at) <= new Date(Date.now() + 7*24*60*60*1000)) {
                return 'Expiring Soon';
            }
            return coupon.status.charAt(0).toUpperCase() + coupon.status.slice(1);
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString();
        },

        formatDateTimeLocal(dateString) {
            const date = new Date(dateString);
            return date.toISOString().slice(0, 16);
        },

        showNotification(message, type = 'success') {
            this.notification = { show: true, message, type };
            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        }
    }
}
</script>
@endsection