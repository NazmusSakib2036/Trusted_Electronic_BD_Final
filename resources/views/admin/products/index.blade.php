@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('content')
<div x-data="products()" x-init="loadProducts()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Products Management</h2>
            <p class="text-gray-600">Manage your product catalog</p>
        </div>
        <a href="/admin/products/create" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" x-model="filters.search" @input.debounce.300ms="loadProducts()" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Search products...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select x-model="filters.category" @change="loadProducts()" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    <template x-for="category in categories" :key="category.id">
                        <option :value="category.id" x-text="category.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select x-model="filters.status" @change="loadProducts()" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                <select x-model="filters.stock" @change="loadProducts()" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Stock</option>
                    <option value="low">Low Stock</option>
                    <option value="out">Out of Stock</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Products List</h3>
                <div class="text-sm text-gray-500" x-show="products.data">
                    Showing <span x-text="products.from || 0"></span> to <span x-text="products.to || 0"></span> 
                    of <span x-text="products.total || 0"></span> results
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="p-8 text-center">
            <div class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading products...
            </div>
        </div>

        <!-- Table -->
        <div x-show="!loading && products.data && products.data.length" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="product in products.data" :key="product.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 relative">
                                        <!-- Show first image if available -->
                                        <template x-if="product.images && product.images.length > 0">
                                            <img :src="product.images[0]" alt="Product image" class="h-10 w-10 rounded object-cover">
                                        </template>
                                        <!-- Fallback placeholder -->
                                        <template x-if="!product.images || product.images.length === 0">
                                            <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </template>
                                        <!-- Multiple images indicator -->
                                        <template x-if="product.images && product.images.length > 1">
                                            <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" 
                                                  x-text="product.images.length"></span>
                                        </template>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="product.name"></div>
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <span x-text="product.short_description ? product.short_description.substring(0, 50) + '...' : ''"></span>
                                            <!-- YouTube video indicator -->
                                            <template x-if="product.youtube_video_url">
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                    </svg>
                                                    Video
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="product.sku"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="product.category?.name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>
                                    <span x-text="'৳' + parseFloat(product.price).toFixed(2)"></span>
                                    <span x-show="product.sale_price" class="text-green-600 ml-2" x-text="'(৳' + parseFloat(product.sale_price).toFixed(2) + ')'"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                                      :class="{
                                          'bg-red-100 text-red-800': product.stock_quantity === 0,
                                          'bg-yellow-100 text-yellow-800': product.stock_quantity > 0 && product.stock_quantity <= 10,
                                          'bg-green-100 text-green-800': product.stock_quantity > 10
                                      }"
                                      x-text="product.stock_quantity">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                                      :class="product.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                      x-text="product.is_active ? 'Active' : 'Inactive'">
                                </span>
                                <span x-show="product.is_featured" class="ml-1 px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    Featured
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a :href="'/admin/products/' + product.id + '/edit'" 
                                       class="text-blue-600 hover:text-blue-900">Edit</a>
                                    <!-- <button @click="toggleFeatured(product)" 
                                            class="text-purple-600 hover:text-purple-900"
                                            x-text="product.is_featured ? 'Unfeature' : 'Feature'"></button> -->
                                    <button @click="deleteProduct(product)" 
                                            class="text-red-600 hover:text-red-900">Delete</button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div x-show="!loading && (!products.data || products.data.length === 0)" class="p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v5"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new product.</p>
            <div class="mt-6">
                <a href="/admin/products/create" 
                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    New Product
                </a>
            </div>
        </div>

        <!-- Pagination -->
        <div x-show="products.data && products.data.length > 0" class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Page <span x-text="products.current_page || 1"></span> of <span x-text="products.last_page || 1"></span>
                </div>
                <div class="flex space-x-2">
                    <button :disabled="!products.prev_page_url" @click="loadPage(products.current_page - 1)"
                            class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <button :disabled="!products.next_page_url" @click="loadPage(products.current_page + 1)"
                            class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> <br> <br> <br> <br> <br> <br> <br> <br>
@endsection

@push('scripts')
<script>
function products() {
    return {
        products: {},
        categories: [],
        loading: false,
        filters: {
            search: '',
            category: '',
            status: '',
            stock: ''
        },
        
        async loadProducts(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({
                    page: page,
                    ...this.filters
                });
                
                const response = await axios.get(`${window.API_BASE}/products?${params}`);
                this.products = response.data.data;
            } catch (error) {
                console.error('Error loading products:', error);
                alert('Error loading products. Please try again.');
            } finally {
                this.loading = false;
            }
        },
        
        async loadPage(page) {
            if (page >= 1 && page <= this.products.last_page) {
                await this.loadProducts(page);
            }
        },
        
        async loadCategories() {
            try {
                const response = await axios.get(`${window.API_BASE}/categories`);
                this.categories = response.data.data;
            } catch (error) {
                console.error('Error loading categories:', error);
            }
        },
        
        async toggleFeatured(product) {
            try {
                const response = await axios.patch(`${window.API_BASE}/products/${product.id}/toggle-featured`);
                product.is_featured = response.data.data.is_featured;
                alert('Product featured status updated successfully');
            } catch (error) {
                console.error('Error updating featured status:', error);
                alert('Error updating featured status. Please try again.');
            }
        },
        
        async deleteProduct(product) {
            if (!confirm(`Are you sure you want to delete "${product.name}"?`)) {
                return;
            }
            
            try {
                await axios.delete(`${window.API_BASE}/products/${product.id}`);
                await this.loadProducts(this.products.current_page);
                alert('Product deleted successfully');
            } catch (error) {
                console.error('Error deleting product:', error);
                alert('Error deleting product. Please try again.');
            }
        },
        
        async init() {
            await this.loadCategories();
            await this.loadProducts();
        }
    }
}
</script>
@endpush