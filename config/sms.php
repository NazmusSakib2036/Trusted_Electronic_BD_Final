<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure SMS service providers and their settings
    |
    */

    'default' => env('SMS_DRIVER', 'bulksmsbd'),

    'drivers' => [
        'bulksmsbd' => [
            'url' => env('BULKSMSBD_URL', 'http://bulksmsbd.net/api/smsapi'),
            'api_key' => env('BULKSMSBD_API_KEY', '4CaUBCVpiLpBNKd2YrqI'),
            'sender_id' => env('BULKSMSBD_SENDER_ID', '8809617629096'),
        ],
    ],

    'bulksmsbd' => [
        'url' => env('BULKSMSBD_URL', 'http://bulksmsbd.net/api/smsapi'),
        'balance_url' => env('BULKSMSBD_BALANCE_URL', 'http://bulksmsbd.net/api/getBalanceApi'),
        'api_key' => env('BULKSMSBD_API_KEY', '4CaUBCVpiLpBNKd2YrqI'),
        'sender_id' => env('BULKSMSBD_SENDER_ID', '8809617629096'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configure when to send SMS notifications
    |
    */

    'notifications' => [
        'order_status_changes' => env('SMS_ORDER_STATUS_CHANGES', [
            'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'
        ]),
        'order_status_change' => env('SMS_ORDER_STATUS', true),
        'order_created' => env('SMS_ORDER_CREATED', true),
        'payment_received' => env('SMS_PAYMENT_RECEIVED', true),
        'delivery_confirmation' => env('SMS_DELIVERY_CONFIRMATION', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Templates
    |--------------------------------------------------------------------------
    |
    | Pre-defined SMS message templates for different scenarios
    |
    */

    'templates' => [
        'order_pending' => 'Dear {customer}, your order #{order_id} is being processed. Total: {total}. We\'ll notify you once it\'s ready. Thanks! - TrustedElectronics',
        'order_confirmed' => 'Great news! Your order #{order_id} has been confirmed. Total: {total}. We\'re preparing your items. Thanks for choosing TrustedElectronics!',
        'order_processing' => 'Your order #{order_id} is now being processed. Total: {total}. We\'ll update you on shipping details soon. - TrustedElectronics',
        'order_shipped' => 'Your order #{order_id} has been shipped! Total: {total}. Track your delivery for updates. Thanks! - TrustedElectronics',
        'order_delivered' => 'Order #{order_id} delivered successfully! Total: {total}. Thank you for shopping with TrustedElectronics. Rate your experience!',
        'order_cancelled' => 'Sorry, your order #{order_id} has been cancelled. Total: {total}. Refund will be processed if applicable. Contact us for support. - TrustedElectronics',
        'payment_received' => 'Payment received for order #{order_id}! Amount: {total}. Your order will be processed shortly. Thanks! - TrustedElectronics',
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Logging
    |--------------------------------------------------------------------------
    |
    | Enable/disable SMS logging for audit purposes
    |
    */

    'logging' => [
        'enabled' => env('SMS_LOGGING', true),
        'log_success' => env('SMS_LOG_SUCCESS', true),
        'log_failures' => env('SMS_LOG_FAILURES', true),
    ],

];