@extends('frontend.layouts.app')

@section('title', 'Shopping Cart - Trusted Electronics BD')

@section('content')
<div x-data="cartStore()" x-init="init()">
    <!-- Breadcrumb -->
    <nav class="bg-gray-50 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center space-x-2 text-sm">
                <a href="/" class="text-blue-600 hover:underline" x-text="$store.translationStore.translate('home')"></a>
                <span class="text-gray-400">/</span> 
                <span class="text-gray-700" x-text="$store.translationStore.translate('shopping_cart')"></span>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <section class="py-8 min-h-screen">
        <div class="container mx-auto px-4">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2" x-text="$store.translationStore.translate('shopping_cart')"></h1>
                <p class="text-gray-600" x-text="`${cartItems.length} ${cartItems.length === 1 ? $store.translationStore.translate('item_in_cart') : $store.translationStore.translate('items_in_cart')}`"></p>
            </div> 

            <!-- Empty Cart -->
            <div x-show="cartItems.length === 0" class="text-center py-16">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6"/>
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2" x-text="$store.translationStore.translate('empty_cart')"></h3>
                <p class="text-gray-600 mb-6" x-text="$store.translationStore.translate('empty_cart_message')"></p>
                <a href="/products" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                    </svg>
                    <span x-text="$store.translationStore.translate('continue_shopping')"></span>
                </a>
            </div>

            <!-- Cart Items -->
            <div x-show="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items List -->
                <div class="lg:col-span-2 space-y-4">
                    <template x-for="item in cartItems" :key="item.id">
                        <div class="bg-white rounded-lg shadow-md border p-4">
                            <div class="flex flex-row items-center gap-3 sm:gap-4 overflow-x-auto">
                                <!-- Product Image -->
                                <div class="w-20 h-20 sm:w-32 sm:h-32 bg-gray-100 rounded-lg overflow-hidden cursor-pointer flex-shrink-0"
                                    @click="viewProduct(item.id)">
                                    <img x-show="item.image" :src="item.image" :alt="item.name"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                                    <div x-show="!item.image" class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Main details: image left, info column to the right on mobile -->
                                <div class="flex-1 flex items-center gap-3 min-w-0">
                                    <!-- Center column: name & price -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm sm:text-lg font-semibold text-gray-900 truncate cursor-pointer hover:text-blue-600"
                                            @click="viewProduct(item.id)" x-text="item.name"></h3>

                                        <div class="text-sm text-gray-700 mt-1" x-text="'Tk ' + item.price"></div>
                                    </div>

                                    <!-- Right column: qty, total, remove (stacked on mobile) -->
                                    <div class="flex flex-col items-end space-y-2 w-auto">
                                        <div class="flex items-center border border-gray-300 rounded text-sm bg-white">
                                            <button aria-label="Decrease quantity" @click="updateQuantity(item.id, Math.max(1, item.quantity - 1))"
                                                    class="px-3 py-2 sm:px-2 sm:py-1 hover:bg-gray-100 text-base sm:text-sm">-</button>
                                            <span class="px-4 py-2 border-l border-r border-gray-300 min-w-[2.2rem] text-center text-base sm:text-sm"
                                                  x-text="item.quantity" aria-live="polite"></span>
                                            <button aria-label="Increase quantity" @click="updateQuantity(item.id, item.quantity + 1)"
                                                    class="px-3 py-2 sm:px-2 sm:py-1 hover:bg-gray-100 text-base sm:text-sm">+</button>
                                        </div>

                                        <div class="text-sm sm:text-base font-bold text-gray-900" x-text="'Tk ' + (item.price * item.quantity)"></div>

                                        <button @click="removeFromCart(item.id)" class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span x-text="$store.translationStore.translate('remove')"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Continue Shopping Button -->
                    <div class="text-center py-6">
                        <a href="/products" class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                            </svg>
                            <span x-text="$store.translationStore.translate('continue_shopping')"></span>
                        </a>
                    </div>
                </div>

                <!-- Order Summary -->
                <div x-show="cartItems.length > 0" class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md border p-6 sticky top-4">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6" x-text="$store.translationStore.translate('order_summary')"></h2>
                        
                        <!-- Summary Items -->
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-gray-700">
                                <span x-text="$store.translationStore.translate('subtotal') + ' (' + getTotalItems() + ' ' + ($store.translationStore.currentLanguage === 'english' ? 'items' : 'আইটেম') + ')'"></span>
                                <span x-text="'Tk ' + getSubtotal()"></span>
                            </div>
                            
                            <!-- Discount Display -->
                            <div x-show="appliedCoupon" class="flex justify-between text-green-600">
                                <span x-text="$store.translationStore.translate('discount') + ' (' + (appliedCoupon?.code || '') + ')'"></span>
                                <span x-text="'- Tk ' + getDiscountAmount()"></span>
                            </div>
                            
                            <!-- Shipping Area Selection -->
                            <!-- <div class="border border-gray-200 rounded p-3 bg-gray-50">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Shipping Area:</label>
                                <select x-model="shippingArea" @change="updateShipping()" 
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select shipping area</option>
                                    <option value="dhaka">Inside Dhaka - Tk 70</option>
                                    <option value="outside">Outside Dhaka - Tk 120</option>
                                </select>
                            </div> -->
                            
                            <!-- <div class="flex justify-between text-gray-700">
                                <span>শিপিং</span>
                                <span x-text="shippingArea ? 'Tk ' + getShippingCost() : 'এলাকা নির্বাচন করুন'"></span>
                            </div> -->
                            
                            <hr class="border-gray-200">
                            
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span x-text="$store.translationStore.translate('total')"></span>
                                <span x-text="'Tk ' + getFinalTotal()"></span>
                            </div>
                        </div>

                        <!-- Discount Section -->
                        <div class="mb-6">
                            <div class="flex space-x-2">
                                <input type="text" 
                                       x-model="couponCode"
                                       :placeholder="$store.translationStore.translate('coupon_code')"
                                       class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button @click="applyCoupon()" 
                                        :disabled="!couponCode.trim()"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 disabled:bg-gray-100 disabled:text-gray-400 text-gray-800 rounded text-sm font-medium transition-colors"
                                        x-text="$store.translationStore.translate('apply_coupon')">
                                </button>
                            </div>
                            <div x-show="appliedCoupon" class="mt-2 p-2 bg-green-50 border border-green-200 rounded text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-green-800">
                                        কুপন প্রয়োগ করা হয়েছে: <strong x-text="appliedCoupon?.code"></strong>
                                        (<span x-text="appliedCoupon?.type === 'percentage' ? appliedCoupon?.value + '%' : 'Tk ' + appliedCoupon?.value"></span> ছাড়)
                                    </span>
                                    <button @click="removeCoupon()" class="text-red-600 hover:text-red-800">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button @click="proceedToCheckout()" 
                                class="w-full bg-purple-600 hover:bg-purple-700 text-white py-4 px-6 rounded-lg font-semibold transition-colors"
                                x-text="$store.translationStore.translate('checkout')">
                        </button>

                        <!-- Security Badges -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span x-text="$store.translationStore.translate('secure')"></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span x-text="$store.translationStore.translate('trusted')"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Notification -->
    <div x-show="notification.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed bottom-4 right-4 z-50">
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
function cartStore() {
    return {
        cartItems: [],
        couponCode: '',
        appliedCoupon: null,
        shippingArea: '',
        notification: {
            show: false,
            message: '',
            type: 'success'
        },
        
        init() {
            // Get cart items from app store
            this.cartItems = this.$store.appStore.cartItems || [];
            this.appliedCoupon = this.$store.appStore.appliedCoupon;
            this.shippingArea = this.$store.appStore.shippingArea;
            
            // Watch for cart changes
            this.$watch('$store.appStore.cartItems', (newItems) => {
                this.cartItems = newItems || [];
            });
            
            // Watch for coupon changes
            this.$watch('$store.appStore.appliedCoupon', (newCoupon) => {
                this.appliedCoupon = newCoupon;
            });
            
            console.log('Cart initialized with items:', this.cartItems);
            console.log('Cart initialized with coupon:', this.appliedCoupon);
            console.log('Cart initialized with shipping area:', this.shippingArea);
        },
        
        viewProduct(productId) {
            window.location.href = `/product/${productId}`;
        },
        
        updateQuantity(productId, newQuantity) {
            this.$store.appStore.updateCartItemQuantity(productId, newQuantity);
            this.showNotification('Quantity updated', 'success');
        },
        
        removeFromCart(productId) {
            this.$store.appStore.removeFromCart(productId);
            this.showNotification('Item removed from cart', 'success');
        },
        
        getTotalItems() {
            return this.cartItems.reduce((total, item) => total + item.quantity, 0);
        },
        
        getSubtotal() {
            return this.cartItems.reduce((total, item) => {
                return total + (item.price * item.quantity);
            }, 0);
        },
        
        getDiscountAmount() {
            if (!this.appliedCoupon) return 0;
            
            const subtotal = this.getSubtotal();
            if (this.appliedCoupon.type === 'percentage') {
                return Math.round((subtotal * this.appliedCoupon.value) / 100);
            } else if (this.appliedCoupon.type === 'fixed') {
                return Math.min(this.appliedCoupon.value, subtotal);
            }
            return 0;
        },
        
        getShippingCost() {
            if (this.shippingArea === 'dhaka') {
                return 70;
            } else if (this.shippingArea === 'outside') {
                return 120;
            }
            return 0;
        },
        
        updateShipping() {
            // Save shipping area to global store for checkout
            this.$store.appStore.shippingArea = this.shippingArea;
            this.$store.appStore.saveShippingToStorage();
        },
        
        getFinalTotal() {
            const subtotal = this.getSubtotal();
            const discount = this.getDiscountAmount();
            const shipping = this.getShippingCost();
            return Math.max(0, subtotal - discount + shipping);
        },
        
        async applyCoupon() {
            if (!this.couponCode.trim()) return;
            
            try {
                const response = await fetch(`${window.API_BASE}/coupons/validate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        code: this.couponCode,
                        total: this.getSubtotal()
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.valid) {
                    this.$store.appStore.applyCoupon(data.coupon);
                    this.showNotification('Coupon applied successfully!', 'success');
                    console.log('Applied coupon:', data.coupon);
                } else {
                    this.showNotification(data.message || 'Invalid coupon code', 'error');
                }
            } catch (error) {
                console.error('Error applying coupon:', error);
                this.showNotification('Error applying coupon', 'error');
            }
        },
        
        removeCoupon() {
            this.$store.appStore.removeCoupon();
            this.couponCode = '';
            this.showNotification('Coupon removed', 'info');
        },
        
        proceedToCheckout() {
            if (this.cartItems.length === 0) {
                this.showNotification('Your cart is empty', 'error');
                return;
            }
            
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