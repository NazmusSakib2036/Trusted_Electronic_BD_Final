<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Order Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .order-info {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }
        .customer-info {
            background-color: #e3f2fd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            background-color: #f1f8e9;
            border-radius: 8px;
            padding: 20px;
            text-align: right;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .payment-cod {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .payment-bkash {
            background-color: #e91e63;
            color: white;
        }
        .payment-rocket {
            background-color: #8e24aa;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 New Order Received!</h1>
            <p>A new order has been placed on Trusted Electronics</p>
        </div>

        <div class="content">
            <div class="order-info">
                <h2>Order Details</h2>
                <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                <p><strong>Status:</strong> <span class="status-badge status-pending">{{ ucfirst($order->status) }}</span></p>
                <p><strong>Payment Method:</strong> 
                    <span class="status-badge payment-{{ $order->payment_method }}">
                        {{ strtoupper($order->payment_method) }}
                    </span>
                </p>
            </div>

            <div class="customer-info">
                <h2>Customer Information</h2>
                <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email }}</p>
                <p><strong>Phone:</strong> {{ $order->customer->phone }}</p>
                <p><strong>Shipping Address:</strong><br>
                    {{ $order->shipping_address['address'] ?? 'N/A' }}<br>
                    @if(isset($order->shipping_address['area']))
                        Area: {{ $order->shipping_address['area'] }}<br>
                    @endif
                    @if(isset($order->shipping_address['postal_code']))
                        Postal Code: {{ $order->shipping_address['postal_code'] }}
                    @endif
                </p>
                @if(isset($order->shipping_address['instructions']) && $order->shipping_address['instructions'])
                    <p><strong>Delivery Instructions:</strong> {{ $order->shipping_address['instructions'] }}</p>
                @endif
            </div>

            <h2>Order Items</h2>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->product_sku }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>৳{{ number_format($item->price, 2) }}</td>
                        <td>৳{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <p><strong>Subtotal:</strong> ৳{{ number_format($order->total_amount - $order->shipping_amount + $order->discount_amount, 2) }}</p>
                @if($order->discount_amount > 0)
                    <p><strong>Discount:</strong> -৳{{ number_format($order->discount_amount, 2) }}
                        @if($order->coupon_code)
                            ({{ $order->coupon_code }})
                        @endif
                    </p>
                @endif
                @if($order->shipping_amount > 0)
                    <p><strong>Shipping:</strong> ৳{{ number_format($order->shipping_amount, 2) }}</p>
                @endif
                <hr>
                <h3><strong>Total Amount: ৳{{ number_format($order->total_amount, 2) }}</strong></h3>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from Trusted Electronics Admin Panel.</p>
            <p>Please process this order as soon as possible.</p>
        </div>
    </div>
</body>
</html>