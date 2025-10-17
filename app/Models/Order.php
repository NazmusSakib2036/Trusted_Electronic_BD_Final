<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'total_amount',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'coupon_code',
        'shipping_address',
        'billing_address',
        'payment_method',
        'payment_status',
        'notes',
        'shipped_at',
        'delivered_at'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get the subtotal amount (total - tax - shipping).
     */
    public function getSubtotalAttribute()
    {
        return $this->total_amount - $this->tax_amount - $this->shipping_amount + $this->discount_amount;
    }

    /**
     * Get the customer name attribute.
     */
    public function getCustomerNameAttribute()
    {
        return $this->customer ? $this->customer->name : 'Guest Customer';
    }

    /**
     * Get the customer email attribute.
     */
    public function getCustomerEmailAttribute()
    {
        return $this->customer ? $this->customer->email : 'No email';
    }

    /**
     * Get the customer phone attribute.
     */
    public function getCustomerPhoneAttribute()
    {
        return $this->customer ? $this->customer->phone : null;
    }

    /**
     * Get the customer division attribute.
     */
    public function getCustomerDivisionAttribute()
    {
        return $this->customer ? $this->customer->division : 'No division';
    }

    /**
     * Get the customer district attribute.
     */
    public function getCustomerDistrictAttribute()
    {
        return $this->customer ? $this->customer->district : 'No district';
    }
}
