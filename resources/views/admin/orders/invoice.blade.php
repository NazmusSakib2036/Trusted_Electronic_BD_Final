@extends('admin.layouts.app')

@section('title', 'Invoice - Order #' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Invoice Header -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Invoice Top Section -->
        <div class="bg-blue-600 text-white p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">INVOICE</h1>
                    <p class="text-blue-100 mt-2">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold">Trusted Electronic BD</h2>
                    <p class="text-blue-100">Your Trusted Partner</p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Company Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">From:</h3>
                    <div class="text-gray-600">
                        <p class="font-medium">Trusted Electronic BD</p>
                        <p>Savar Heymatpur (Singair, Manikgonj)</p>
                        <p>Email: trustedelectronicbd.info@gmail.com</p>
                        <p>Phone: +8801888058362</p>
                    </div>
                </div>

                <!-- Customer Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">To:</h3>
                    <div class="text-gray-600">
                        <p class="font-medium">{{ $order->customer->name }}</p>
                        <p>{{ $order->customer->phone }}</p>
                        <p>{{ $order->customer->division }}, {{ $order->customer->district }}</p>
                        @if($order->shipping_address)
                            <p>{{ $order->shipping_address['address'] ?? '' }}</p>
                        @endif
                    </div>
                </div>

                <!-- Invoice Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Invoice Details:</h3>
                    <div class="text-gray-600">
                        <p><span class="font-medium">Invoice #:</span> {{ $order->order_number }}</p>
                        <p><span class="font-medium">Date:</span> {{ $order->created_at->format('F j, Y') }}</p>
                        <p><span class="font-medium">Status:</span> 
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : 
                                   ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                   ($order->status === 'processing' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                        <p><span class="font-medium">Payment:</span> {{ ucfirst($order->payment_method) }}</p>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase border-b">SKU</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase border-b">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase border-b">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase border-b">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900 border-b">{{ $item->product_name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-500 border-b">{{ $item->product_sku }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-center border-b">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right border-b">৳{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 text-right border-b font-medium">৳{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-full max-w-sm">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal:</span>
                                <span>৳{{ number_format($order->total_amount - $order->shipping_amount + $order->discount_amount, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Discount:</span>
                                <span>-৳{{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                            @endif
                            @if($order->shipping_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span>Shipping:</span>
                                <span>৳{{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            @endif
                            <div class="border-t pt-2">
                                <div class="flex justify-between text-lg font-bold">
                                    <span>Total:</span>
                                    <span>৳{{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($order->coupon_code)
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">
                    <span class="font-medium">Coupon Applied:</span> {{ $order->coupon_code }}
                </p>
            </div>
            @endif

            <!-- Notes -->
            @if($order->notes)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Notes</h3>
                <p class="text-gray-600 bg-gray-50 p-4 rounded-lg">{{ $order->notes }}</p>
            </div>
            @endif

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
                <p>Thank you for your business!</p>
                <p class="mt-2">This invoice was generated on {{ now()->format('F j, Y g:i A') }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between">
        <a href="{{ url()->previous() }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
            ← Back to Orders
        </a>
        <div class="space-x-3">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                🖨️ Print Invoice
            </button>
            <a href="/api/admin/orders/{{ $order->id }}/invoice/download" target="_blank" 
               class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg inline-block">
                📄 Download PDF
            </a>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { margin: 0; }
    .shadow-lg { box-shadow: none !important; }
}
</style>
@endsection