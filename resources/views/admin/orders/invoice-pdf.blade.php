<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }
        .invoice-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .invoice-header .order-number {
            margin-top: 5px;
            font-size: 14px;
            opacity: 0.9;
        }
        .company-info {
            float: right;
            text-align: right;
        }
        .clear { clear: both; }
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-details > div {
            display: table-cell;
            width: 33.33%;
            vertical-align: top;
            padding-right: 20px;
        }
        .invoice-details h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #333;
        }
        .invoice-details p {
            margin: 3px 0;
            font-size: 14px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .items-table .text-right {
            text-align: right;
        }
        .items-table .text-center {
            text-align: center;
        }
        .totals {
            float: right;
            width: 300px;
            background-color: #f8f9fa;
            padding: 15px;
            margin-top: 20px;
        }
        .totals .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .totals .total-final {
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pending { background-color: #fbbf24; color: #92400e; }
        .status-processing { background-color: #60a5fa; color: #1e40af; }
        .status-shipped { background-color: #34d399; color: #065f46; }
        .status-delivered { background-color: #10b981; color: #064e3b; }
        .status-cancelled { background-color: #f87171; color: #991b1b; }
    </style>
</head>
<body>
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div style="float: left;">
            <h1>INVOICE</h1>
            <div class="order-number">{{ $order->order_number }}</div>
        </div>
        <div class="company-info">
            <h2>Trusted Electronic BD</h2>
            <p>Your Trusted Partner</p>
        </div>
        <div class="clear"></div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <!-- Company Info -->
        <div>
            <h3>From:</h3>
            <p><strong>Trusted Electronic BD</strong></p>
            <p>Dhaka, Bangladesh</p>
            <p>Email: info@trustedelectronicbd.com</p>
            <p>Phone: +880 1234-567890</p>
        </div>

        <!-- Customer Info -->
        <div>
            <h3>To:</h3>
            <p><strong>{{ $order->customer->name }}</strong></p>
            <p>{{ $order->customer->phone }}</p>
            <p>{{ $order->customer->division }}, {{ $order->customer->district }}</p>
            @if($order->shipping_address)
                <p>{{ $order->shipping_address['address'] ?? '' }}</p>
            @endif
        </div>

        <!-- Invoice Details -->
        <div>
            <h3>Invoice Details:</h3>
            <p><strong>Invoice #:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
            <p><strong>Status:</strong> 
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </p>
            <p><strong>Payment:</strong> {{ ucfirst($order->payment_method) }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <h3>Order Items</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->product_sku }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">৳{{ number_format($item->price, 2) }}</td>
                <td class="text-right"><strong>৳{{ number_format($item->total, 2) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <div class="total-row">
            <span>Subtotal:</span>
            <span>৳{{ number_format($order->total_amount - $order->shipping_amount + $order->discount_amount, 2) }}</span>
        </div>
        @if($order->discount_amount > 0)
        <div class="total-row" style="color: green;">
            <span>Discount:</span>
            <span>-৳{{ number_format($order->discount_amount, 2) }}</span>
        </div>
        @endif
        @if($order->shipping_amount > 0)
        <div class="total-row">
            <span>Shipping:</span>
            <span>৳{{ number_format($order->shipping_amount, 2) }}</span>
        </div>
        @endif
        <div class="total-row total-final">
            <span>Total:</span>
            <span>৳{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="clear"></div>

    <!-- Payment Info -->
    @if($order->coupon_code)
    <div style="margin-top: 20px; padding: 10px; background-color: #d1fae5; border: 1px solid #10b981; border-radius: 4px;">
        <p style="margin: 0; color: #065f46;">
            <strong>Coupon Applied:</strong> {{ $order->coupon_code }}
        </p>
    </div>
    @endif

    <!-- Notes -->
    @if($order->notes)
    <div style="margin-top: 20px;">
        <h3>Notes</h3>
        <p style="background-color: #f8f9fa; padding: 10px; border-radius: 4px;">{{ $order->notes }}</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>This invoice was generated on {{ now()->format('F j, Y g:i A') }}</p>
    </div>
</body>
</html>