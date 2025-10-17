@extends('frontend.layouts.app')

@section('title', 'Trusted Electronics BD - Home')

@section('content')
    <div x-data="homeStore()" x-init="init()">

        <section class="bg-gray-50 py-3 sm:py-6 shadow-inner mt-4 sm:mt-6">
            <div class="max-w-4xl mx-auto">

                <div class="overflow-hidden">
                    <div
                        class="flex flex-nowrap gap-x-4 overflow-x-auto pb-4 custom-scrollbar px-4 sm:px-6 lg:px-8 snap-x snap-mandatory">

                        <div class="category-card flex-shrink-0 cursor-pointer group snap-start w-1/3 sm:w-auto flex items-center justify-center"
                            style="margin-left:0.5rem;margin-right:0.5rem;" @click="selectCategory(null)">
                            <div :class="selectedCategory === null ? 'border-2 border-red-500 shadow-lg bg-white' : 'border border-gray-200 bg-white hover:bg-gray-50'"
                                class="relative w-24 h-32 sm:w-32 sm:h-40 rounded-lg overflow-hidden shadow-sm flex items-center justify-center">

                                <div class="w-full h-full flex items-center justify-center">
                                    <h3
                                        :class="selectedCategory === null ? 'text-red-600 font-bold text-sm sm:text-xl' : 'text-gray-700 font-bold text-sm sm:text-xl'"
                                        x-text="$store.translationStore.translate('all_products')">
                                    </h3>
                                </div>
                            </div>
                        </div>

                        {{-- Dynamic small category cards --}}
                        <template x-for="category in categories" :key="category.id">

                            <div class="category-card flex-shrink-0 cursor-pointer group snap-start w-1/3 sm:w-auto"
                                style="margin-left:0.5rem;margin-right:0.5rem;" @click="selectCategory(category.id)">
                                <div :class="selectedCategory === category.id ? 'shadow-lg bg-white' : 'border border-gray-200 bg-white'"
                                    class="relative w-24 h-32 sm:w-32 sm:h-40 rounded-lg overflow-hidden shadow-sm">

                                    <img x-show="category.image" :src="category.image" :alt="category.name"
                                        class="w-full h-full object-contain">

                                    <div x-show="!category.image"
                                        class="w-full h-full flex items-center justify-center bg-blue-500 text-white font-extrabold text-lg">
                                        <span x-text="category.name.charAt(0).toUpperCase()"></span>
                                    </div>

                                    <div
                                        class="absolute bottom-0 left-0 right-0 py-5 bg-gradient-to-t from-black via-black/95 to-black/60 text-center">
                                        <h3 class="text-white font-black text-sm sm:text-base line-clamp-1 drop-shadow-2xl"
                                            style="text-shadow: 8px 8px 20px rgba(0,0,0,1), 5px 5px 15px rgba(0,0,0,1), -5px -5px 15px rgba(0,0,0,1), 3px 3px 8px rgba(0,0,0,1), -3px -3px 8px rgba(0,0,0,1), 0px 0px 20px rgba(0,0,0,1), 1px 1px 5px rgba(0,0,0,1), -1px -1px 5px rgba(0,0,0,1);"
                                            x-text="category.name"></h3>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <style>
            /* For Webkit browsers (Chrome, Safari) */
            .custom-scrollbar::-webkit-scrollbar {
                height: 8px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background-color: rgba(0, 0, 0, 0.1);
                border-radius: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                background-color: rgba(0, 0, 0, 0.2);
            }

            .custom-scrollbar::-webkit-scrollbar-track {
                background: transparent;
            }
        </style>

        {{-- The rest of your content (product listing, scripts) remains the same --}}

        <section class="py-4 sm:py-8">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                


            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
    <div class="flex flex-row items-center space-x-1 sm:space-x-4 w-full"> 
        
        <div class="relative w-full flex-1">
            <input type="text" x-model="searchQuery" @input="filterProducts()"
                :placeholder="$store.translationStore.translate('search_placeholder')"
                class="w-full border border-gray-300 rounded px-3 py-2 pl-10 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <div class="flex items-center flex-shrink-0">
            <span class="text-sm text-gray-600 whitespace-nowrap hidden sm:inline">Sort by:</span> 
            
            <select x-model="sortBy" @change="sortProducts()"
            class="border border-gray-300 rounded px-1 py-2 text-xs sm:px-3 sm:py-12 sm:text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-auto">
            <option value="default">Default</option>
            <option value="price_low">Low</option>
            <option value="price_high">High</option>
            <option value="name">Name</option>
            <option value="newest">New</option>
            </select>
        </div>
    </div>
</div>

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
                        <span class="text-lg">Loading products...</span>
                    </div>
                </div>

                <div x-show="!loading" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden border">


                            <!-- <div x-show="product.sale_price" class="relative">
                                <div class="absolute top-2 left-2 z-10">
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded"
                                        x-text="'Save ' + (product.price - product.sale_price) + ' BDT'">
                                    </span>
                                </div>
                            </div> -->

                            <div class="aspect-square bg-gray-100 relative overflow-hidden cursor-pointer"
                                @click="viewProduct(product.id)">
                                <img x-show="product.images && product.images.length > 0" :src="product.images[0]"
                                    :alt="product.name"
                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                    src="/storage/products/product_1758953011_68d77e33b1376.jpg"
                                    alt="20000mAh Power Bank Case – Without Battery">
                                <div x-show="!product.images || product.images.length === 0"
                                    class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>

                            <div class="p-4">
                                <h3 class="font-medium text-gray-900 mb-2 line-clamp-2 cursor-pointer hover:text-blue-600 text-sm sm:text-base"
                                    @click="viewProduct(product.id)" x-text="product.name"></h3>

                                <div class="mb-3">
                                    <div x-show="product.sale_price"
                                        class="flex flex-col sm:flex-row sm:items-center sm:space-x-2">
                                        <span class="text-sm sm:text-lg font-bold text-green-600"
                                            x-text="'Tk ' + product.sale_price"></span>
                                        <!-- <span class="text-xs sm:text-sm text-gray-500 line-through mt-1 sm:mt-0"
                                            x-text="'Tk ' + product.price"></span> -->
                                    </div>
                                    <div x-show="!product.sale_price">
                                        <span class="text-sm sm:text-lg font-bold text-gray-900"
                                            x-text="'Tk ' + product.price"></span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <button @click="addToCart(product)" :disabled="product.stock_quantity === 0"
                                        :class="product.stock_quantity === 0 ? 
                                                    'w-full bg-gray-300 text-gray-500 text-xs py-2 px-3 rounded cursor-not-allowed' : 
                                                    'w-full bg-gray-200 hover:bg-gray-300 text-gray-800 text-xs py-2 px-3 rounded transition-colors'">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6" />
                                        </svg>
                                        <span
                                            x-text="product.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('add_to_cart')"></span>
                                    </button>
                                    <button @click="buyNow(product)" :disabled="product.stock_quantity === 0"
                                        :class="product.stock_quantity === 0 ? 
                                                    'w-full bg-gray-400 text-gray-500 text-xs py-2 px-3 rounded cursor-not-allowed' : 
                                                    'w-full bg-purple-600 hover:bg-purple-700 text-white text-xs py-2 px-3 rounded transition-colors'">
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
                    </template>
                </div>

                <div x-show="!loading && filteredProducts.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v5" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                </div>
            </div>
        </section>












        <div x-show="$store.appStore && $store.appStore.cartCount > 0" x-cloak
            class="fixed inset-x-0 bottom-4 z-50">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <a href="/cart" role="button" aria-label="View cart and checkout"
                    class="pointer-events-auto no-underline cursor-pointer block w-full sm:w-[20%] mx-auto">
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
    </div>

    @push('scripts')
        <script>
            function homeStore() {
                return {
                    products: [],
                    filteredProducts: [],
                    categories: [],
                    loading: true,
                    selectedCategory: null,
                    sortBy: 'default',
                    searchQuery: '',
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
                                console.log('Home page received navigationSearch event:', event.detail);
                                this.searchQuery = event.detail.searchQuery;
                                this.filterProducts();
                            });

                            await this.loadCategories();
                            await this.loadProducts();
                            this.filterProducts();
                        } catch (error) {
                            console.error('Error during initialization:', error);
                            // Ensure arrays are initialized even if API fails
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
                            // Handle different response formats
                            if (data.success && Array.isArray(data.data)) {
                                this.categories = data.data;
                            } else {
                                this.categories = [];
                            }
                            console.log('Loaded categories:', this.categories);
                        } catch (error) {
                            console.error('Error loading categories:', error);
                            this.categories = []; // Ensure it's always an array
                        }
                    },

                    async loadProducts() {
                        try {
                            const response = await fetch(`${window.API_BASE}/products`);
                            const data = await response.json();
                            // Handle paginated response
                            if (data.success && data.data && data.data.data) {
                                this.products = data.data.data; // Get products from pagination data
                            } else if (data.success && Array.isArray(data.data)) {
                                this.products = data.data; // Direct array response
                            } else {
                                this.products = []; // Fallback to empty array
                            }
                            console.log('Loaded products:', this.products);
                        } catch (error) {
                            console.error('Error loading products:', error);
                            this.products = []; // Ensure it's always an array
                        }
                    },

                    selectCategory(categoryId) {
                        this.selectedCategory = categoryId;
                        this.filterProducts();
                    },

                    filterProducts() {
                        // Ensure products is always an array
                        if (!Array.isArray(this.products)) {
                            this.products = [];
                        }

                        let filtered = [...this.products];

                        // Filter by category
                        if (this.selectedCategory) {
                            filtered = filtered.filter(product => product.category_id === this.selectedCategory);
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
                        // Ensure filteredProducts is always an array
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
                                // Default sorting
                                break;
                        }
                    },

                    viewProduct(productId) {
                        window.location.href = `/product/${productId}`;
                    },

                    addToCart(product) {
                        try {
                            // Check stock availability
                            if (product.stock_quantity === 0) {
                                this.showNotification('Product is out of stock', 'error');
                                return;
                            }

                            // Use global app store
                            const success = this.$store.appStore.addToCart(product);
                            if (success) {
                                this.showNotification(`${product.name} added to cart!`, 'success');
                            } else {
                                this.showNotification('Failed to add product to cart', 'error');
                            }
                        } catch (error) {
                            console.error('Error adding to cart:', error);
                            this.showNotification('Error adding product to cart', 'error');
                        }
                    },

                    buyNow(product) {
                        try {
                            // Check stock availability
                            if (product.stock_quantity === 0) {
                                this.showNotification('Product is out of stock', 'error');
                                return;
                            }

                            // Add to cart and redirect to checkout
                            const success = this.$store.appStore.addToCart(product);
                            if (success) {
                                window.location.href = '/checkout';
                            } else {
                                this.showNotification('Failed to add product to cart', 'error');
                            }
                        } catch (error) {
                            console.error('Error during buy now:', error);
                            this.showNotification('Error processing purchase', 'error');
                        }
                    },

                    showNotification(message, type = 'success') {
                        this.notification = {
                            show: true,
                            message,
                            type
                        };
                        setTimeout(() => {
                            this.notification.show = false;
                        }, 3000);
                    }
                }
            }
        </script>
    @endpush
@endsection