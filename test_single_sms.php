<?php

require __DIR__ . '/vendor/autoload.php';

// Create a Laravel application instance for testing
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Order;

echo "🧪 Testing Single SMS Delivery Fix...\n\n";

// Clear any existing cache
\Cache::flush();
echo "✅ Cache cleared\n\n";

// Get a test order
$order = Order::with('customer')->first();
if (!$order) {
    echo "❌ No orders found in database\n";
    exit(1);
}

echo "📋 Test Order ID: {$order->id}\n";
echo "📋 Current Status: {$order->status}\n";
echo "👤 Customer: " . ($order->customer ? $order->customer->name : 'N/A') . "\n";
echo "📞 Phone: {$order->phone}\n\n";

// Store original status and choose appropriate new status
$originalStatus = $order->status;

// Choose a new status that will trigger SMS
$validStatuses = ['confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
$newStatus = 'shipped'; // Always use 'shipped' as it should trigger SMS

// Make sure the order has a phone number for testing
if (empty($order->phone)) {
    $order->update(['phone' => '01712345678']); // Set a test phone number
    echo "📞 Set test phone number: 01712345678\n";
}

echo "🔄 Testing status change: {$originalStatus} → {$newStatus}\n\n";

// Clear logs before testing
file_put_contents(storage_path('logs/laravel.log'), '');

// Dispatch the event multiple times to simulate the old bug
echo "📤 Dispatching OrderStatusChanged event 3 times (simulating old bug)...\n";

for ($i = 1; $i <= 3; $i++) {
    echo "   Event #{$i}... ";
    event(new \App\Events\OrderStatusChanged($order, $originalStatus, $newStatus));
    echo "✅ Dispatched\n";
    
    // Small delay between events
    usleep(100000); // 0.1 seconds
}

echo "\n⏳ Waiting 2 seconds for processing...\n";
sleep(2);

// Check logs for SMS sends
$logContent = file_get_contents(storage_path('logs/laravel.log'));
$smsAttempts = substr_count($logContent, 'Attempting to send SMS');
$smsSuccess = substr_count($logContent, 'SMS sent successfully');
$duplicatePrevention = substr_count($logContent, 'Duplicate SMS prevented');

echo "\n📊 Results:\n";
echo "   SMS Attempts: {$smsAttempts}\n";
echo "   SMS Success: {$smsSuccess}\n";
echo "   Duplicates Prevented: {$duplicatePrevention}\n\n";

if ($smsSuccess === 1 && $duplicatePrevention >= 1) {
    echo "✅ SUCCESS: Only 1 SMS was sent, duplicates were prevented!\n";
} elseif ($smsSuccess > 1) {
    echo "❌ FAILURE: Multiple SMS messages were sent ({$smsSuccess})\n";
} else {
    echo "⚠️  WARNING: No SMS was sent - check configuration\n";
}

// Show relevant log entries
echo "\n📝 Relevant log entries:\n";
$logLines = explode("\n", $logContent);
foreach ($logLines as $line) {
    if (strpos($line, 'SendSmsNotification') !== false || 
        strpos($line, 'Attempting to send SMS') !== false || 
        strpos($line, 'SMS sent successfully') !== false ||
        strpos($line, 'Duplicate SMS prevented') !== false) {
        echo "   " . substr($line, 0, 150) . "...\n";
    }
}

echo "\n=== Single SMS Test Complete ===\n";