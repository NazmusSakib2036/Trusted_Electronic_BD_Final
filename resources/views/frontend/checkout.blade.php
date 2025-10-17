@extends('frontend.layouts.app')

@section('title', 'Checkout - Trusted Electronics BD')

@section('content')
    <div x-data="checkoutStore()" x-init="init()">
        <nav class="bg-gray-50 py-4">
            <div class="container mx-auto px-4">
                <div class="flex items-center space-x-2 text-sm">
                    <a href="/" class="text-blue-600 hover:underline" x-text="$store.translationStore.translate('home')"></a>
                    <span class="text-gray-400">/</span>
                    <a href="/cart" class="text-blue-600 hover:underline" x-text="$store.translationStore.translate('cart')"></a>
                    <span class="text-gray-400">/</span>
                    <span class="text-gray-700" x-text="$store.translationStore.translate('checkout')"></span>
                </div>
            </div>
        </nav>

        <section class="py-8 min-h-screen">
            <div class="container mx-auto px-4">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900" x-text="$store.translationStore.translate('checkout')"></h1>
                </div>

                <div x-show="cartItems.length === 0" class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2" x-text="$store.translationStore.translate('checkout_empty_cart')"></h3>
                    <p class="text-gray-600 mb-6" x-text="$store.translationStore.translate('checkout_empty_message')"></p>
                    <a href="/products"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                        x-text="$store.translationStore.translate('continue_shopping')">
                    </a>
                </div>

                <div x-show="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-md border p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6" x-text="$store.translationStore.translate('customer_info')"></h2>

                            <form @submit.prevent="placeOrder()" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" x-text="$store.translationStore.translate('full_name') + ' *'"></label>
                                    <input type="text" x-model="customerInfo.name" required :placeholder="$store.translationStore.translate('full_name')"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" x-text="$store.translationStore.translate('phone_number') + ' *'"></label>
                                    <input type="tel" x-model="customerInfo.phone" required placeholder="০১৩XXXXXXXXX"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2" x-text="$store.translationStore.translate('division') + ' *'"></label>
                                        <select x-model="customerInfo.division" @change="onDivisionChange()" required
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="" x-text="$store.translationStore.translate('select_division')"></option>
                                            <template x-for="division in divisions" :key="division.nameKey">
                                                <option :value="division.nameKey" x-text="$store.translationStore.translate(division.nameKey)"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2" x-text="$store.translationStore.translate('district') + ' *'"></label>
                                        <select x-model="customerInfo.district" required :disabled="!customerInfo.division"
                                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-100">
                                            <option value="" x-text="$store.translationStore.translate('select_district')"></option>
                                            <template x-for="district in availableDistricts" :key="district.nameKey">
                                                <option :value="district.nameKey" x-text="$store.translationStore.translate(district.nameKey)"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white rounded-lg shadow-md border p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6" x-text="$store.translationStore.translate('shipping_address')"></h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" x-text="$store.translationStore.translate('address') + ' *'"></label>
                                    <textarea x-model="shippingAddress.address" required rows="3"
                                        :placeholder="$store.translationStore.translate('address')"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2" x-text="$store.translationStore.translate('delivery_instructions')"></label>
                                    <textarea x-model="shippingAddress.instructions" rows="2"
                                        :placeholder="$store.translationStore.translate('delivery_instructions_placeholder')"
                                        class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md border p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6" x-text="$store.translationStore.translate('shipping_area')"></h2>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3" x-text="$store.translationStore.translate('select_shipping_area')"></label>
                                    <div class="space-y-3">
                                        <!-- <-- 
                                                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                                                                       :class="{'border-blue-500 bg-blue-50': shippingArea === 'dhaka'}">
                                                                    <input type="radio" 
                                                                           x-model="shippingArea" 
                                                                           value="dhaka"
                                                                           @change="updateShipping('dhaka')"
                                                                           class="text-blue-600 focus:ring-blue-500">
                                                                    <div class="ml-3 flex-1">
                                                                        <div class="flex justify-between items-center">
                                                                            <span class="font-medium text-gray-900">Inside Dhaka</span>
                                                                            <span class="font-bold text-blue-600">Tk 70</span>
                                                                        </div>
                                                                        <p class="text-sm text-gray-600 mt-1">Dhaka Metropolitan Area - Faster delivery</p>
                                                                    </div>
                                                                </label>
                                                                -- -->

                                        <label
                                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer transition-colors"
                                            :class="{'border-blue-500 bg-blue-50': shippingArea === 'outside'}">
                                            <input type="radio" x-model="shippingArea" value="outside"
                                                @change="updateShipping('outside')"
                                                class="text-blue-600 focus:ring-blue-500">
                                            <div class="ml-3 flex-1">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-medium text-gray-900" x-text="$store.translationStore.translate('delivery_charge')"></span>
                                                    <span class="font-bold text-orange-600">টাকা ১২০</span>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1" x-text="$store.translationStore.translate('all_areas_bangladesh')"></p>
                                            </div>
                                        </label>
                                    </div>


                                    <!-- <div x-show="!shippingArea" class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                                <div class="flex items-center">
                                                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                                    </svg>
                                                                    <span class="text-sm text-red-700 font-medium">Please select a shipping area to continue</span>
                                                                </div>
                                                            </div> -->


                                    <p
                                        style="color: red; font-weight: bold; margin-top: 20px; padding: 1rem; background-color: #fff0f0; border: 1px solid #ffcccc; border-radius: 0.5rem;"
                                        x-text="$store.translationStore.translate('new_customer_notice')">
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md border p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6" x-text="$store.translationStore.translate('payment_method')"></h2>

                            <div class="space-y-4">
                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" x-model="paymentMethod" value="cod"
                                        class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900" x-text="$store.translationStore.translate('cash_on_delivery')"></span>
                                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </div>

                                    </div>
                                </label>

                                <p
                                    style="color: red; font-weight: bold; margin-top: 20px; padding: 1rem; background-color: #fff0f0; border: 1px solid #ffcccc; border-radius: 0.5rem;"
                                    x-text="$store.translationStore.translate('cod_restriction_notice')">
                                </p>

                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 opacity-50">
                                    <input type="radio" value="bkash" disabled class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900" x-text="$store.translationStore.translate('bkash')"></span>
                                            <div class="bg-pink-500 text-white px-2 py-1 rounded text-xs font-bold" x-text="$store.translationStore.translate('bkash')">
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600" x-text="$store.translationStore.translate('coming_soon')"></p>
                                    </div>
                                </label>

                                <label
                                    class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 opacity-50">
                                    <input type="radio" value="rocket" disabled class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900" x-text="$store.translationStore.translate('rocket')"></span>
                                            <div class="bg-purple-500 text-white px-2 py-1 rounded text-xs font-bold" x-text="$store.translationStore.translate('rocket')">
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600" x-text="$store.translationStore.translate('coming_soon')"></p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>





                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-md border p-6 sticky top-4">
                            <h2 class="text-xl font-semibold text-gray-900 mb-6" x-text="$store.translationStore.translate('order_summary_checkout')"></h2>

                            <div class="space-y-4 mb-6 max-h-96 overflow-y-auto">
                                <template x-for="item in cartItems" :key="item.uniqueId">
                                    <div class="flex items-center space-x-4 pb-4 border-b border-gray-100 last:border-b-0">
                                        <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden">
                                            <img x-show="item.images && item.images.length > 0" :src="item.images[0]"
                                                :alt="item.name" class="w-full h-full object-cover">
                                            <div x-show="!item.images || item.images.length === 0"
                                                class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900 text-sm line-clamp-2" x-text="item.name">
                                            </h4>
                                            <p class="text-sm text-gray-600"><span x-text="$store.translationStore.translate('quantity_short')"></span>: <span x-text="item.quantity"></span></p>
                                            <p class="text-sm font-semibold text-gray-900"
                                                x-text="'Tk ' + ((item.sale_price || item.price) * item.quantity)"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-700">
                                    <span x-text="$store.translationStore.translate('subtotal') + ' (' + getTotalItems() + ' ' + ($store.translationStore.currentLanguage === 'english' ? 'items' : 'আইটেম') + ')'"></span>
                                    <span x-text="'Tk ' + getSubtotal()"></span>
                                </div>

                                <div x-show="appliedCoupon" class="flex justify-between text-green-600">
                                    <span x-text="$store.translationStore.translate('discount') + ' (' + (appliedCoupon?.code || '') + ')'"></span>
                                    <span x-text="'- Tk ' + getDiscountAmount()"></span>
                                </div>

                                <div class="flex justify-between text-gray-700">
                                    <span x-text="$store.translationStore.translate('shipping')"></span>
                                    {{-- shippingArea is now always 'outside' (Tk 120) and is auto-calculated --}}
                                    <span x-text="'Tk ' + getShippingCost()"></span>
                                </div>

                                <hr class="border-gray-200">

                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span x-text="$store.translationStore.translate('total')"></span>
                                    <span x-text="'Tk ' + getTotal()"></span>
                                </div>
                            </div>

                            <button @click="placeOrder()" :disabled="isPlacingOrder"
                                class="w-full bg-purple-600 hover:bg-purple-700 disabled:bg-purple-400 text-white py-4 px-6 rounded-lg font-semibold transition-colors">
                                <span x-show="!isPlacingOrder" x-text="$store.translationStore.translate('place_order')"></span>
                                <span x-show="isPlacingOrder" class="flex items-center justify-center">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span x-text="$store.translationStore.translate('placing_order')"></span>
                                </span>
                            </button>

                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="$store.translationStore.translate('secure_checkout')"></span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span x-text="$store.translationStore.translate('ssl_secured')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
    </div>

    @push('scripts')
        <script>
            function checkoutStore() {
                return {
                    cartItems: [],
                    appliedCoupon: null,
                    shippingArea: 'outside', // <<< Fixed to 'outside' as default
                    isPlacingOrder: false, 
                    paymentMethod: 'cod',
                    customerInfo: {
                        name: '',
                        phone: '',
                        division: '',
                        district: ''
                    },
                    shippingAddress: {
                        address: '',
                        instructions: ''
                    },
                    notification: {
                        show: false,
                        message: '',
                        type: 'success'
                    },

                    divisions: [
                        {
                            nameKey: 'dhaka_division',
                            districts: [
                                { nameKey: 'dhaka_district' },
                                { nameKey: 'faridpur_district' },
                                { nameKey: 'gazipur_district' },
                                { nameKey: 'gopalganj_district' },
                                { nameKey: 'kishoreganj_district' },
                                { nameKey: 'madaripur_district' },
                                { nameKey: 'manikganj_district' },
                                { nameKey: 'munshiganj_district' },
                                { nameKey: 'narayanganj_district' },
                                { nameKey: 'narsingdi_district' },
                                { nameKey: 'rajbari_district' },
                                { nameKey: 'shariatpur_district' },
                                { nameKey: 'tangail_district' }
                            ]
                        },
                        {
                            nameKey: 'chattogram_division',
                            districts: [
                                { nameKey: 'chattogram_district' },
                                { nameKey: 'bandarban_district' },
                                { nameKey: 'brahmanbaria_district' },
                                { nameKey: 'chandpur_district' },
                                { nameKey: 'comilla_district' },
                                { nameKey: 'coxs_bazar_district' },
                                { nameKey: 'feni_district' },
                                { nameKey: 'khagrachari_district' },
                                { nameKey: 'lakshmipur_district' },
                                { nameKey: 'noakhali_district' },
                                { nameKey: 'rangamati_district' }
                            ]
                        },
                        {
                            nameKey: 'rajshahi_division',
                            districts: [
                                { nameKey: 'rajshahi_district' },
                                { nameKey: 'bogra_district' },
                                { nameKey: 'chapai_nawabganj_district' },
                                { nameKey: 'joypurhat_district' },
                                { nameKey: 'naogaon_district' },
                                { nameKey: 'natore_district' },
                                { nameKey: 'pabna_district' },
                                { nameKey: 'sirajganj_district' }
                            ]
                        },
                        {
                            nameKey: 'khulna_division',
                            districts: [
                                { nameKey: 'khulna_district' },
                                { nameKey: 'bagerhat_district' },
                                { nameKey: 'chuadanga_district' },
                                { nameKey: 'jessore_district' },
                                { nameKey: 'jhenaidah_district' },
                                { nameKey: 'kushtia_district' },
                                { nameKey: 'magura_district' },
                                { nameKey: 'meherpur_district' },
                                { nameKey: 'narayanganj_district' },
                                { nameKey: 'satkhira_district' }
                            ]
                        },
                        {
                            nameKey: 'barisal_division',
                            districts: [
                                { nameKey: 'barisal_district' },
                                { nameKey: 'barguna_district' },
                                { nameKey: 'bhola_district' },
                                { nameKey: 'jhalokathi_district' },
                                { nameKey: 'patuakhali_district' },
                                { nameKey: 'pirojpur_district' }
                            ]
                        },
                        {
                            nameKey: 'sylhet_division',
                            districts: [
                                { nameKey: 'sylhet_district' },
                                { nameKey: 'habiganj_district' },
                                { nameKey: 'moulvibazar_district' },
                                { nameKey: 'sunamganj_district' }
                            ]
                        },
                        {
                            nameKey: 'rangpur_division',
                            districts: [
                                { nameKey: 'rangpur_district' },
                                { nameKey: 'dinajpur_district' },
                                { nameKey: 'gaibandha_district' },
                                { nameKey: 'kurigram_district' },
                                { nameKey: 'lalmonirhat_district' },
                                { nameKey: 'nilphamari_district' },
                                { nameKey: 'panchagarh_district' },
                                { nameKey: 'thakurgaon_district' }
                            ]
                        },
                        {
                            nameKey: 'mymensingh_division',
                            districts: [
                                { nameKey: 'mymensingh_district' },
                                { nameKey: 'jamalpur_district' },
                                { nameKey: 'netrokona_district' },
                                { nameKey: 'sherpur_district' }
                            ]
                        }
                    ],
                    availableDistricts: [],

                    init() {
                        // Get cart items from app store
                        this.cartItems = this.$store.appStore.cartItems || [];
                        this.appliedCoupon = this.$store.appStore.appliedCoupon;

                        // Set shippingArea to 'outside' and update global store if it exists
                        this.shippingArea = 'outside';
                        if (window.appStore) {
                            window.appStore.setShippingArea('outside');
                        }

                        // Watch for changes (Coupon/Items)
                        this.$watch('$store.appStore.cartItems', (newItems) => {
                            this.cartItems = newItems || [];
                        });

                        this.$watch('$store.appStore.appliedCoupon', (newCoupon) => {
                            this.appliedCoupon = newCoupon;
                        });

                        // Redirect if cart is empty
                        if (this.cartItems.length === 0) {
                            setTimeout(() => {
                                window.location.href = '/cart';
                            }, 100);
                        }

                        console.log('Checkout initialized with coupon:', this.appliedCoupon);
                        console.log('Checkout initialized with shipping area:', this.shippingArea);
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
                        // Shipping is now fixed to 120 Tk ('outside') as requested
                        return 120;
                    },

                    getTotal() {
                        const subtotal = this.getSubtotal();
                        const discount = this.getDiscountAmount();
                        const shipping = this.getShippingCost();
                        return Math.max(0, subtotal - discount + shipping);
                    },

                    updateShipping(area) {
                        // This function is now simplified/fixed to only set 'outside' and a message (Optional)
                        if (area !== 'outside') {
                            this.shippingArea = 'outside';
                            if (window.appStore) {
                                window.appStore.setShippingArea('outside');
                            }
                            this.showNotification('Shipping is fixed at Tk 120 (Outside Dhaka)', 'info');
                        }
                    },

                    onDivisionChange() {
                        // Reset district when division changes
                        this.customerInfo.district = '';

                        // Update available districts based on selected division
                        const selectedDivision = this.divisions.find(div => div.nameKey === this.customerInfo.division);
                        this.availableDistricts = selectedDivision ? selectedDivision.districts : [];
                    },

                    async placeOrder() {
                        if (this.isPlacingOrder) return;

                        // Validate form
                        if (!this.customerInfo.name || !this.customerInfo.phone || !this.customerInfo.division || !this.customerInfo.district) {
                            this.showNotification('Please fill in all customer information including division and district', 'error');
                            return;
                        }

                        if (!this.shippingAddress.address) {
                            this.showNotification('Please fill in the street address', 'error');
                            return;
                        }

                        // shippingArea validation is now always true since it's hardcoded to 'outside'
                        if (this.cartItems.length === 0) {
                            this.showNotification('Your cart is empty', 'error');
                            return;
                        }

                        this.isPlacingOrder = true;

                        try {
                            // Prepare order data
                            const orderData = {
                                customer_name: this.customerInfo.name,
                                customer_phone: this.customerInfo.phone,
                                customer_division: this.customerInfo.division,
                                customer_district: this.customerInfo.district,
                                shipping_address: this.shippingAddress.address,
                                delivery_instructions: this.shippingAddress.instructions,
                                payment_method: this.paymentMethod,
                                subtotal: this.getSubtotal(),
                                discount: this.getDiscountAmount(),
                                shipping_cost: this.getShippingCost(), // Fixed Tk 120
                                shipping_area: this.shippingArea, // Always 'outside'
                                total: this.getTotal(),
                                coupon_code: this.appliedCoupon?.code || null,
                                items: this.cartItems.map(item => ({
                                    product_id: item.id,
                                    product_name: item.name,
                                    quantity: item.quantity,
                                    price: item.price,
                                    total: item.price * item.quantity
                                })),
                                status: 'pending'
                            };

                            const response = await fetch(`${window.API_BASE}/orders`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(orderData)
                            });

                            const data = await response.json();

                            if (response.ok) {
                                // Clear cart
                                this.$store.appStore.clearCart();

                                // Show success message
                                this.showNotification('Order placed successfully!', 'success');

                                // Redirect to thank you page or home
                                setTimeout(() => {
                                    window.location.href = '/';
                                }, 2000);
                            } else {
                                this.showNotification(data.message || 'Error placing order', 'error');
                            }
                        } catch (error) {
                            console.error('Error placing order:', error);
                            this.showNotification('Error placing order. Please try again.', 'error');
                        } finally {
                            this.isPlacingOrder = false;
                        }
                    },

                    showNotification(message, type = 'success') {
                        this.notification = { show: true, message, type };
                        setTimeout(() => {
                            this.notification.show = false;
                        }, 5000);
                    }
                }
            }
        </script>
    @endpush
@endsection