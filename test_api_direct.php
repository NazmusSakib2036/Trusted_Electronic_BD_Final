<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== Testing API Route Directly ===\n";

// Create a request to the API endpoint
$request = Illuminate\Http\Request::create('/api/admin/orders/9/status', 'PATCH');
$request->headers->set('Content-Type', 'application/json');
$request->headers->set('Accept', 'application/json');
$request->initialize([], [], [], [], [], [], json_encode(['status' => 'processing']));

// Add CSRF token if needed
$request->headers->set('X-CSRF-TOKEN', 'test-token');

echo "Making PATCH request to /api/admin/orders/9/status with status=processing\n";

// Clear logs first
file_put_contents(storage_path('logs/laravel.log'), '');

try {
    $response = $kernel->handle($request);
    $content = $response->getContent();
    $statusCode = $response->getStatusCode();
    
    echo "Response Status: {$statusCode}\n";
    echo "Response Content: {$content}\n\n";
    
    // Wait for potential async processing
    sleep(2);
    
    // Check logs
    $logs = file_get_contents(storage_path('logs/laravel.log'));
    
    if (strpos($logs, 'SMS sent successfully') !== false) {
        echo "✅ SMS was sent via API route!\n";
    } elseif (strpos($logs, 'OrderStatusChanged') !== false) {
        echo "📧 Event was triggered via API route\n";
    } else {
        echo "❌ No SMS activity detected from API route\n";
        
        // Show what logs we got
        $logLines = explode("\n", trim($logs));
        echo "Log entries (" . count($logLines) . " lines):\n";
        foreach (array_slice($logLines, -5) as $line) {
            if (trim($line)) {
                echo "  " . trim($line) . "\n";
            }
        }
    }
    
} catch (Exception $e) {
    echo "❌ API Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== API Test Complete ===\n";