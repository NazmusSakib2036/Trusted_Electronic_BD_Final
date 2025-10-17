@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div x-data="dashboard()" x-init="loadData()">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM6 18v-6h8v6H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_products || '0'"></p>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h2a1 1 0 100-2H7z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_orders || '0'"></p>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Customers</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="stats.total_customers || '0'"></p>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="detailedStats.pending_orders || '0'"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Revenue</h3>
            <div class="relative">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Status Distribution</h3>
            <div class="relative">
                <canvas id="orderStatusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Low Stock Products -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
    
        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Low Stock Alert</h3>
                    <a href="/admin/products" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Manage products</a>
                </div>
            </div>
            <div class="p-6">
                <div x-show="!lowStockProducts.length" class="text-gray-500 text-center py-4">
                    No low stock products.
                </div>
                <div class="space-y-3">
                    <template x-for="product in lowStockProducts" :key="product.id">
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900" x-text="product.name"></p>
                                    <p class="text-sm text-gray-500" x-text="'SKU: ' + product.sku"></p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full" 
                                      x-text="product.stock_quantity + ' left'">
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function dashboard() {
    return {
        stats: {},
        detailedStats: {},
        recentOrders: [],
        lowStockProducts: [],
        
        async loadData() {
            try {
                // Load basic dashboard data
                const dashboardResponse = await axios.get('/api/admin/dashboard');
                const data = dashboardResponse.data.data;
                
                this.stats = {
                    total_orders: data.total_orders,
                    total_products: data.total_products,
                    total_customers: data.total_customers,
                    total_categories: data.total_categories
                };
                
                this.recentOrders = data.recent_orders || [];
                this.lowStockProducts = data.low_stock_products || [];
                
                // Load detailed stats
                const statsResponse = await axios.get('/api/admin/dashboard/stats');
                this.detailedStats = statsResponse.data.data;
                
                // Initialize charts
                this.initCharts();
                
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        },
        
        initCharts() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 3000, 5000, 2000, 30000],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '৳' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
            
            // Order Status Chart
            const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
            const statusCounts = this.detailedStats.order_status_counts || {};
            
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(statusCounts),
                    datasets: [{
                        data: Object.values(statusCounts),
                        backgroundColor: [
                            '#FCD34D', // pending
                            '#3B82F6', // processing
                            '#8B5CF6', // shipped
                            '#10B981', // delivered
                            '#EF4444'  // cancelled
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    }
}
</script>
@endpush