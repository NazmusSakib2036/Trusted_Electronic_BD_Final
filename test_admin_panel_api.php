<?php

require __DIR__ . '/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Models\Order;
use App\Models\User;

// Create a Laravel application instance for testing
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing Admin Panel API Integration...\n\n";

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

// Simulate the exact same request that admin panel makes
$controller = new OrderController();

// Create a request object with the same data structure
$request = Request::create('/api/admin/orders/' . $order->id . '/status', 'PATCH');
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Accept', 'application/json');

// Set the JSON content
$requestData = ['status' => 'shipped'];
$request->setJson(new \Symfony\Component\HttpFoundation\ParameterBag($requestData));

echo "🔧 Simulating Admin Panel Request:\n";
echo "   Method: PATCH\n";
echo "   URL: /api/admin/orders/{$order->id}/status\n";
echo "   Data: " . json_encode($requestData) . "\n\n";

try {
    // Store old status for comparison
    $oldStatus = $order->status;
    
    // Call the controller method directly
    $response = $controller->updateStatus($request, $order);
    
    echo "✅ Controller Response: " . $response->getContent() . "\n\n";
    
    // Refresh the order from database
    $order->refresh();
    
    echo "📋 Updated Status: {$order->status}\n";
    echo "🔄 Status Changed: " . ($oldStatus !== $order->status ? 'Yes' : 'No') . "\n\n";
    
    if ($oldStatus !== $order->status) {
        echo "✅ Status update successful - SMS should have been triggered!\n";
    } else {
        echo "❌ Status not updated - something went wrong\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Admin Panel API Test Complete ===\n";