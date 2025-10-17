@extends('frontend.layouts.app')

@section('title', 'All Products - Trusted Electronics BD')

@section('content')
    <div x-data="productsStore()" x-init="init()">
        <nav class="bg-gray-50 py-4">
            {{-- **Fixed:** Using max-w-4xl container to match home page style --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-8">
                <div class="flex items-center space-x-2 text-sm">
                    <a href="/" class="text-blue-600 hover:underline" x-text="$store.translationStore.translate('home')"></a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-700" x-text="$store.translationStore.translate('products')"></span>
                </div>
            </div>
        </nav>

        <section class="bg-white py-8 border-b"> 
            {{-- **Fixed:** Using max-w-4xl container to match home page style --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2 text-center" x-text="$store.translationStore.translate('all_products')"></h1>
                <p class="text-gray-600 text-center" x-text="`${filteredProducts.length} ${$store.translationStore.translate('products_found')}`"></p><br>
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">

                    <div class="grid grid-cols-2 gap-4 mt-4 md:mt-0 md:grid-cols-4 md:gap-4">
                        {{-- Search Input --}}
                        <div class="relative">
                            <input type="text" x-model="searchQuery" @input="filterProducts()"
                                :placeholder="$store.translationStore.translate('search_placeholder')"
                                class="w-full border border-gray-300 rounded px-4 py-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- Category Filter --}}
                        <select x-model="selectedCategory" @change="filterProducts()"
                            class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" x-text="$store.translationStore.translate('all_categories')"></option>
                            <template x-for="category in categories" :key="category.id">
                                <option :value="category.id" x-text="category.name"></option>
                            </template>
                        </select>

                        {{-- Sort By --}}
                        <select x-model="sortBy" @change="sortProducts()"
                            class="border border-gray-300 rounded px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="default" x-text="$store.translationStore.translate('default')"></option>
                            <option value="price_low" x-text="$store.translationStore.translate('price_low_to_high')"></option>
                            <option value="price_high" x-text="$store.translationStore.translate('price_high_to_low')"></option>
                            <option value="name" x-text="$store.translationStore.translate('name_a_to_z')"></option>
                            <option value="newest" x-text="$store.translationStore.translate('newest_first')"></option>
                        </select>

                        {{-- View Mode Toggle --}}
                        <div class="flex border border-gray-300 rounded overflow-hidden">
                            <button @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'"
                                class="px-3 py-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                </svg>
                            </button>
                            <button @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700'"
                                class="px-3 py-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-12 md:py-16">
            {{-- **Fixed:** max-w-4xl mx-auto for centered layout matching the home page --}}
            <div class="max-w-4xl mx-auto px-4 sm:px-8">

                <div x-show="loading" class="text-center py-12">
                    <div class="inline-flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="text-lg" x-text="$store.translationStore.translate('loading_products')"></span>
                    </div>
                </div>

                <div x-show="!loading && viewMode === 'grid'" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                    <template x-for="product in filteredProducts" :key="product.id">
                        {{-- PRODUCT CARD FROM HOME PAGE --}}
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden border">


                            <!-- <div x-show="product.sale_price" class="relative">
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded"
                                        x-text="$store.translationStore.translate('save') + ' ' + (product.price - product.sale_price) + ' BDT'">
                                    </span>
                                </div>
                            </div> -->

                            <div class="aspect-square bg-gray-100 relative overflow-hidden cursor-pointer"
                                @click="viewProduct(product.id)">
                                <img x-show="product.images && product.images.length > 0" :src="product.images[0]"
                                    :alt="product.name"
                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                <div x-show="!product.images || product.images.length === 0"
                                    class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            <div class="p-3 sm:p-4">
                                <h3 class="font-medium text-gray-900 mb-1 line-clamp-2 cursor-pointer hover:text-blue-600 text-sm sm:text-base"
                                    @click="viewProduct(product.id)" x-text="product.name"></h3>

                                {{-- Price / Discount: show original price first then discounted price on next line for mobile stacking --}}
                                <div class="mb-2">
                                    <template x-if="product.sale_price">
                                        <div>
                                            <!-- <div class="text-xs sm:text-sm text-gray-500 line-through"
                                                x-text="'Tk ' + product.price"></div> -->
                                            <div class="text-sm sm:text-lg font-bold text-green-600"
                                                x-text="'Tk ' + product.sale_price"></div>
                                        </div>
                                    </template>
                                    <template x-if="!product.sale_price">
                                        <div class="text-sm sm:text-lg font-bold text-gray-900"
                                            x-text="'Tk ' + product.price"></div>
                                    </template>
                                </div>

                                <div class="space-y-2">
                                    <button @click="addToCart(product)" :disabled="product.stock_quantity === 0"
                                        :class="product.stock_quantity === 0 ? 
                                                            'w-full bg-gray-300 text-gray-500 text-xs sm:text-sm py-2 px-4 rounded cursor-not-allowed' : 
                                                            'w-full bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs sm:text-sm py-2 px-4 rounded transition-colors'">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6" />
                                        </svg>
                                        <span x-text="product.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('add_to_cart')"></span>
                                    </button>
                                    <button @click="buyNow(product)" :disabled="product.stock_quantity === 0"
                                        :class="product.stock_quantity === 0 ? 
                                                            'w-full bg-gray-400 text-gray-500 text-xs sm:text-sm py-2 px-4 rounded cursor-not-allowed' : 
                                                            'w-full bg-purple-600 hover:bg-purple-700 text-white text-xs sm:text-sm py-2 px-4 rounded transition-colors'">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span x-text="product.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('buy_now')"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- END PRODUCT CARD --}}
                    </template>
                </div>

                <div x-show="!loading && viewMode === 'list'" class="space-y-6">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="bg-white rounded-lg shadow-xl border p-6 hover:shadow-2xl transition-shadow">
                            <div class="flex flex-col md:flex-row gap-6">
                                <div class="w-full md:w-48 h-48 bg-gray-100 rounded-lg overflow-hidden cursor-pointer flex-shrink-0"
                                    @click="viewProduct(product.id)">
                                    <img x-show="product.images && product.images.length > 0" :src="product.images[0]"
                                        :alt="product.name"
                                        class="w-full h-full object-contain sm:object-cover hover:scale-110 transition-transform duration-300">
                                    <div x-show="!product.images || product.images.length === 0"
                                        class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:justify-between h-full">
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-gray-900 mb-2 cursor-pointer hover:text-blue-600"
                                                @click="viewProduct(product.id)" x-text="product.name"></h3>

                                            <p x-show="product.description" class="text-gray-600 mb-4 line-clamp-3"
                                                x-text="product.dxescription"></p>

                                            <!-- <div class="mb-4">
                                                <div x-show="(product.stock_quantity ?? 0) > 0"
                                                    class="text-sm text-green-600 font-medium">
                                                    <span x-text="$store.translationStore.translate('stock') + ': ' + product.stock_quantity + ' ' + $store.translationStore.translate('available')"></span>
                                                </div>
                                                <div x-show="(product.stock_quantity ?? 0) === 0"
                                                    class="text-sm text-red-600 font-medium"
                                                    x-text="$store.translationStore.translate('out_of_stock')">
                                                </div>
                                            </div> -->
                                        </div>

                                        <div class="flex flex-col justify-between md:ml-8 md:text-right min-w-[200px]">
                                            <div class="mb-4">
                                                <div x-show="product.sale_price" class="space-y-1">
                                                    <div class="text-3xl font-extrabold text-red-600"
                                                        x-text="'Tk ' + product.sale_price"></div>
                                                    <!-- <div class="text-lg text-gray-500 line-through"
                                                        x-text="'Tk ' + product.price"></div> -->
                                                    <!-- <div class="text-sm text-red-600"
                                                        x-text="$store.translationStore.translate('save_tk') + ' ' + (product.price - product.sale_price)"></div> -->
                                                </div>
                                                <div x-show="!product.sale_price">
                                                    <div class="text-3xl font-extrabold text-gray-900"
                                                        x-text="'Tk ' + product.price"></div>
                                                </div>
                                            </div>

                                            <div class="space-y-3">
                                                <button @click="addToCart(product)" :disabled="product.stock_quantity === 0"
                                                    :class="product.stock_quantity === 0 ? 
                                                                    'w-full bg-gray-300 text-gray-500 py-3 px-6 rounded cursor-not-allowed' : 
                                                                    'w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold py-3 px-6 rounded transition-colors'">
                                                    <span
                                                        x-text="product.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('add_to_cart')"></span>
                                                </button>
                                                <button @click="buyNow(product)" :disabled="product.stock_quantity === 0"
                                                    :class="product.stock_quantity === 0 ? 
                                                                    'w-full bg-gray-400 text-gray-500 py-3 px-6 rounded cursor-not-allowed' : 
                                                                    'w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-6 rounded transition-colors'">
                                                    <span x-text="product.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('buy_now')"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="!loading && filteredProducts.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v5" />
                    </svg>
                    <h3 class="mt-2 text-xl font-medium text-gray-900" x-text="$store.translationStore.translate('no_products_found')"></h3>
                    <p class="mt-1 text-base text-gray-500" x-text="$store.translationStore.translate('adjust_search')"></p>
                </div>
            </div>
        </section>

        <div x-show="notification.show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2" class="fixed bottom-4 right-4 z-50">
            <div :class="{
                        'bg-green-500': notification.type === 'success',
                        'bg-red-500': notification.type === 'error',
                        'bg-blue-500': notification.type === 'info'
                    }" class="text-white px-6 py-4 rounded-lg shadow-lg">
                <p x-text="notification.message"></p>
            </div>
        </div>

        <!-- Floating Cart Button -->
        <div x-show="$store.appStore && $store.appStore.cartCount > 0" x-cloak
            class="fixed inset-x-0 bottom-4 z-50">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <a href="/cart" role="button" aria-label="View cart and checkout"
                    class="pointer-events-auto no-underline cursor-pointer block sm:w-[20%] w-full mx-auto">
                    <div
                        class="relative flex items-center bg-red-600 text-white rounded-full py-3 shadow-lg transform transition duration-200 hover:scale-105 w-full px-6 mx-4 sm:mx-0">
                        <!-- Cart count circle (desktop) -->
                        <div
                            class="absolute -left-3 top-1/2 transform -translate-y-1/2 bg-white text-red-600 w-8 h-8 rounded-full hidden sm:flex items-center justify-center font-semibold shadow-md">
                            <span x-text="$store.appStore.cartCount"></span>
                        </div>

                        <!-- Cart text and count (mobile) -->
                        <div class="w-full flex items-center justify-center sm:justify-start sm:ml-4">
                            <span
                                class="sm:hidden bg-white text-red-600 w-6 h-6 rounded-full flex items-center justify-center font-bold mr-3 text-xs"
                                x-text="$store.appStore.cartCount"></span>

                            <span class="text-sm font-medium sm:text-base">
                                View your cart (BDT
                                <span
                                    x-text="$store.appStore.getCartTotal() ? $store.appStore.getCartTotal().toFixed(0) : 0"></span>)
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Alpine.js logic remains the same (productsStore function)
            function productsStore() {
                return {
                    products: [],
                    filteredProducts: [],
                    categories: [],
                    loading: true,
                    selectedCategory: '',
                    sortBy: 'default',
                    searchQuery: '',
                    viewMode: 'grid', // Default to grid view to match home page look
                    notification: {
                        show: false,
                        message: '',
                        type: 'success'
                    },

                    async init() {
                        try {
                            // Get search query from URL parameters
                            const urlParams = new URLSearchParams(window.location.search);
                            const searchParam = urlParams.get('search');
                            if (searchParam) {
                                this.searchQuery = decodeURIComponent(searchParam);
                            }

                            // Listen for navigation search events
                            window.addEventListener('navigationSearch', (event) => {
                                this.searchQuery = event.detail.searchQuery;
                                this.filterProducts();
                            });

                            await this.loadCategories();
                            await this.loadProducts();
                            this.filterProducts();
                        } catch (error) {
                            console.error('Error during initialization:', error);
                            this.categories = this.categories || [];
                            this.products = this.products || [];
                            this.filteredProducts = this.filteredProducts || [];
                        } finally {
                            this.loading = false;
                        }
                    },

                    async loadCategories() {
                        try {
                            const response = await fetch(`${window.API_BASE}/categories`);
                            const data = await response.json();
                            if (data.success && Array.isArray(data.data)) {
                                this.categories = data.data;
                            } else {
                                this.categories = [];
                            }
                        } catch (error) {
                            this.categories = [];
                        }
                    },

                    async loadProducts() {
                        try {
                            const response = await fetch(`${window.API_BASE}/products`);
                            const data = await response.json();
                            if (data.success && data.data && data.data.data) {
                                this.products = data.data.data;
                            } else if (data.success && Array.isArray(data.data)) {
                                this.products = data.data;
                            } else {
                                this.products = [];
                            }
                        } catch (error) {
                            this.products = [];
                        }
                    },

                    filterProducts() {
                        if (!Array.isArray(this.products)) {
                            this.products = [];
                        }

                        let filtered = [...this.products];

                        // Filter by category
                        if (this.selectedCategory) {
                            filtered = filtered.filter(product => product.category_id == this.selectedCategory);
                        }

                        // Filter by search query
                        if (this.searchQuery && this.searchQuery.trim() !== '') {
                            const query = this.searchQuery.toLowerCase().trim();
                            filtered = filtered.filter(product => {
                                return product.name.toLowerCase().includes(query) ||
                                    product.description?.toLowerCase().includes(query) ||
                                    product.short_description?.toLowerCase().includes(query) ||
                                    product.sku.toLowerCase().includes(query);
                            });
                        }

                        this.filteredProducts = filtered;
                        this.sortProducts();
                    },

                    sortProducts() {
                        if (!Array.isArray(this.filteredProducts)) {
                            this.filteredProducts = [];
                            return;
                        }

                        switch (this.sortBy) {
                            case 'price_low':
                                this.filteredProducts.sort((a, b) => (a.sale_price || a.price) - (b.sale_price || b.price));
                                break;
                            case 'price_high':
                                this.filteredProducts.sort((a, b) => (b.sale_price || b.price) - (a.sale_price || a.price));
                                break;
                            case 'name':
                                this.filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
                                break;
                            case 'newest':
                                this.filteredProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                                break;
                            default:
                                break;
                        }
                    },

                    viewProduct(productId) {
                        window.location.href = `/product/${productId}`;
                    },

                    addToCart(product) {
                        if (product.stock_quantity === 0) {
                            this.showNotification('Product is out of stock', 'error');
                            return;
                        }

                        this.$store.appStore.addToCart(product);
                        this.showNotification(`${product.name} added to cart!`, 'success');
                    },

                    buyNow(product) {
                        if (product.stock_quantity === 0) {
                            this.showNotification('Product is out of stock', 'error');
                            return;
                        }

                        this.$store.appStore.addToCart(product);
                        window.location.href = '/checkout';
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
    @endpush
@endsection