@extends('frontend.layouts.app')

@section('title', 'Product Details - Trusted Electronics BD')
@section('content')
    <div x-data="productDetailStore()" x-init="init()">
        <div x-show="loading" class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <svg class="animate-spin mx-auto h-12 w-12 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-4 text-lg text-gray-600" x-text="$store.translationStore.translate('loading_product_details')"></p>
            </div>
        </div>

        <div x-show="!loading && product" class="min-h-screen">
            <nav class="bg-gray-50 py-4">
                <div class="container mx-auto px-4">
                    <div class="flex items-center space-x-2 text-sm">
                        <a href="/" class="text-blue-600 hover:underline" x-text="$store.translationStore.translate('home')"></a>
                        <span class="text-gray-400">/</span>
                        <a href="/products" class="text-blue-600 hover:underline" x-text="$store.translationStore.translate('products')"></a>
                        <span class="text-gray-400">/</span>
                        <span class="text-gray-700" x-text="product?.name"></span>
                    </div>
                </div>
            </nav>

            <section class="py-8">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <div class="space-y-4">


                            <!-- main image or video display -->

                            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden relative z-30">
                                
                                <div x-show="!isVideoSelected" class="w-full h-full relative z-40">
                                    <img x-show="selectedImage" :src="selectedImage" :alt="product?.name"
                                         class="w-full h-full object-cover">
                                    
                                    <div x-show="!selectedImage"
                                         class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>

                                <div x-show="isVideoSelected" class="w-full h-full relative z-30 overflow-hidden">
                                    <iframe x-show="videoEmbedUrl" :src="videoEmbedUrl" :key="videoEmbedUrl" class="w-full h-full"
                                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                    <div x-show="!videoEmbedUrl" class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                            </div>


                            <!-- image & video thumbnails -->

                            <div class="grid grid-cols-4 gap-2">
                                <template x-if="getYouTubeThumbnail()">
                                    <div class="aspect-square bg-gray-100 rounded overflow-hidden cursor-pointer border-2 relative group"
                                        :class="isVideoSelected ? 'border-blue-500' : 'border-transparent'"
                                        @click="selectVideo(true)">
                                        <img :src="getYouTubeThumbnail()" :alt="`${product?.name} - Video Thumbnail`"
                                            class="w-full h-full object-cover group-hover:opacity-75 transition-opacity">
                                        <svg class="absolute inset-0 m-auto w-12 h-12 text-white opacity-0 group-hover:opacity-0 transition-opacity" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M6.3 5.4C8.7 3.5 12 1.9 12 1.9s3.3 1.6 5.7 3.5c2.4 1.9 4 4.1 4 6.6 0 3.9-3.2 7-7 7H7c-3.9 0-7-3.1-7-7 0-2.5 1.6-4.7 4-6.6zM15 12l-6 3V9l6 3z"/>
                                        </svg>
                                    </div>
                                </template>
                                
                                <template x-for="(image, index) in product?.images" :key="index">
                                    <div class="aspect-square bg-gray-100 rounded overflow-hidden cursor-pointer border-2"
                                        :class="selectedImage === image && !isVideoSelected ? 'border-blue-500' : 'border-transparent'"
                                        @click="selectImage(image)">
                                        <img :src="image" :alt="`${product?.name} - Image ${index + 1}`"
                                            class="w-full h-full object-cover">
                                    </div>
                                </template>
                            </div>

                        </div>

                        <div class="space-y-6">
                            <div>
                                <div x-show="product?.category" class="text-sm text-blue-600 mb-2">
                                    <span x-text="product?.category?.name"></span>
                                </div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-4" x-text="product?.name"></h1>
                            </div>

                            <div class="border-b pb-6">
                                <div x-show="product?.sale_price" class="space-y-2">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-3xl font-bold text-green-600"
                                            x-text="'৳' + product?.sale_price"></span>
                                    </div>
                                </div>
                                <div x-show="!product?.sale_price">
                                    <span class="text-3xl font-bold text-gray-900" x-text="'৳' + product?.price"></span>
                                </div>
                            </div>

                            <div class="border-b pb-6">
                                <h3 class="text-lg font-semibold mb-3" x-text="$store.translationStore.translate('quantity_label')"></h3>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center border border-gray-300 rounded">
                                        <button @click="quantity = Math.max(1, quantity - 1)"
                                            :disabled="product?.stock_quantity === 0"
                                            class="px-3 py-2 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                            -
                                        </button>
                                        <span class="px-4 py-2 border-l border-r border-gray-300 min-w-[3rem] text-center"
                                            x-text="quantity"></span>
                                        <button @click="quantity = Math.min(product?.stock_quantity, quantity + 1)"
                                            :disabled="product?.stock_quantity === 0"
                                            class="px-3 py-2 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                                            +
                                        </button>
                                    </div>
                                    <div x-show="product?.stock_quantity === 0" class="text-red-600 font-medium"
                                        x-text="$store.translationStore.translate('out_of_stock')">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <button @click="addToCart()" :disabled="product?.stock_quantity === 0"
                                    :class="product?.stock_quantity === 0 ? 
                                            'w-full bg-gray-300 text-gray-500 py-4 px-6 rounded-lg font-semibold cursor-not-allowed' : 
                                            'w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg font-semibold transition-colors'">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6" />
                                    </svg>
                                    <span x-text="product?.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('add_to_cart')"></span>
                                </button>
                                <button @click="buyNow()" :disabled="product?.stock_quantity === 0"
                                    :class="product?.stock_quantity === 0 ? 
                                            'w-full bg-gray-400 text-gray-500 py-4 px-6 rounded-lg font-semibold cursor-not-allowed' : 
                                            'w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg font-semibold transition-colors'">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    <span x-text="product?.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('buy_now')"></span>
                                </button>
                            </div>

                            <!-- <div class="border-t pt-6">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="$store.translationStore.translate('free_shipping')"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="$store.translationStore.translate('days_return')"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="$store.translationStore.translate('warranty_included')"></span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="$store.translationStore.translate('secure_payment')"></span>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <div x-show="product?.description" class="bg-white rounded-lg shadow-md p-8 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6" x-text="$store.translationStore.translate('product_description')"></h2>
                        <div class="prose max-w-none text-gray-700" x-html="product?.description"></div>
                    </div>

                    <div x-show="relatedProducts.length > 0" class="bg-white rounded-lg shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6" x-text="$store.translationStore.translate('related_products')"></h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <template x-for="relatedProduct in relatedProducts" :key="relatedProduct.id">
                                <div
                                    class="product-card bg-white rounded-lg border overflow-hidden hover:shadow-lg transition-shadow">
                                    <div class="aspect-square bg-gray-100 relative overflow-hidden cursor-pointer"
                                        @click="window.location.href = `/product/${relatedProduct.id}`">
                                        <img x-show="relatedProduct.images && relatedProduct.images.length > 0"
                                            :src="relatedProduct.images[0]" :alt="relatedProduct.name"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                        <div x-show="!relatedProduct.images || relatedProduct.images.length === 0"
                                            class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <h3 class="font-medium text-gray-900 mb-2 line-clamp-2 cursor-pointer hover:text-blue-600"
                                            @click="window.location.href = `/product/${relatedProduct.id}`"
                                            x-text="relatedProduct.name"></h3>

                                        <div class="mb-3">
                                            <div x-show="relatedProduct.sale_price" class="flex items-center space-x-2">
                                                <span class="text-lg font-bold text-green-600"
                                                    x-text="'TK ' + relatedProduct.sale_price"></span>
                                            </div>
                                            <div x-show="!relatedProduct.sale_price">
                                                <span class="text-lg font-bold text-gray-900"
                                                    x-text="'৳' + relatedProduct.price"></span>
                                            </div>
                                        </div>

                                        <div class="space-y-2">
                                            <button @click="addRelatedToCart(relatedProduct)"
                                                :disabled="relatedProduct.stock_quantity === 0"
                                                :class="relatedProduct.stock_quantity === 0 ? 
                                                            'w-full bg-gray-300 text-gray-500 text-sm py-2 px-4 rounded cursor-not-allowed' : 
                                                            'w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 px-4 rounded transition-colors'">
                                                <span
                                                    x-text="relatedProduct.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('add_to_cart')"></span>
                                            </button>

                                            <button
                                                @click="(function(){ addRelatedToCart(relatedProduct); setTimeout(()=>{ window.location.href = '/checkout' }, 150); })()"
                                                :disabled="relatedProduct.stock_quantity === 0"
                                                :class="relatedProduct.stock_quantity === 0 ? 
                                                            'w-full bg-gray-300 text-gray-500 text-sm py-2 px-4 rounded cursor-not-allowed' : 
                                                            'w-full bg-green-600 hover:bg-green-700 text-white text-sm py-2 px-4 rounded transition-colors'">
                                                <span
                                                    x-text="relatedProduct.stock_quantity === 0 ? $store.translationStore.translate('out_of_stock') : $store.translationStore.translate('buy_now')"></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div x-show="!loading && !product" class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-5v5" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900" x-text="$store.translationStore.translate('product_not_found')"></h3>
                <p class="mt-1 text-sm text-gray-500" x-text="$store.translationStore.translate('product_not_found_message')"></p>
                <div class="mt-6">
                    <a href="/products"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        x-text="$store.translationStore.translate('view_all_products')">
                    </a>
                </div>
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

    <script>
        function productDetailStore() {
            return {
                product: null,
                relatedProducts: [],
                selectedImage: null,
                isVideoSelected: false, 
                videoEmbedUrl: null, 
                quantity: 1,
                loading: true,
                notification: {
                    show: false,
                    message: '',
                    type: 'success'
                },

                async init() {
                    const productId = window.location.pathname.split('/').pop();
                    await this.loadProduct(productId);
                    if (this.product) {
                        this.selectedImage = this.product.images?.[0] || null;
                        
                        // Check for video and select it first if available
                        if (this.getYouTubeVideoId()) {
                            this.selectVideo(true); // Selects video and starts autoplay
                        } else if (this.selectedImage) {
                            this.selectImage(this.selectedImage); // Fallback to first image
                        }

                        await this.loadRelatedProducts();
                    }
                    this.loading = false;
                },

                async loadProduct(id) {
                    try {
                        const response = await fetch(`${window.API_BASE}/products/${id}`);
                        if (response.ok) {
                            const data = await response.json();
                            this.product = data.data;
                        } else {
                            this.product = null;
                        }
                    } catch (error) {
                        console.error('Error loading product:', error);
                        this.product = null;
                    }
                },

                async loadRelatedProducts() {
                    try {
                        const response = await fetch(`${window.API_BASE}/products`);
                        const data = await response.json();

                        let allProducts = [];
                        if (data.success && data.data && data.data.data) {
                            allProducts = data.data.data; 
                        } else if (data.success && Array.isArray(data.data)) {
                            allProducts = data.data; 
                        } else {
                            allProducts = []; 
                        }

                        this.relatedProducts = allProducts
                            .filter(p => p.category_id === this.product.category_id && p.id !== this.product.id)
                            .slice(0, 4);

                        if (this.relatedProducts.length < 4) {
                            const additionalProducts = allProducts
                                .filter(p => p.id !== this.product.id && !this.relatedProducts.some(rp => rp.id === p.id))
                                .slice(0, 4 - this.relatedProducts.length);
                            this.relatedProducts = [...this.relatedProducts, ...additionalProducts];
                        }
                    } catch (error) {
                        console.error('Error loading related products:', error);
                        this.relatedProducts = [];
                    }
                },
                
                // Function to select an image and stop video playback
                selectImage(image) {
                    this.isVideoSelected = false;
                    this.selectedImage = image;
                    this.videoEmbedUrl = null; // Forces iframe to unload/stop
                },

                // Function to select the video and control autoplay
                selectVideo(autoplay = true) {
                    this.isVideoSelected = true;
                    this.selectedImage = null; // Deselect image
                    
                    const videoId = this.getYouTubeVideoId();
                    if (videoId) {
                        // Use a timestamp/key change to force iframe to reload.
                        // Add ?autoplay=1 for auto-play when clicked or set to true on load.
                        const autoParam = autoplay ? '&autoplay=1' : '';
                        this.videoEmbedUrl = `https://www.youtube.com/embed/${videoId}?rel=0&version=3&enablejsapi=1&playerapiid=ytplayer${autoParam}&t=${Date.now()}`;
                    }
                },

                // Extracts YouTube Video ID from standard videos AND Shorts
                getYouTubeVideoId() {
                    if (!this.product?.youtube_video_url) {
                        return null;
                    }

                    const url = this.product.youtube_video_url;
                    const patterns = [
                        /youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/,
                        /youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/,
                        /youtube\.com\/embed\/([a-zA-Z0-9_-]+)/,
                        /youtu\.be\/([a-zA-Z0-9_-]+)/,
                        /youtube\.com\/v\/([a-zA-Z0-9_-]+)/
                    ];

                    for (const pattern of patterns) {
                        const match = url.match(pattern);
                        if (match) {
                            return match[1];
                        }
                    }
                    return null;
                },

                // Generates the thumbnail image URL for the video
                getYouTubeThumbnail() {
                    const videoId = this.getYouTubeVideoId();
                    return videoId ? `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg` : null;
                },

                // --- Cart Logic (Unchanged) ---

                addToCart() {
                    if (!this.product || this.product.stock_quantity === 0 || this.quantity > this.product.stock_quantity) {
                        this.showNotification(this.product.stock_quantity === 0 ? 'Product is out of stock' : `Only ${this.product.stock_quantity} items available in stock`, 'error');
                        return;
                    }
                    const cartStore = Alpine.store('appStore');
                    const success = cartStore.addToCart({
                        id: this.product.id,
                        name: this.product.name,
                        price: this.product.price,
                        sale_price: this.product.sale_price,
                        images: this.product.images,
                        stock_quantity: this.product.stock_quantity
                    }, this.quantity);

                    this.showNotification(success ? `${this.product.name} (x${this.quantity}) added to cart!` : 'Failed to add product to cart', success ? 'success' : 'error');
                },

                buyNow() {
                    if (!this.product || this.product.stock_quantity === 0 || this.quantity > this.product.stock_quantity) {
                        this.showNotification(this.product.stock_quantity === 0 ? 'Product is out of stock' : `Only ${this.product.stock_quantity} items available in stock`, 'error');
                        return;
                    }
                    const cartStore = Alpine.store('appStore');
                    const success = cartStore.addToCart({
                        id: this.product.id,
                        name: this.product.name,
                        price: this.product.price,
                        sale_price: this.product.sale_price,
                        images: this.product.images,
                        stock_quantity: this.product.stock_quantity
                    }, this.quantity);

                    if (success) {
                        window.location.href = '/checkout';
                    } else {
                        this.showNotification('Failed to add product to cart', 'error');
                    }
                },

                addRelatedToCart(product) {
                    if (product.stock_quantity === 0) {
                        this.showNotification('Product is out of stock', 'error');
                        return;
                    }
                    const cartStore = Alpine.store('appStore');
                    const success = cartStore.addToCart({
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        sale_price: product.sale_price,
                        images: product.images,
                        stock_quantity: product.stock_quantity
                    }, 1);

                    this.showNotification(success ? `${product.name} added to cart!` : 'Failed to add product to cart', success ? 'success' : 'error');
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