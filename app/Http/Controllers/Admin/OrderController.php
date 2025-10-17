<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['customer', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['customer', 'orderItems.product']);
        
        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    /**
     * Store a new order from frontend.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_division' => 'required|string|max:255',
            'customer_district' => 'required|string|max:255',
            'shipping_address' => 'required|string',
            'postal_code' => 'nullable|string|max:10',
            'delivery_instructions' => 'nullable|string',
            'payment_method' => 'required|string|in:cod,bkash,rocket',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_area' => 'nullable|string',
            'total' => 'required|numeric|min:0',
            'coupon_code' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0'
        ]);

        try {
            // Validate stock availability for all items
            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found: ' . $item['product_name']
                    ], 400);
                }
                
                if ($product->stock_quantity < $item['quantity']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock for ' . $product->name . '. Available: ' . $product->stock_quantity . ', Requested: ' . $item['quantity']
                    ], 400);
                }
            }

            // Create or update customer
            $customer = \App\Models\Customer::updateOrCreate([
                'phone' => $request->customer_phone
            ], [
                'name' => $request->customer_name,
                'division' => $request->customer_division,
                'district' => $request->customer_district,
                'address_line_1' => $request->shipping_address
            ]);

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'total_amount' => $request->total,
                'discount_amount' => $request->discount ?? 0,
                'shipping_amount' => $request->shipping_cost ?? 0,
                'shipping_address' => [
                    'address' => $request->shipping_address,
                    'postal_code' => $request->postal_code,
                    'instructions' => $request->delivery_instructions,
                    'area' => $request->shipping_area
                ],
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'coupon_code' => $request->coupon_code
            ]);

            // Create order items and reduce stock
            foreach ($request->items as $item) {
                $product = \App\Models\Product::find($item['product_id']);
                
                // Create order item
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_sku' => $product ? $product->sku : 'N/A',
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ]);

                // Reduce product stock
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                }
            }

                \Mail::raw('Test email', function($message) {
    $message->to('shariarfahim21@gmail.com')
            ->subject('Test Mail');
});


            // Send admin notification email
            try {
                $adminEmail = config('mail.admin_email', 'shariarfahim21@gmail.com');
                \Mail::to($adminEmail)->send(new \App\Mail\NewOrderNotification($order));
                


            } catch (\Exception $e) {
                // Log email error but don't fail the order
                \Log::error('Failed to send admin notification email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'data' => [
                    'order_number' => $order->order_number,
                    'total' => $order->total,
                    'status' => $order->status
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error placing order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        // Add logging to track admin panel requests
        \Log::info('Admin Panel - Order Status Update Request', [
            'order_id' => $order->id,
            'old_status' => $order->status,
            'new_status' => $request->status,
            'user_agent' => $request->header('User-Agent'),
            'ip' => $request->ip(),
            'timestamp' => now()->toDateTimeString()
        ]);

        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Load customer relationship before updating
        $order->load('customer');
        
        $order->update(['status' => $newStatus]);

        // Log before event dispatch
        \Log::info('Admin Panel - Dispatching OrderStatusChanged Event', [
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'customer_phone' => $order->phone
        ]);

        // Manually dispatch the event since observer might not be working in API context
        event(new \App\Events\OrderStatusChanged($order, $oldStatus, $newStatus));

        if ($newStatus === 'shipped') {
            $order->update(['shipped_at' => now()]);
        } elseif ($newStatus === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }

        // Send email notification to customer
        try {
            $order->load('customer', 'orderItems');
            \Mail::to($order->customer->email)->send(new \App\Mail\OrderStatusUpdated($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            // Log email error but don't fail the status update
            \Log::error('Failed to send order status email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'payment_status' => 'required|in:pending,completed,failed,refunded'
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully',
            'data' => $order
        ]);
    }

    /**
     * Get orders by status.
     */
    public function byStatus(string $status): JsonResponse
    {
        $orders = Order::with(['customer', 'orderItems.product'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Add notes to order.
     */
    public function addNotes(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'notes' => 'required|string'
        ]);

        $order->update(['notes' => $request->notes]);

        return response()->json([
            'success' => true,
            'message' => 'Notes added successfully',
            'data' => $order
        ]);
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order): JsonResponse
    {
        try {
            // Delete order items first
            $order->orderItems()->delete();
            
            // Delete the order
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order'
            ], 500);
        }
    }

    /**
     * Generate invoice for order.
     */
    public function invoice(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        
        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Download invoice PDF.
     */
    public function downloadInvoice(Order $order)
    {
        $order->load(['customer', 'orderItems.product']);
        
        // For now, return HTML view. You can add PDF generation later with packages like dompdf
        return view('admin.orders.invoice-pdf', compact('order'));
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product']);
        
        // Apply filters if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Division',
                'Customer District',
                'Order Date',
                'Status',
                'Payment Status',
                'Payment Method',
                'Subtotal',
                'Tax Amount',
                'Shipping Amount',
                'Discount Amount',
                'Total Amount',
                'Shipping Address',
                'Items Count',
                'Products'
            ]);
            
            // Add data rows
            foreach ($orders as $order) {
                $products = $order->orderItems->map(function($item) {
                    return $item->product->name . ' (Qty: ' . $item->quantity . ')';
                })->join(', ');
                
                $shippingAddress = '';
                if ($order->shipping_address && is_array($order->shipping_address)) {
                    $addr = $order->shipping_address;
                    $shippingAddress = ($addr['address'] ?? '') . ', ' . 
                                     ($addr['city'] ?? '') . ', ' . 
                                     ($addr['state'] ?? '') . ', ' . 
                                     ($addr['postal_code'] ?? '');
                }
                
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_division,
                    $order->customer_district,
                    $order->created_at->format('Y-m-d H:i:s'),
                    ucfirst($order->status),
                    ucfirst($order->payment_status),
                    ucfirst($order->payment_method ?? 'N/A'),
                    $order->subtotal,
                    $order->tax_amount,
                    $order->shipping_amount,
                    $order->discount_amount,
                    $order->total_amount,
                    trim($shippingAddress, ', '),
                    $order->orderItems->count(),
                    $products
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}