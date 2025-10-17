<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('starts_at', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    /**
     * Check if coupon is valid for use.
     */
    public function isValid()
    {
        return $this->is_active 
            && $this->starts_at <= now() 
            && ($this->expires_at === null || $this->expires_at >= now())
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
