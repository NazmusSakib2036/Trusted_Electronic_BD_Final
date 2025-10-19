@extends('admin.layouts.app')

@section('title', 'Customers Management')

@section('content')
<div x-data="customersManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
            <p class="text-gray-600">Manage customer profiles and information</p>
        </div>
        <div class="flex gap-3">
            <button @click="exportCustomers()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Customers
            </button>
            <button @click="showCreateForm = true" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Customer
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_customers"></p>
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
                    <p class="text-sm font-medium text-gray-600">Active Customers</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.active_customers"></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_orders"></p>
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
                    <p class="text-sm font-medium text-gray-600">Average Order Value</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="'৳' + (stats.average_order_value || 0).toFixed(2)"></p>
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
                       @input="filterCustomers()"
                       placeholder="Search customers..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <!-- <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select x-model="filters.status" 
                        @change="filterCustomers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div> -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                <select x-model="filters.date_range" 
                        @change="filterCustomers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Time</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Order Count</label>
                <select x-model="filters.order_count" 
                        @change="filterCustomers()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Customers</option>
                    <option value="new">New (0 orders)</option>
                    <option value="returning">Returning (1+ orders)</option>
                    <option value="loyal">Loyal (5+ orders)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="customer in paginatedCustomers" :key="customer.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm" x-text="customer.name ? customer.name.charAt(0).toUpperCase() : 'C'"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="customer.name || 'N/A'"></div>
                                        <div class="text-sm text-gray-500" x-text="'Customer #' + customer.id"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900" x-text="(customer.division || 'Unknown') + ', ' + (customer.district || 'Unknown')"></div>
                                <div class="text-sm text-gray-500" x-text="customer.phone || 'No phone'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900" x-text="customer.orders_count || 0"></div>
                                <div class="text-sm text-gray-500">
                                    <span x-show="(customer.orders_count || 0) === 0">New customer</span>
                                    <span x-show="(customer.orders_count || 0) >= 1 && (customer.orders_count || 0) < 5">Returning</span>
                                    <span x-show="(customer.orders_count || 0) >= 5">Loyal customer</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span x-text="'৳' + parseFloat(customer.total_spent || 0).toFixed(2)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full': true,
                                    'bg-green-100 text-green-800': customer.is_active === true,
                                    'bg-red-100 text-red-800': customer.is_active === false
                                }" x-text="customer.is_active ? 'Active' : 'Inactive'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="formatDate(customer.created_at)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button @click="viewCustomer(customer)" 
                                        class="text-blue-600 hover:text-blue-900">
                                    View
                                </button>
                                <button @click="editCustomer(customer)" 
                                        class="text-green-600 hover:text-green-900">
                                    Edit
                                </button>
                                <!-- <button @click="toggleStatus(customer)" 
                                        :class="customer.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'">
                                    <span x-text="customer.is_active ? 'Deactivate' : 'Activate'"></span>
                                </button> -->
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
                            Showing <span x-text="startIndex"></span> to <span x-text="endIndex"></span> of <span x-text="filteredCustomers.length"></span> customers
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
    </div> <br> <br> <br> <br> <br>

    <!-- Customer Details Modal -->
    <div x-show="showCustomerDetails" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         x-transition>
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Customer Details</h3>
                    <button @click="showCustomerDetails = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div x-show="selectedCustomer">
                    <!-- Customer Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Personal Information</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Name:</span> <span x-text="selectedCustomer?.name || 'N/A'"></span></div>
                                <div><span class="font-medium">Phone:</span> <span x-text="selectedCustomer?.phone || 'N/A'"></span></div>
                                <div><span class="font-medium">Division:</span> <span x-text="selectedCustomer?.division || 'Not specified'"></span></div>
                                <div><span class="font-medium">District:</span> <span x-text="selectedCustomer?.district || 'Not specified'"></span></div>
                                <!-- <div><span class="font-medium">Status:</span> <span x-text="selectedCustomer?.is_active ? 'Active' : 'Inactive'"></span></div> -->
                                <div><span class="font-medium">Joined:</span> <span x-text="formatDate(selectedCustomer?.created_at)"></span></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Order Statistics</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Total Orders:</span> <span x-text="selectedCustomer?.orders_count || 0"></span></div>
                                <div><span class="font-medium">Total Spent:</span> <span x-text="'৳' + parseFloat(selectedCustomer?.total_spent || 0).toFixed(2)"></span></div>
                                <div><span class="font-medium">Average Order:</span> <span x-text="'৳' + (selectedCustomer?.orders_count ? (selectedCustomer.total_spent / selectedCustomer.orders_count).toFixed(2) : '0.00')"></span></div>
                                <div><span class="font-medium">Last Order:</span> <span x-text="selectedCustomer?.last_order_date ? formatDate(selectedCustomer.last_order_date) : 'Never'"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Recent Orders</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template x-for="order in selectedCustomer?.recent_orders || []" :key="order.id">
                                        <tr>
                                            <td class="px-4 py-2 text-sm font-medium" x-text="order.order_number"></td>
                                            <td class="px-4 py-2 text-sm" x-text="formatDate(order.created_at)"></td>
                                            <td class="px-4 py-2 text-sm">
                                                <span :class="{
                                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full': true,
                                                    'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                                    'bg-blue-100 text-blue-800': order.status === 'processing',
                                                    'bg-purple-100 text-purple-800': order.status === 'shipped',
                                                    'bg-green-100 text-green-800': order.status === 'delivered',
                                                    'bg-red-100 text-red-800': order.status === 'cancelled'
                                                }" x-text="order.status.charAt(0).toUpperCase() + order.status.slice(1)"></span>
                                            </td>
                                            <td class="px-4 py-2 text-sm font-medium" x-text="'$' + parseFloat(order.total || 0).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="!selectedCustomer?.recent_orders || selectedCustomer.recent_orders.length === 0">
                                        <td colspan="4" class="px-4 py-6 text-sm text-gray-500 text-center">No orders found</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Default Address</h4>
                            <div class="text-sm text-gray-600">
                                <template x-if="selectedCustomer?.address_line_1">
                                    <div>
                                        <div x-text="selectedCustomer.address_line_1"></div>
                                        <div x-show="selectedCustomer.address_line_2" x-text="selectedCustomer.address_line_2"></div>
                                        <div x-show="selectedCustomer.city || selectedCustomer.state">
                                            <span x-text="selectedCustomer.city || ''"></span>
                                            <span x-show="selectedCustomer.city && selectedCustomer.state">, </span>
                                            <span x-text="selectedCustomer.state || ''"></span>
                                        </div>
                                        <div x-show="selectedCustomer.postal_code" x-text="selectedCustomer.postal_code"></div>
                                        <div x-show="selectedCustomer.country" x-text="selectedCustomer.country"></div>
                                    </div>
                                </template>
                                <template x-if="!selectedCustomer?.address_line_1">
                                    <div>No address on file</div>
                                </template>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Notes</h4>
                            <div class="text-sm text-gray-600">
                                <div x-text="selectedCustomer?.notes || 'No notes'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showCreateForm || showEditForm" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         x-transition>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="showEditForm ? 'Edit Customer' : 'Add New Customer'"></h3>
                
                <form @submit.prevent="showEditForm ? updateCustomer() : createCustomer()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                        <input type="text" 
                               x-model="customerForm.name" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                        <input type="tel" 
                               x-model="customerForm.phone" 
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Division *</label>
                            <select x-model="customerForm.division" 
                                    @change="onCustomerDivisionChange()"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Division</option>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Rajshahi">Rajshahi</option>
                                <option value="Khulna">Khulna</option>
                                <option value="Barishal">Barishal</option>
                                <option value="Sylhet">Sylhet</option>
                                <option value="Rangpur">Rangpur</option>
                                <option value="Mymensingh">Mymensingh</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">District *</label>
                            <select x-model="customerForm.district" 
                                    required
                                    :disabled="!customerForm.division"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100">
                                <option value="">Select District</option>
                                <template x-for="district in availableCustomerDistricts" :key="district">
                                    <option :value="district" x-text="district"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" 
                               x-model="customerForm.email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <textarea x-model="customerForm.address" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <!-- <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select x-model="customerForm.is_active" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div> -->

                    <div class="flex items-center gap-3">
                        <button type="submit" 
                                :disabled="isLoading"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg disabled:opacity-50">
                            <span x-show="!isLoading" x-text="showEditForm ? 'Update Customer' : 'Create Customer'"></span>
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
function customersManager() {
    return {
        customers: [],
        filteredCustomers: [],
        paginatedCustomers: [],
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        isLoading: false,
        showCreateForm: false,
        showEditForm: false,
        showCustomerDetails: false,
        editingCustomer: null,
        selectedCustomer: null,
        
        stats: {
            total_customers: 0,
            active_customers: 0,
            total_orders: 0,
            average_order_value: 0
        },
        
        filters: {
            search: '',
            status: '',
            date_range: '',
            order_count: ''
        },
        
        customerForm: {
            name: '',
            email: '',
            phone: '',
            division: '',
            district: '',
            address: '',
            is_active: 1
        },
        
        customerDivisions: [
            {
                name: 'Dhaka',
                districts: ['Dhaka', 'Faridpur', 'Gazipur', 'Gopalganj', 'Kishoreganj', 'Madaripur', 'Manikganj', 'Munshiganj', 'Narayanganj', 'Narsingdi', 'Rajbari', 'Shariatpur', 'Tangail']
            },
            {
                name: 'Chittagong',
                districts: ['Chittagong', 'Bandarban', 'Brahmanbaria', 'Chandpur', 'Comilla', 'Cox\'s Bazar', 'Feni', 'Khagrachari', 'Lakshmipur', 'Noakhali', 'Rangamati']
            },
            {
                name: 'Rajshahi',
                districts: ['Rajshahi', 'Bogura', 'Chapainawabganj', 'Joypurhat', 'Naogaon', 'Natore', 'Pabna', 'Sirajganj']
            },
            {
                name: 'Khulna',
                districts: ['Khulna', 'Bagerhat', 'Chuadanga', 'Jessore', 'Jhenaidah', 'Kushtia', 'Magura', 'Meherpur', 'Narail', 'Satkhira']
            },
            {
                name: 'Barishal',
                districts: ['Barishal', 'Barguna', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur']
            },
            {
                name: 'Sylhet',
                districts: ['Sylhet', 'Habiganj', 'Moulvibazar', 'Sunamganj']
            },
            {
                name: 'Rangpur',
                districts: ['Rangpur', 'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat', 'Nilphamari', 'Panchagarh', 'Thakurgaon']
            },
            {
                name: 'Mymensingh',
                districts: ['Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur']
            }
        ],
        availableCustomerDistricts: [],
        
        notification: {
            show: false,
            message: '',
            type: 'success'
        },

        async init() {
            await this.loadCustomers();
            await this.loadStats();
            this.filterCustomers();
        },

        onCustomerDivisionChange() {
            // Reset district when division changes
            this.customerForm.district = '';
            
            // Update available districts based on selected division
            const selectedDivision = this.customerDivisions.find(div => div.name === this.customerForm.division);
            this.availableCustomerDistricts = selectedDivision ? selectedDivision.districts : [];
        },

        async loadCustomers() {
            try {
                this.isLoading = true;
                const response = await fetch('/api/admin/customers');
                if (response.ok) {
                    const responseData = await response.json();
                    console.log('Customers API Response:', responseData); // Debug log
                    
                    // Handle paginated response structure
                    if (responseData.success && responseData.data && responseData.data.data) {
                        this.customers = responseData.data.data; // Laravel pagination structure
                        this.totalPages = responseData.data.last_page || 1;
                        this.currentPage = responseData.data.current_page || 1;
                    } else if (responseData.data) {
                        this.customers = Array.isArray(responseData.data) ? responseData.data : [responseData.data];
                    } else {
                        this.customers = [];
                    }
                } else {
                    throw new Error('Failed to load customers');
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                this.showNotification('Failed to load customers', 'error');
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

        filterCustomers() {
            let filtered = [...this.customers];
            
            // Search filter
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(customer => 
                    (customer.name && customer.name.toLowerCase().includes(search)) ||
                    (customer.phone && customer.phone.includes(search)) ||
                    (customer.division && customer.division.toLowerCase().includes(search)) ||
                    (customer.district && customer.district.toLowerCase().includes(search)) ||
                    customer.id.toString().includes(search)
                );
            }
            
            // Status filter
            if (this.filters.status) {
                filtered = filtered.filter(customer => customer.status === this.filters.status);
            }
            
            // Date range filter
            if (this.filters.date_range) {
                const now = new Date();
                let startDate;
                
                switch (this.filters.date_range) {
                    case 'today':
                        startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                        break;
                    case 'week':
                        startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                        break;
                    case 'month':
                        startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        break;
                    case 'year':
                        startDate = new Date(now.getFullYear(), 0, 1);
                        break;
                }
                
                if (startDate) {
                    filtered = filtered.filter(customer => new Date(customer.created_at) >= startDate);
                }
            }
            
            // Order count filter
            if (this.filters.order_count) {
                switch (this.filters.order_count) {
                    case 'new':
                        filtered = filtered.filter(customer => (customer.orders_count || 0) === 0);
                        break;
                    case 'returning':
                        filtered = filtered.filter(customer => (customer.orders_count || 0) >= 1);
                        break;
                    case 'loyal':
                        filtered = filtered.filter(customer => (customer.orders_count || 0) >= 5);
                        break;
                }
            }
            
            this.filteredCustomers = filtered;
            this.totalPages = Math.ceil(filtered.length / this.perPage);
            this.currentPage = 1;
            this.updatePagination();
        },

        updatePagination() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            this.paginatedCustomers = this.filteredCustomers.slice(start, end);
        },

        get startIndex() {
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get endIndex() {
            return Math.min(this.currentPage * this.perPage, this.filteredCustomers.length);
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

        async viewCustomer(customer) {
            try {
                const response = await fetch(`/api/admin/customers/${customer.id}`);
                if (response.ok) {
                    const data = await response.json();
                    this.selectedCustomer = data.data || data;
                    this.showCustomerDetails = true;
                } else {
                    throw new Error('Failed to load customer details');
                }
            } catch (error) {
                this.showNotification('Failed to load customer details', 'error');
            }
        },

        async createCustomer() {
            try {
                this.isLoading = true;
                const response = await fetch('/api/admin/customers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.customerForm)
                });

                if (response.ok) {
                    await this.loadCustomers();
                    this.filterCustomers();
                    this.closeModal();
                    this.showNotification('Customer created successfully!', 'success');
                } else {
                    throw new Error('Failed to create customer');
                }
            } catch (error) {
                this.showNotification('Failed to create customer', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        editCustomer(customer) {
            this.editingCustomer = customer;
            this.customerForm = {
                name: customer.name || '',
                email: customer.email || '',
                phone: customer.phone || '',
                division: customer.division || '',
                district: customer.district || '',
                address: customer.address || '',
                is_active: customer.is_active !== undefined ? customer.is_active : 1
            };
            
            // Update available districts for the selected division
            if (customer.division) {
                const selectedDivision = this.customerDivisions.find(div => div.name === customer.division);
                this.availableCustomerDistricts = selectedDivision ? selectedDivision.districts : [];
            }
            
            this.showEditForm = true;
        },

        async updateCustomer() {
            try {
                this.isLoading = true;
                const response = await fetch(`/api/admin/customers/${this.editingCustomer.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.customerForm)
                });

                if (response.ok) {
                    await this.loadCustomers();
                    this.filterCustomers();
                    this.closeModal();
                    this.showNotification('Customer updated successfully!', 'success');
                } else {
                    throw new Error('Failed to update customer');
                }
            } catch (error) {
                this.showNotification('Failed to update customer', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async toggleStatus(customer) {
            try {
                const newStatus = !customer.is_active;
                const response = await fetch(`/api/admin/customers/${customer.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ...customer, is_active: newStatus })
                });

                if (response.ok) {
                    customer.is_active = newStatus;
                    this.showNotification(`Customer ${newStatus ? 'activated' : 'deactivated'} successfully!`, 'success');
                } else {
                    throw new Error('Failed to update customer status');
                }
            } catch (error) {
                this.showNotification('Failed to update customer status', 'error');
            }
        },

        exportCustomers() {
            // Build query parameters for export
            const params = new URLSearchParams();
            
            if (this.filters.search) {
                params.append('search', this.filters.search);
            }
            
            if (this.filters.status) {
                params.append('status', this.filters.status);
            }
            
            if (this.filters.date_range) {
                // Convert date range to actual dates
                const now = new Date();
                let startDate;
                
                switch (this.filters.date_range) {
                    case 'today':
                        startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                        params.append('date_from', startDate.toISOString().split('T')[0]);
                        params.append('date_to', now.toISOString().split('T')[0]);
                        break;
                    case 'week':
                        startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                        params.append('date_from', startDate.toISOString().split('T')[0]);
                        break;
                    case 'month':
                        startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        params.append('date_from', startDate.toISOString().split('T')[0]);
                        break;
                    case 'year':
                        startDate = new Date(now.getFullYear(), 0, 1);
                        params.append('date_from', startDate.toISOString().split('T')[0]);
                        break;
                }
            }
            
            // Create download link
            const url = `/api/admin/customers/export?${params.toString()}`;
            
            // Create temporary link to trigger download
            const link = document.createElement('a');
            link.href = url;
            link.download = 'customers_export.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            this.showNotification('Customers exported successfully!', 'success');
        },

        closeModal() {
            this.showCreateForm = false;
            this.showEditForm = false;
            this.showCustomerDetails = false;
            this.editingCustomer = null;
            this.selectedCustomer = null;
            this.customerForm = {
                name: '',
                email: '',
                phone: '',
                address: '',
                is_active: 1
            };
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString();
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