<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Order;

echo "=== Testing Admin Panel SMS Functionality ===\n";

// Find an order to test with
$order = Order::with('customer')->first();
if (!$order) {
    echo "❌ No orders found in database\n";
    exit(1);
}

echo "Testing Order #{$order->id}\n";
echo "Customer: {$order->customer->name}\n";
echo "Phone: {$order->customer->phone}\n";
echo "Current Status: {$order->status}\n\n";

// Clear logs
file_put_contents(storage_path('logs/laravel.log'), '');

// Simulate the API call
echo "Simulating API status update...\n";

$oldStatus = $order->status;
$newStatus = $oldStatus === 'delivered' ? 'shipped' : 'delivered';

// Update status and trigger event manually (same as API controller)
$order->update(['status' => $newStatus]);
event(new \App\Events\OrderStatusChanged($order, $oldStatus, $newStatus));

echo "✅ Status updated from '{$oldStatus}' to '{$newStatus}'\n";
echo "✅ Event dispatched manually\n\n";

// Wait a moment for processing
sleep(2);

// Check if SMS was sent by looking at logs
$logs = file_get_contents(storage_path('logs/laravel.log'));

if (strpos($logs, 'SMS sent successfully') !== false) {
    echo "✅ SMS was sent successfully!\n";
} elseif (strpos($logs, 'SMS Sent') !== false) {
    echo "✅ SMS notification processed!\n";
} else {
    echo "❌ No SMS activity found in logs\n";
    echo "Checking for any SMS-related entries...\n";
    
    if (strpos($logs, 'SendSmsNotification') !== false) {
        echo "📧 Found SendSmsNotification activity in logs\n";
    }
    
    if (strpos($logs, 'OrderStatusChanged') !== false) {
        echo "📧 Found OrderStatusChanged event in logs\n";
    }
    
    // Show recent log entries
    $logLines = explode("\n", $logs);
    $recentLines = array_slice($logLines, -10);
    echo "\nRecent log entries:\n";
    foreach ($recentLines as $line) {
        if (trim($line)) {
            echo "  " . trim($line) . "\n";
        }
    }
}

echo "\n=== Test Complete ===\n";