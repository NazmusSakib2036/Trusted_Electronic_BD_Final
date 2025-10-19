<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trusted Electronics BD')</title>

    <!-- Favicon and Logo for Browser Tab -->
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/app/public/products/logo.jpg') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/app/public/products/logo.jpg') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/app/public/products/logo.jpg') }}">
    <meta name="msapplication-TileImage" content="{{ asset('storage/app/public/products/logo.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .category-card {
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: scale(1.05);
        }

        .cart-count {
            animation: bounce 0.5s ease-in-out;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        /* WhatsApp button styles */
        .whatsapp-fixed-btn {
            position: fixed;
            bottom: 20px;
            right: 24px;
            z-index: 9999;
            padding: 16px;
            border-radius: 9999px;
            background-color: #25D366 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .messenger-fixed-btn {
            position: fixed;
            bottom: 100px;
            right: 24px;
            z-index: 9999;
            padding: 16px;
            border-radius: 9999px;
            background-color: #25D366 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .whatsapp-fixed-btn .fa-whatsapp{
            color: white !important;
            font-size: 32px !important;
            line-height: 1;
        }
        .messenger-fixed-btn .fa-facebook-messenger {
            color: white !important;
            font-size: 32px !important;
            line-height: 1;
        }

        /* Minor responsiveness improvements for search */
        .header-search-container {
            width: 100%;
            max-width: 640px;
        }

        /* * UPDATED FIX: 
         * Custom class for centered footer column content on ALL screen sizes.
         * The only change needed was removing the media query override from the previous version.
         */
        .footer-col-center {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        /* Custom class for left alignment on mobile, required for this project */
        .footer-col-flexstart {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50" x-data="appStore()" x-init="init()">
    <div class="bg-red-600 text-white text-center py-1 cursor-pointer" style="font-size: .7rem;">
        <marquee behavior="scroll" direction="left" onmouseover="this.stop();" onmouseout="this.start();">
            <b>Trusted Electronic BD</b> - তে আপনাকে স্বাগতম । আমাদের থেকে নতুন লিথিয়্যাম ফসফেট ব্যাটারি দিয়ে 12v-24v ব্যাটারি প্যাক বানিয়ে নিতে পারবেন।
        </marquee>
    </div>

    <header class="bg-white shadow-md sticky top-0 z-50 px-2">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between py-4 space-x-6">

                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/">
                        <img style="height: 50px; width: auto;"
                            src="{{ asset('storage/app/public/products/logo.jpg') }}" alt="Trusted Electronics BD"
                            class="h-12">
                    </a>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl">
                    <div class="relative">
                        <input type="text" x-model="searchQuery" @keyup.enter="searchProducts()"
                            :placeholder="$store.translationStore.translate('search_placeholder')"
                            class="w-full px-6 py-3 border border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-base pr-14">

                        <button @click="searchProducts()"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 p-2 rounded-full bg-blue-500 text-white hover:bg-blue-600 transition duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Right Side Items -->
                <div class="flex items-center">
                    <button
                        @click="$store.translationStore.setLanguage($store.translationStore.currentLanguage === 'english' ? 'bangla' : 'english')"
                        class="flex items-center justify-center w-auto h-10 text-gray-700 hover:text-blue-500 transition duration-150 p-2 rounded-lg">
                        <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <span class="text-sm font-bold"
                            x-text="$store.translationStore.currentLanguage === 'english' ? 'BN' : 'EN'">
                        </span>
                    </button>

                    <!-- বাংলা ENGLISH -->

                    <a href="/cart" class="relative" aria-label="Shopping Cart">
                        <div
                            class="flex items-center justify-center w-10 h-10 text-gray-600 hover:text-blue-500 transition duration-150 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M17 13v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6" />
                            </svg>
                        </div>
                        <span x-show="$store.appStore.cartCount > 0" x-text="$store.appStore.cartCount"
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-semibold cart-count"></span>
                    </a>
                </div>

            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-12 mt-16">
        <div class="max-w-4xl mx-auto px-4" style="justify-content: center; align-items: center;">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">

                <div class="footer-col-flexstart">
                    <div class="mb-4">
                        <img style="height: 50px; width: auto;"
                            src="{{ asset('storage/app/public/products/logo.jpg') }}" alt="Trusted Electronics BD"
                            class="h-10">
                    </div> <br>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/profile.php?id=61561476570628" target="_blank"
                            class="text-white hover:text-blue-500 transition duration-150" aria-label="Facebook">
                            <i class="fab fa-facebook-square" style="font-size: 1.9rem !important;"></i>
                        </a>
                        <a href="https://www.youtube.com/@trustedelectronicbd1" target="_blank"
                            class="text-white hover:text-red-500 transition duration-150" aria-label="YouTube">
                            <i class="fab fa-youtube-square" style="font-size: 1.9rem !important;"></i>
                        </a>
                    </div>
                </div>

                <div class="footer-col-flexstart">
                    <h3 class="font-semibold mb-4 text-lg">Policies</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/about-us" class="hover:text-blue-400 transition duration-150">About Us</a></li>
                        <li><a href="" class="hover:text-blue-400 transition duration-150">Privacy Policy</a></li>
                        <li><a href="" class="hover:text-blue-400 transition duration-150">Terms And Conditions</a>
                        </li>
                        <li><a href="" class="hover:text-blue-400 transition duration-150">Return And Cancellation
                                Policy</a></li>
                    </ul>
                </div>

                <div class="footer-col-flexstart">
                    <h3 class="font-semibold mb-4 text-lg">Contact Us</h3>
                    <div class="space-y-2 text-sm">
                        <p><a href="mailto:robinhossain70000@gmail.com"
                                class="hover:text-blue-400 transition duration-150">robinhossain70000@gmail.com</a>
                        </p>
                        <p><a href="tel:+8801888058362"
                                class="hover:text-blue-400 transition duration-150">+8801888058362</a></p>
                        <p>Savar Heymatpur (Singair, Manikgonj)</p>
                    </div>
                </div>

                <div class="hidden md:block"></div>

            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm">
                <p>© 2025 - Copyright Trusted Electronic BD | Design & Development by <b><a
                            href="https://fabtechit.com/" target="_blank"
                            class="text-blue-400 hover:text-blue-300 transition duration-150">FabTech.IT</a></b></p>
            </div>
        </div> <br> <br>
    </footer>

    <a href="https://wa.me/8801888058362" title="Chat on WhatsApp" class="whatsapp-fixed-btn" target="_blank"
        aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <a href="https://m.me/335879172950893?source=qr_link_share" title="Chat on Messenger" class="messenger-fixed-btn" target="_blank"
        aria-label="Chat on Messenger">
        <i class="fab fa-facebook-messenger"></i>
    </a>

    <!-- Sticky cart pill (shows when cart has items) -->
    <div x-show="$store.appStore.cartCount > 0" x-cloak
        class="fixed left-1/2 transform -translate-x-1/2 bottom-20 z-50">
        <a href="/cart"
            class="inline-flex items-center bg-red-600 text-white px-6 py-3 rounded-full shadow-lg hover:shadow-xl transition-all">
            <span
                class="inline-flex items-center justify-center bg-white text-red-600 rounded-full h-6 w-6 mr-3 font-semibold"
                x-text="$store.appStore.cartCount"></span>
            <span class="mr-3">View your cart</span>
            <span class="font-semibold">(BDT <span x-text="$store.appStore.getCartTotal().toFixed(0)"></span>)</span>
        </a>
    </div>

    <script>
        // Set API base URL
        window.API_BASE = '{{ url("/api") }}';

        // Initialize Alpine stores
        document.addEventListener('alpine:init', () => {
            Alpine.store('translationStore', {
                currentLanguage: 'bangla', // Default to Bangla

                translations: {
                    'add_to_cart': {
                        'english': 'Add to Cart',
                        'bangla': 'কার্টে যোগ করুন'
                    },
                    'buy_now': {
                        'english': 'Buy Now',
                        'bangla': 'এখনই কিনুন'
                    },
                    'search_placeholder': {
                        'english': 'Search products...',
                        'bangla': 'পণ্য খুঁজুন...'
                    },
                    'view_cart': {
                        'english': 'View your cart',
                        'bangla': 'View your cart'
                    },
                    'out_of_stock': {
                        'english': 'Out of Stock',
                        'bangla': 'স্টকে নেই'
                    },
                    'all_products': {
                        'english': 'All Products',
                        'bangla': 'সব পণ্য'
                    },
                    'home': {
                        'english': 'Home',
                        'bangla': 'হোম'
                    },
                    'products': {
                        'english': 'Products',
                        'bangla': 'পণ্য'
                    },
                    'cart': {
                        'english': 'Cart',
                        'bangla': 'কার্ট'
                    },
                    'checkout': {
                        'english': 'Checkout',
                        'bangla': 'চেকআউট'
                    },
                    'shopping_cart': {
                        'english': 'Shopping Cart',
                        'bangla': 'শপিং কার্ট'
                    },
                    'continue_shopping': {
                        'english': 'Continue Shopping',
                        'bangla': 'শপিং চালিয়ে যান'
                    },
                    'empty_cart': {
                        'english': 'Your cart is empty',
                        'bangla': 'আপনার কার্ট খালি'
                    },
                    'empty_cart_message': {
                        'english': 'Add some products to get started!',
                        'bangla': 'শুরু করতে কিছু পণ্য যোগ করুন!'
                    },
                    'loading': {
                        'english': 'Loading...',
                        'bangla': 'লোড হচ্ছে...'
                    },
                    'loading_products': {
                        'english': 'Loading products...',
                        'bangla': 'পণ্য লোড হচ্ছে...'
                    },
                    'products_found': {
                        'english': 'products found',
                        'bangla': 'পণ্য পাওয়া গেছে'
                    },
                    'sort_by': {
                        'english': 'Sort by:',
                        'bangla': 'সাজান:'
                    },
                    'default': {
                        'english': 'Default',
                        'bangla': 'ডিফল্ট'
                    },
                    'price_low_to_high': {
                        'english': 'Price: Low to High',
                        'bangla': 'দাম: কম থেকে বেশি'
                    },
                    'price_high_to_low': {
                        'english': 'Price: High to Low',
                        'bangla': 'দাম: বেশি থেকে কম'
                    },
                    'name_a_to_z': {
                        'english': 'Name A-Z',
                        'bangla': 'নাম অ-ঃ'
                    },
                    'newest_first': {
                        'english': 'Newest First',
                        'bangla': 'সর্বশেষ প্রথম'
                    },
                    'all_categories': {
                        'english': 'All Categories',
                        'bangla': 'সব ক্যাটেগরি'
                    },
                    'no_products_found': {
                        'english': 'No products found',
                        'bangla': 'কোনো পণ্য পাওয়া যায়নি'
                    },
                    'adjust_search': {
                        'english': 'Try adjusting your search or filter criteria.',
                        'bangla': 'আপনার অনুসন্ধান বা ফিল্টার মানদণ্ড সামঞ্জস্য করার চেষ্টা করুন।'
                    },
                    'customer_info': {
                        'english': 'Customer Information',
                        'bangla': 'গ্রাহকের তথ্য'
                    },
                    'full_name': {
                        'english': 'Full Name',
                        'bangla': 'পূর্ণ নাম'
                    },
                    'phone_number': {
                        'english': 'Phone Number',
                        'bangla': 'ফোন নম্বর'
                    },
                    'division': {
                        'english': 'Division',
                        'bangla': 'বিভাগ'
                    },
                    'district': {
                        'english': 'District',
                        'bangla': 'জেলা'
                    },
                    'shipping_address': {
                        'english': 'Shipping Address',
                        'bangla': 'শিপিং ঠিকানা'
                    },
                    'address': {
                        'english': 'Address',
                        'bangla': 'ঠিকানা'
                    },
                    'order_summary': {
                        'english': 'Order Summary',
                        'bangla': 'অর্ডার সারসংক্ষেপ'
                    },
                    'subtotal': {
                        'english': 'Subtotal',
                        'bangla': 'উপমোট'
                    },
                    'discount': {
                        'english': 'Discount',
                        'bangla': 'ছাড়'
                    },
                    'shipping': {
                        'english': 'Shipping',
                        'bangla': 'শিপিং'
                    },
                    'total': {
                        'english': 'Total',
                        'bangla': 'মোট'
                    },
                    'place_order': {
                        'english': 'Place Order',
                        'bangla': 'অর্ডার করুন'
                    },
                    'apply_coupon': {
                        'english': 'Apply Coupon',
                        'bangla': 'কুপন প্রয়োগ করুন'
                    },
                    'coupon_code': {
                        'english': 'Coupon Code',
                        'bangla': 'কুপন কোড'
                    },
                    'remove': {
                        'english': 'Remove',
                        'bangla': 'সরান'
                    },
                    'quantity': {
                        'english': 'Quantity',
                        'bangla': 'পরিমাণ'
                    },
                    'price': {
                        'english': 'Price',
                        'bangla': 'দাম'
                    },
                    'save': {
                        'english': 'Save',
                        'bangla': 'সাশ্রয়'
                    },
                    'product_description': {
                        'english': 'Product Description',
                        'bangla': 'পণ্যের বিবরণ'
                    },
                    'related_products': {
                        'english': 'Related Products',
                        'bangla': 'সংক্রান্ত পণ্যসমূহ'
                    },
                    'product_not_found': {
                        'english': 'Product not found',
                        'bangla': 'পণ্য পাওয়া যায়নি'
                    },
                    'product_not_found_message': {
                        'english': "The product you're looking for doesn't exist.",
                        'bangla': 'আপনি যে পণ্যটি খুঁজছেন তা বিদ্যমান নেই।'
                    },
                    'view_all_products': {
                        'english': 'View All Products',
                        'bangla': 'সব পণ্য দেখুন'
                    },
                    'loading_product_details': {
                        'english': 'Loading product details...',
                        'bangla': 'পণ্যের বিবরণ লোড হচ্ছে...'
                    },
                    'items_in_cart': {
                        'english': 'items in your cart',
                        'bangla': 'আইটেম আপনার কার্টে'
                    },
                    'item_in_cart': {
                        'english': 'item in your cart',
                        'bangla': 'আইটেম আপনার কার্টে'
                    },
                    'checkout_empty_cart': {
                        'english': 'Your cart is empty',
                        'bangla': 'আপনার কার্ট খালি'
                    },
                    'checkout_empty_message': {
                        'english': 'Add some products before checkout!',
                        'bangla': 'চেকআউটের আগে কিছু পণ্য যোগ করুন!'
                    },
                    'secure': {
                        'english': 'Secure',
                        'bangla': 'নিরাপদ'
                    },
                    'trusted': {
                        'english': 'Trusted',
                        'bangla': 'বিশ্বস্ত'
                    },
                    'delivery_instructions': {
                        'english': 'Delivery Instructions (Optional)',
                        'bangla': 'ডেলিভারি নির্দেশনা (ঐচ্ছিক)'
                    },
                    'delivery_instructions_placeholder': {
                        'english': 'Special delivery instructions',
                        'bangla': 'বিশেষ ডেলিভারি নির্দেশনা'
                    },
                    'shipping_area': {
                        'english': 'Shipping Area',
                        'bangla': 'শিপিং এলাকা'
                    },
                    'select_shipping_area': {
                        'english': 'Select your shipping area to calculate delivery charge:',
                        'bangla': 'আপনার শিপিং এলাকা নির্বাচন করুন ডেলিভারি চার্জ গণনা করতে:'
                    },
                    'delivery_charge': {
                        'english': 'Delivery Charge',
                        'bangla': 'ডেলিভারি চার্জ'
                    },
                    'all_areas_bangladesh': {
                        'english': 'All areas of Bangladesh - Standard delivery',
                        'bangla': 'বাংলাদেশের সব এলাকা - স্ট্যান্ডার্ড ডেলিভারি'
                    },
                    'new_customer_notice': {
                        'english': 'New customers must pay 120 Taka delivery charge to confirm order. TRUSTED ELECTRONIC BD',
                        'bangla': 'নতুন কাস্টমারদের অবশ্যই 120 টাকা ডেলিভারি চার্জ পেমেন্ট করে অর্ডার কনর্ফাম করতে হবে। TRUSTED ELECTRONIC BD'
                    },
                    'payment_method': {
                        'english': 'Payment Method',
                        'bangla': 'পেমেন্ট পদ্ধতি'
                    },
                    'cash_on_delivery': {
                        'english': 'Cash on Delivery',
                        'bangla': 'ক্যাশ অন ডেলিভারি'
                    },
                    'cod_restriction_notice': {
                        'english': 'Cash on delivery is not applicable for those who have a record of previous order cancellation or return.',
                        'bangla': 'যাদের পূর্বে কোনো অর্ডার বাতিল বা রিটার্নের রেকর্ড রয়েছে, তাদের জন্য ক্যাশ অন ডেলিভারি প্রযোজ্য নয়।'
                    },
                    'bkash': {
                        'english': 'bKash',
                        'bangla': 'বিকাশ'
                    },
                    'rocket': {
                        'english': 'Rocket',
                        'bangla': 'রকেট'
                    },
                    'coming_soon': {
                        'english': 'Coming Soon',
                        'bangla': 'শীঘ্রই আসছে'
                    },
                    'order_summary_checkout': {
                        'english': 'Order Summary',
                        'bangla': 'অর্ডার সারাংশ'
                    },
                    'quantity_short': {
                        'english': 'Qty',
                        'bangla': 'পরিমাণ'
                    },
                    'placing_order': {
                        'english': 'Placing Order...',
                        'bangla': 'অর্ডার হচ্ছে...'
                    },
                    'secure_checkout': {
                        'english': 'Secure Checkout',
                        'bangla': 'নিরাপদ চেকআউট'
                    },
                    'ssl_secured': {
                        'english': 'SSL Secured',
                        'bangla': 'SSL সুরক্ষিত'
                    },
                    'taka_120': {
                        'english': 'Tk 120',
                        'bangla': 'টাকা ১২০'
                    },
                    'phone_placeholder': {
                        'english': '013XXXXXXXXX',
                        'bangla': '০১৩XXXXXXXXX'
                    },
                    'stock': {
                        'english': 'Stock',
                        'bangla': 'স্টক'
                    },
                    'available': {
                        'english': 'available',
                        'bangla': 'টি উপলব্ধ'
                    },
                    'save_tk': {
                        'english': 'Save Tk',
                        'bangla': 'সাশ্রয় টাকা'
                    },
                    'you_saved': {
                        'english': 'You saved',
                        'bangla': 'আপনি সেভ করেছেন'
                    },
                    'percent_off': {
                        'english': '% OFF',
                        'bangla': '% ছাড়'
                    },
                    'quantity_label': {
                        'english': 'Quantity',
                        'bangla': 'পরিমাণ'
                    },
                    'free_shipping': {
                        'english': 'Free shipping - (conditions apply)',
                        'bangla': 'বিনামূল্যে শিপিং - (শর্ত প্রযোজ্য)'
                    },
                    'days_return': {
                        'english': '7 Days Return',
                        'bangla': '৭ দিন ফিরতি'
                    },
                    'warranty_included': {
                        'english': 'Warranty Included',
                        'bangla': 'ওয়ারেন্টি অন্তর্ভুক্ত'
                    },
                    'secure_payment': {
                        'english': 'Secure Payment',
                        'bangla': 'নিরাপদ পেমেন্ট'
                    },
                    // Division translations
                    'dhaka_division': {
                        'english': 'Dhaka',
                        'bangla': 'ঢাকা'
                    },
                    'chattogram_division': {
                        'english': 'Chattogram',
                        'bangla': 'চট্টগ্রাম'
                    },
                    'rajshahi_division': {
                        'english': 'Rajshahi',
                        'bangla': 'রাজশাহী'
                    },
                    'khulna_division': {
                        'english': 'Khulna',
                        'bangla': 'খুলনা'
                    },
                    'barisal_division': {
                        'english': 'Barisal',
                        'bangla': 'বরিশাল'
                    },
                    'sylhet_division': {
                        'english': 'Sylhet',
                        'bangla': 'সিলেট'
                    },
                    'rangpur_division': {
                        'english': 'Rangpur',
                        'bangla': 'রংপুর'
                    },
                    'mymensingh_division': {
                        'english': 'Mymensingh',
                        'bangla': 'ময়মনসিংহ'
                    },
                    // District translations
                    'dhaka_district': {
                        'english': 'Dhaka',
                        'bangla': 'ঢাকা'
                    },
                    'faridpur_district': {
                        'english': 'Faridpur',
                        'bangla': 'ফরিদপুর'
                    },
                    'gazipur_district': {
                        'english': 'Gazipur',
                        'bangla': 'গাজীপুর'
                    },
                    'gopalganj_district': {
                        'english': 'Gopalganj',
                        'bangla': 'গোপালগঞ্জ'
                    },
                    'kishoreganj_district': {
                        'english': 'Kishoreganj',
                        'bangla': 'কিশোরগঞ্জ'
                    },
                    'madaripur_district': {
                        'english': 'Madaripur',
                        'bangla': 'মাদারীপুর'
                    },
                    'manikganj_district': {
                        'english': 'Manikganj',
                        'bangla': 'মানিকগঞ্জ'
                    },
                    'munshiganj_district': {
                        'english': 'Munshiganj',
                        'bangla': 'মুন্সীগঞ্জ'
                    },
                    'narayanganj_district': {
                        'english': 'Narayanganj',
                        'bangla': 'নারায়ণগঞ্জ'
                    },
                    'narsingdi_district': {
                        'english': 'Narsingdi',
                        'bangla': 'নরসিংদী'
                    },
                    'rajbari_district': {
                        'english': 'Rajbari',
                        'bangla': 'রাজবাড়ী'
                    },
                    'shariatpur_district': {
                        'english': 'Shariatpur',
                        'bangla': 'শরীয়তপুর'
                    },
                    'tangail_district': {
                        'english': 'Tangail',
                        'bangla': 'টাঙ্গাইল'
                    },
                    'chattogram_district': {
                        'english': 'Chattogram',
                        'bangla': 'চট্টগ্রাম'
                    },
                    'bandarban_district': {
                        'english': 'Bandarban',
                        'bangla': 'বান্দরবান'
                    },
                    'brahmanbaria_district': {
                        'english': 'Brahmanbaria',
                        'bangla': 'ব্রাহ্মণবাড়িয়া'
                    },
                    'chandpur_district': {
                        'english': 'Chandpur',
                        'bangla': 'চাঁদপুর'
                    },
                    'comilla_district': {
                        'english': 'Comilla',
                        'bangla': 'কুমিল্লা'
                    },
                    'coxs_bazar_district': {
                        'english': 'Cox\'s Bazar',
                        'bangla': 'কক্সবাজার'
                    },
                    'feni_district': {
                        'english': 'Feni',
                        'bangla': 'ফেনী'
                    },
                    'khagrachari_district': {
                        'english': 'Khagrachari',
                        'bangla': 'খাগড়াছড়ি'
                    },
                    'lakshmipur_district': {
                        'english': 'Lakshmipur',
                        'bangla': 'লক্ষ্মীপুর'
                    },
                    'noakhali_district': {
                        'english': 'Noakhali',
                        'bangla': 'নোয়াখালী'
                    },
                    'rangamati_district': {
                        'english': 'Rangamati',
                        'bangla': 'রাঙ্গামাটি'
                    },
                    'rajshahi_district': {
                        'english': 'Rajshahi',
                        'bangla': 'রাজশাহী'
                    },
                    'bogra_district': {
                        'english': 'Bogra',
                        'bangla': 'বগুড়া'
                    },
                    'chapai_nawabganj_district': {
                        'english': 'Chapai Nawabganj',
                        'bangla': 'চাঁপাইনবাবগঞ্জ'
                    },
                    'joypurhat_district': {
                        'english': 'Joypurhat',
                        'bangla': 'জয়পুরহাট'
                    },
                    'naogaon_district': {
                        'english': 'Naogaon',
                        'bangla': 'নওগাঁ'
                    },
                    'natore_district': {
                        'english': 'Natore',
                        'bangla': 'নাটোর'
                    },
                    'pabna_district': {
                        'english': 'Pabna',
                        'bangla': 'পাবনা'
                    },
                    'sirajganj_district': {
                        'english': 'Sirajganj',
                        'bangla': 'সিরাজগঞ্জ'
                    },
                    'khulna_district': {
                        'english': 'Khulna',
                        'bangla': 'খুলনা'
                    },
                    'bagerhat_district': {
                        'english': 'Bagerhat',
                        'bangla': 'বাগেরহাট'
                    },
                    'chuadanga_district': {
                        'english': 'Chuadanga',
                        'bangla': 'চুয়াডাঙ্গা'
                    },
                    'jessore_district': {
                        'english': 'Jessore',
                        'bangla': 'যশোর'
                    },
                    'jhenaidah_district': {
                        'english': 'Jhenaidah',
                        'bangla': 'ঝিনাইদহ'
                    },
                    'kushtia_district': {
                        'english': 'Kushtia',
                        'bangla': 'কুষ্টিয়া'
                    },
                    'magura_district': {
                        'english': 'Magura',
                        'bangla': 'মাগুরা'
                    },
                    'meherpur_district': {
                        'english': 'Meherpur',
                        'bangla': 'মেহেরপুর'
                    },
                    'satkhira_district': {
                        'english': 'Satkhira',
                        'bangla': 'সাতক্ষীরা'
                    },
                    'barisal_district': {
                        'english': 'Barisal',
                        'bangla': 'বরিশাল'
                    },
                    'barguna_district': {
                        'english': 'Barguna',
                        'bangla': 'বরগুনা'
                    },
                    'bhola_district': {
                        'english': 'Bhola',
                        'bangla': 'ভোলা'
                    },
                    'jhalokathi_district': {
                        'english': 'Jhalokathi',
                        'bangla': 'ঝালকাঠী'
                    },
                    'patuakhali_district': {
                        'english': 'Patuakhali',
                        'bangla': 'পটুয়াখালী'
                    },
                    'pirojpur_district': {
                        'english': 'Pirojpur',
                        'bangla': 'পিরোজপুর'
                    },
                    'sylhet_district': {
                        'english': 'Sylhet',
                        'bangla': 'সিলেট'
                    },
                    'habiganj_district': {
                        'english': 'Habiganj',
                        'bangla': 'হবিগঞ্জ'
                    },
                    'moulvibazar_district': {
                        'english': 'Moulvibazar',
                        'bangla': 'মৌলভীবাজার'
                    },
                    'sunamganj_district': {
                        'english': 'Sunamganj',
                        'bangla': 'সুনামগঞ্জ'
                    },
                    'rangpur_district': {
                        'english': 'Rangpur',
                        'bangla': 'রংপুর'
                    },
                    'dinajpur_district': {
                        'english': 'Dinajpur',
                        'bangla': 'দিনাজপুর'
                    },
                    'gaibandha_district': {
                        'english': 'Gaibandha',
                        'bangla': 'গাইবান্ধা'
                    },
                    'kurigram_district': {
                        'english': 'Kurigram',
                        'bangla': 'কুড়িগ্রাম'
                    },
                    'lalmonirhat_district': {
                        'english': 'Lalmonirhat',
                        'bangla': 'লালমনিরহাট'
                    },
                    'nilphamari_district': {
                        'english': 'Nilphamari',
                        'bangla': 'নীলফামারী'
                    },
                    'panchagarh_district': {
                        'english': 'Panchagarh',
                        'bangla': 'পঞ্চগড়'
                    },
                    'thakurgaon_district': {
                        'english': 'Thakurgaon',
                        'bangla': 'ঠাকুরগাঁও'
                    },
                    'mymensingh_district': {
                        'english': 'Mymensingh',
                        'bangla': 'ময়মনসিংহ'
                    },
                    'jamalpur_district': {
                        'english': 'Jamalpur',
                        'bangla': 'জামালপুর'
                    },
                    'netrokona_district': {
                        'english': 'Netrokona',
                        'bangla': 'নেত্রকোনা'
                    },
                    'sherpur_district': {
                        'english': 'Sherpur',
                        'bangla': 'শেরপুর'
                    },
                    'select_division': {
                        'english': 'Select Division',
                        'bangla': 'বিভাগ নির্বাচন করুন'
                    },
                    'select_district': {
                        'english': 'Select District',
                        'bangla': 'জেলা নির্বাচন করুন'
                    },

                },

                init() {
                    // Load saved language preference
                    const savedLang = localStorage.getItem('selectedLanguage');
                    if (savedLang) {
                        this.currentLanguage = savedLang;
                    }
                },

                setLanguage(language) {
                    this.currentLanguage = language;
                    localStorage.setItem('selectedLanguage', language);
                },

                translate(key) {
                    if (this.translations[key] && this.translations[key][this.currentLanguage]) {
                        return this.translations[key][this.currentLanguage];
                    }
                    return key; // Return the key if translation not found
                }
            });

            Alpine.store('appStore', {
                searchQuery: '',
                cartCount: 0,
                cartItems: [],
                appliedCoupon: null,
                shippingArea: '',

                init() {
                    this.loadCartFromStorage();
                    this.loadCouponFromStorage();
                    this.loadShippingFromStorage();
                    // Initialize translation store
                    Alpine.store('translationStore').init();
                },

                loadCartFromStorage() {
                    const cart = localStorage.getItem('cart');
                    if (cart) {
                        this.cartItems = JSON.parse(cart);
                        this.updateCartCount();
                    }
                },

                loadCouponFromStorage() {
                    const coupon = localStorage.getItem('appliedCoupon');
                    if (coupon) {
                        this.appliedCoupon = JSON.parse(coupon);
                    }
                },

                loadShippingFromStorage() {
                    const shipping = localStorage.getItem('shippingArea');
                    if (shipping) {
                        this.shippingArea = shipping;
                    }
                },

                saveShippingToStorage() {
                    if (this.shippingArea) {
                        localStorage.setItem('shippingArea', this.shippingArea);
                    } else {
                        localStorage.removeItem('shippingArea');
                    }
                },

                saveCouponToStorage() {
                    if (this.appliedCoupon) {
                        localStorage.setItem('appliedCoupon', JSON.stringify(this.appliedCoupon));
                    } else {
                        localStorage.removeItem('appliedCoupon');
                    }
                },

                applyCoupon(coupon) {
                    this.appliedCoupon = coupon;
                    this.saveCouponToStorage();
                },

                removeCoupon() {
                    this.appliedCoupon = null;
                    this.saveCouponToStorage();
                },

                updateCartCount() {
                    this.cartCount = this.cartItems.reduce((total, item) => total + item.quantity, 0);
                },

                addToCart(product, quantity = 1) {
                    console.log('Adding to cart:', product);

                    if (!product || !product.id) {
                        console.error('Invalid product data:', product);
                        return false;
                    }

                    const existingItem = this.cartItems.find(item => item.id === product.id);

                    if (existingItem) {
                        existingItem.quantity += quantity;
                        console.log('Updated existing item quantity:', existingItem);
                    } else {
                        const cartItem = {
                            id: product.id,
                            name: product.name,
                            price: product.sale_price || product.price,
                            image: product.images && product.images.length > 0 ? product.images[0] : null,
                            quantity: quantity
                        };
                        this.cartItems.push(cartItem);
                        console.log('Added new item to cart:', cartItem);
                    }

                    this.saveCartToStorage();
                    this.updateCartCount();

                    console.log('Cart items after addition:', this.cartItems);
                    console.log('Cart count after addition:', this.cartCount);

                    return true;
                },

                removeFromCart(productId) {
                    this.cartItems = this.cartItems.filter(item => item.id !== productId);
                    this.saveCartToStorage();
                    this.updateCartCount();
                },

                updateCartItemQuantity(productId, quantity) {
                    const item = this.cartItems.find(item => item.id === productId);
                    if (item) {
                        if (quantity <= 0) {
                            this.removeFromCart(productId);
                        } else {
                            item.quantity = quantity;
                            this.saveCartToStorage();
                            this.updateCartCount();
                        }
                    }
                },

                saveCartToStorage() {
                    localStorage.setItem('cart', JSON.stringify(this.cartItems));
                },

                clearCart() {
                    this.cartItems = [];
                    this.cartCount = 0;
                    this.appliedCoupon = null;
                    this.shippingArea = '';
                    localStorage.removeItem('cart');
                    localStorage.removeItem('appliedCoupon');
                    localStorage.removeItem('shippingArea');
                },

                getCartTotal() {
                    return this.cartItems.reduce((total, item) => total + (item.price * item.quantity), 0);
                }
            });
        });

        function appStore() {
            return {
                searchQuery: '',

                init() {
                    // Initialize the global store
                    this.$store.appStore.init();

                    // Sync with URL search parameters
                    const urlParams = new URLSearchParams(window.location.search);
                    const searchParam = urlParams.get('search');
                    if (searchParam) {
                        this.searchQuery = decodeURIComponent(searchParam);
                    }

                    // Listen for URL changes (back/forward navigation)
                    window.addEventListener('popstate', () => {
                        const urlParams = new URLSearchParams(window.location.search);
                        const searchParam = urlParams.get('search');
                        this.searchQuery = searchParam ? decodeURIComponent(searchParam) : '';
                    });
                },

                searchProducts() {
                    console.log('Navigation search triggered with query:', this.searchQuery);

                    if (this.searchQuery.trim()) {
                        // Check if we're on a page that has search functionality
                        const currentPath = window.location.pathname;
                        console.log('Current path:', currentPath);

                        if (currentPath === '/' || currentPath === '/products') {
                            // Update the URL with search parameter without page reload
                            const url = new URL(window.location);
                            url.searchParams.set('search', this.searchQuery);
                            window.history.pushState({}, '', url);

                            // Dispatch custom event for pages to handle
                            window.dispatchEvent(new CustomEvent('navigationSearch', {
                                detail: {
                                    searchQuery: this.searchQuery
                                }
                            }));
                            console.log('Dispatched navigationSearch event');
                        } else {
                            // Redirect to products page with search
                            console.log('Redirecting to products page');
                            window.location.href = `/products?search=${encodeURIComponent(this.searchQuery)}`;
                        }
                    } else {
                        console.log('Empty search query');
                    }
                }
            }
        }
    </script>

    @stack('scripts')
</body>

</html>