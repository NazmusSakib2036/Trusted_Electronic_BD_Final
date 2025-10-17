<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: capitalize;
        }
        .status-pending { background-color: #fbbf24; color: #92400e; }
        .status-processing { background-color: #60a5fa; color: #1e40af; }
        .status-shipped { background-color: #34d399; color: #065f46; }
        .status-delivered { background-color: #10b981; color: #064e3b; }
        .status-cancelled { background-color: #f87171; color: #991b1b; }
        .order-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Status Updated</h1>
        <p>Your order {{ $order->order_number }} has been updated</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $order->customer->first_name }},</p>
        
        <p>We wanted to let you know that your order status has been updated.</p>
        
        <div class="order-details">
            <h3>Order Details</h3>
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Total Amount:</strong> ${{ number_format($order->total_amount, 2) }}</p>
            
            <p><strong>Previous Status:</strong> 
                <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
            </p>
            
            <p><strong>New Status:</strong> 
                <span class="status-badge status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</span>
            </p>
            
            @if($newStatus === 'shipped')
                <div style="background-color: #dbeafe; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <h4 style="color: #1e40af; margin: 0 0 10px 0;">📦 Your order is on its way!</h4>
                    <p style="margin: 0; color: #1e40af;">Your package has been shipped and is on its way to you. You should receive it within 2-5 business days.</p>
                </div>
            @elseif($newStatus === 'delivered')
                <div style="background-color: #d1fae5; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <h4 style="color: #065f46; margin: 0 0 10px 0;">✅ Order Delivered!</h4>
                    <p style="margin: 0; color: #065f46;">Your order has been successfully delivered. We hope you enjoy your purchase!</p>
                </div>
            @elseif($newStatus === 'processing')
                <div style="background-color: #dbeafe; padding: 15px; border-radius: 8px; margin-top: 15px;">
                    <h4 style="color: #1e40af; margin: 0 0 10px 0;">⚡ Order Processing</h4>
                    <p style="margin: 0; color: #1e40af;">We're currently preparing your order for shipment. It will be shipped soon!</p>
                </div>
            @endif
        </div>
        
        @if($order->orderItems->count() > 0)
        <div class="order-details">
            <h3>Items Ordered</h3>
            @foreach($order->orderItems as $item)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #e5e7eb;">
                <div>
                    <strong>{{ $item->product_name }}</strong><br>
                    <small>SKU: {{ $item->product_sku }}</small><br>
                    <small>Quantity: {{ $item->quantity }}</small>
                </div>
                <div style="text-align: right;">
                    <strong>${{ number_format($item->total, 2) }}</strong>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        
        @if($order->shipping_address)
        <div class="order-details">
            <h3>Shipping Address</h3>
            <p>{{ $order->shipping_address['address'] ?? 'N/A' }}</p>
            @if(isset($order->shipping_address['postal_code']))
                <p>Postal Code: {{ $order->shipping_address['postal_code'] }}</p>
            @endif
            @if(isset($order->shipping_address['area']))
                <p>Area: {{ ucfirst($order->shipping_address['area']) }}</p>
            @endif
        </div>
        @endif
        
        <p>If you have any questions about your order, please don't hesitate to contact us.</p>
        
        <p>Thank you for your business!</p>
        
        <p>Best regards,<br>
        <strong>Trusted Electronics Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Trusted Electronics. All rights reserved.</p>
    </div>
</body>
</html>