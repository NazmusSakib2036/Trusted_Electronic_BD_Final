@extends('admin.layouts.app')

@section('title', 'Orders Management')

@section('content')
<div x-data="ordersManager()" x-init="init()">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders</h1>
            <p class="text-gray-600">Manage customer orders and payments</p>
        </div>
        <div class="flex gap-3">
            <button @click="exportOrders()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Orders
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.pending_orders"></p>
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
                    <p class="text-sm font-medium text-gray-600">Completed Orders</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.completed_orders"></p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="'৳' + (stats.total_revenue || 0).toLocaleString()"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" 
                       x-model="filters.search" 
                       @input="filterOrders()"
                       placeholder="Order ID or customer..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select x-model="filters.status" 
                        @change="filterOrders()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                <select x-model="filters.payment_status" 
                        @change="filterOrders()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Payments</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="failed">Failed</option>
                    <option value="refunded">Refunded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                <input type="date" 
                       x-model="filters.from_date" 
                       @change="filterOrders()"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                <input type="date" 
                       x-model="filters.to_date" 
                       @change="filterOrders()"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="order in paginatedOrders" :key="order.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900" x-text="'#' + order.id"></div>
                                <div class="text-sm text-gray-500" x-text="order.items_count + ' items'"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                            <span class="text-white font-semibold text-sm" x-text="order.customer_name ? order.customer_name.charAt(0).toUpperCase() : 'G'"></span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="order.customer?.name || 'Guest Customer'"></div>
                                        <div class="text-sm text-gray-500">
                                            <span x-text="(order.customer?.division || 'Unknown') + ', ' + (order.customer?.district || 'Unknown')"></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select :value="order.status" 
                                        @change="updateOrderStatus(order, $event.target.value)"
                                        :class="{
                                            'text-xs font-semibold px-2 py-1 rounded-full border-0': true,
                                            'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                            'bg-blue-100 text-blue-800': order.status === 'processing',
                                            'bg-purple-100 text-purple-800': order.status === 'shipped',
                                            'bg-green-100 text-green-800': order.status === 'delivered',
                                            'bg-red-100 text-red-800': order.status === 'cancelled'
                                        }">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="{
                                    'inline-flex px-2 py-1 text-xs font-semibold rounded-full': true,
                                    'bg-yellow-100 text-yellow-800': order.payment_status === 'pending',
                                    'bg-green-100 text-green-800': order.payment_status === 'completed',
                                    'bg-red-100 text-red-800': order.payment_status === 'failed',
                                    'bg-gray-100 text-gray-800': order.payment_status === 'refunded'
                                }" x-text="order.payment_status ? order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1) : 'Pending'"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span x-text="'৳' + parseFloat(order.total_amount || 0).toFixed(2)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span x-text="formatDate(order.created_at)"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                <button @click="viewOrder(order)" 
                                        class="text-blue-600 hover:text-blue-900">
                                    View
                                </button>
                                <button @click="printInvoice(order)" 
                                        class="text-green-600 hover:text-green-900">
                                    Invoice
                                </button>
                                <button @click="deleteOrder(order)" 
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
                            Showing <span x-text="startIndex"></span> to <span x-text="endIndex"></span> of <span x-text="filteredOrders.length"></span> orders
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

    <!-- Order Details Modal -->
    <div x-show="showOrderDetails" 
         class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
         x-transition>
        <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
                    <button @click="showOrderDetails = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div x-show="selectedOrder">
                    <!-- Order Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Order Information</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Order ID:</span> <span x-text="'#' + (selectedOrder?.id || '')"></span></div>
                                <div><span class="font-medium">Status:</span> <span x-text="selectedOrder?.status || ''"></span></div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">Payment Status:</span> 
                                    <select x-model="selectedOrder.payment_status" 
                                            @change="updatePaymentStatus(selectedOrder)"
                                            class="px-2 py-1 border border-gray-300 rounded text-xs">
                                        <option value="pending">Pending</option>
                                        <option value="completed">Completed</option>
                                        <option value="failed">Failed</option>
                                        <option value="refunded">Refunded</option>
                                    </select>
                                </div>
                                <div><span class="font-medium">Order Date:</span> <span x-text="formatDate(selectedOrder?.created_at)"></span></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Customer Information</h4>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Name:</span> <span x-text="selectedOrder?.customer?.name || 'Guest Customer'"></span></div>
                                <div><span class="font-medium">Phone:</span> <span x-text="selectedOrder?.customer?.phone || 'No phone'"></span></div>
                                <div><span class="font-medium">Division:</span> <span x-text="selectedOrder?.customer?.division || 'Not specified'"></span></div>
                                <div><span class="font-medium">District:</span> <span x-text="selectedOrder?.customer?.district || 'Not specified'"></span></div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">Order Items</h4>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template x-for="item in selectedOrder?.order_items || []" :key="item.id">
                                        <tr>
                                            <td class="px-4 py-2 text-sm" x-text="item.product_name"></td>
                                            <td class="px-4 py-2 text-sm" x-text="item.quantity"></td>
                                            <td class="px-4 py-2 text-sm" x-text="'৳' + parseFloat(item.price || 0).toFixed(2)"></td>
                                            <td class="px-4 py-2 text-sm font-medium" x-text="'৳' + (parseFloat(item.price || 0) * parseInt(item.quantity || 0)).toFixed(2)"></td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-sm font-medium text-right">Total:</td>
                                        <td class="px-4 py-2 text-sm font-bold" x-text="'৳' + parseFloat(selectedOrder?.total_amount || 0).toFixed(2)"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Shipping Address</h4>
                            <div class="text-sm text-gray-600">
                                <template x-if="selectedOrder?.shipping_address">
                                    <div>
                                        <div x-text="selectedOrder.shipping_address.address || 'No address'"></div>
                                        <div x-text="'Postal Code: ' + (selectedOrder.shipping_address.postal_code || 'N/A')"></div>
                                        <div x-text="'Area: ' + (selectedOrder.shipping_address.area || 'N/A')"></div>
                                        <div x-show="selectedOrder.shipping_address.instructions" x-text="'Instructions: ' + selectedOrder.shipping_address.instructions"></div>
                                    </div>
                                </template>
                                <template x-if="!selectedOrder?.shipping_address">
                                    <div>No shipping address</div>
                                </template>
                            </div>
                        </div>
                        <!-- <div>
                            <h4 class="font-medium text-gray-900 mb-3">Billing Address</h4>
                            <div class="text-sm text-gray-600">
                                <div x-text="selectedOrder?.billing_address || 'No billing address'"></div>
                            </div>
                        </div> -->
                    </div>
                </div>
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
function ordersManager() {
    return {
        orders: [],
        filteredOrders: [],
        paginatedOrders: [],
        currentPage: 1,
        perPage: 10,
        totalPages: 1,
        isLoading: false,
        showOrderDetails: false,
        selectedOrder: null,
        
        stats: {
            total_orders: 0,
            pending_orders: 0,
            completed_orders: 0,
            total_revenue: 0
        },
        
        filters: {
            search: '',
            status: '',
            payment_status: '',
            from_date: '',
            to_date: ''
        },
        
        notification: {
            show: false,
            message: '',
            type: 'success'
        },

        async init() {
            await this.loadOrders();
            await this.loadStats();
            this.filterOrders();
        },

        async loadOrders() {
            try {
                this.isLoading = true;
                const response = await fetch('/api/admin/orders');
                if (response.ok) {
                    const responseData = await response.json();
                    console.log('Orders API Response:', responseData); // Debug log
                    
                    // Handle paginated response structure
                    if (responseData.success && responseData.data && responseData.data.data) {
                        this.orders = responseData.data.data; // Laravel pagination structure
                        this.totalPages = responseData.data.last_page || 1;
                        this.currentPage = responseData.data.current_page || 1;
                    } else if (responseData.data) {
                        this.orders = Array.isArray(responseData.data) ? responseData.data : [responseData.data];
                    } else {
                        this.orders = [];
                    }
                } else {
                    throw new Error('Failed to load orders');
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                this.showNotification('Failed to load orders', 'error');
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

        filterOrders() {
            let filtered = [...this.orders];
            
            // Search filter
            if (this.filters.search) {
                const search = this.filters.search.toLowerCase();
                filtered = filtered.filter(order => 
                    order.id.toString().includes(search) ||
                    (order.customer_name && order.customer_name.toLowerCase().includes(search)) ||
                    (order.customer_division && order.customer_division.toLowerCase().includes(search)) ||
                    (order.customer_district && order.customer_district.toLowerCase().includes(search))
                );
            }
            
            // Status filter
            if (this.filters.status) {
                filtered = filtered.filter(order => order.status === this.filters.status);
            }
            
            // Payment status filter
            if (this.filters.payment_status) {
                filtered = filtered.filter(order => order.payment_status === this.filters.payment_status);
            }
            
            // Date filters
            if (this.filters.from_date) {
                filtered = filtered.filter(order => new Date(order.created_at) >= new Date(this.filters.from_date));
            }
            
            if (this.filters.to_date) {
                filtered = filtered.filter(order => new Date(order.created_at) <= new Date(this.filters.to_date));
            }
            
            this.filteredOrders = filtered;
            this.totalPages = Math.ceil(filtered.length / this.perPage);
            this.currentPage = 1;
            this.updatePagination();
        },

        updatePagination() {
            const start = (this.currentPage - 1) * this.perPage;
            const end = start + this.perPage;
            this.paginatedOrders = this.filteredOrders.slice(start, end);
        },

        get startIndex() {
            return (this.currentPage - 1) * this.perPage + 1;
        },

        get endIndex() {
            return Math.min(this.currentPage * this.perPage, this.filteredOrders.length);
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

        async updateOrderStatus(order, newStatus) {
            try {
                const response = await fetch(`/api/admin/orders/${order.id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.ok) {
                    order.status = newStatus;
                    this.showNotification('Order status updated successfully!', 'success');
                } else {
                    throw new Error('Failed to update order status');
                }
            } catch (error) {
                console.error('Status update error:', error);
                this.showNotification('Failed to update order status', 'error');
            }
        },

        async updatePaymentStatus(order) {
            try {
                const response = await fetch(`/api/admin/orders/${order.id}/payment-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_status: order.payment_status
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    // Update the order in the list
                    const orderIndex = this.orders.findIndex(o => o.id === order.id);
                    if (orderIndex !== -1) {
                        this.orders[orderIndex].payment_status = order.payment_status;
                    }
                    this.filterOrders();
                    this.showNotification('Payment status updated successfully!', 'success');
                } else {
                    throw new Error('Failed to update payment status');
                }
            } catch (error) {
                console.error('Payment status update error:', error);
                this.showNotification('Failed to update payment status', 'error');
            }
        },

        async viewOrder(order) {
            try {
                const response = await fetch(`/api/admin/orders/${order.id}`);
                if (response.ok) {
                    const data = await response.json();
                    this.selectedOrder = data.data || data;
                    this.showOrderDetails = true;
                } else {
                    throw new Error('Failed to load order details');
                }
            } catch (error) {
                this.showNotification('Failed to load order details', 'error');
            }
        },

        printInvoice(order) {
            // Open invoice in new window
            window.open(`/api/admin/orders/${order.id}/invoice`, '_blank');
        },

        async deleteOrder(order) {
            if (!confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch(`/api/admin/orders/${order.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    await this.loadOrders();
                    this.filterOrders();
                    this.showNotification('Order deleted successfully!', 'success');
                } else {
                    throw new Error('Failed to delete order');
                }
            } catch (error) {
                this.showNotification('Failed to delete order', 'error');
            }
        },

        exportOrders() {
            // Build query parameters for export
            const params = new URLSearchParams();
            
            if (this.filters.status) {
                params.append('status', this.filters.status);
            }
            
            if (this.filters.payment_status) {
                params.append('payment_status', this.filters.payment_status);
            }
            
            if (this.filters.date_from) {
                params.append('date_from', this.filters.date_from);
            }
            
            if (this.filters.date_to) {
                params.append('date_to', this.filters.date_to);
            }
            
            // Create download link
            const url = `/api/admin/orders/export?${params.toString()}`;
            
            // Create temporary link to trigger download
            const link = document.createElement('a');
            link.href = url;
            link.download = 'orders_export.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            this.showNotification('Orders exported successfully!', 'success');
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
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