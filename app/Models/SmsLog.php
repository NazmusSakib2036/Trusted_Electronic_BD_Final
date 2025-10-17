<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SmsLog extends Model
{
    protected $fillable = [
        'phone_number',
        'message',
        'response',
        'status',
        'order_id',
        'type',
        'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeForPhone($query, $phone)
    {
        return $query->where('phone_number', $phone);
    }

    public function scopeRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getFormattedSentAtAttribute()
    {
        return $this->sent_at ? $this->sent_at->format('M d, Y g:i A') : 'Not sent';
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'sent' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'pending' => 'bg-yellow-100 text-yellow-800'
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Methods
    public function markAsSent($response = null)
    {
        $this->update([
            'status' => 'sent',
            'response' => $response,
            'sent_at' => now()
        ]);
    }

    public function markAsFailed($response = null)
    {
        $this->update([
            'status' => 'failed',
            'response' => $response
        ]);
    }

    // Static methods
    public static function logSms($phone, $message, $response = null, $status = 'pending', $orderId = null, $type = 'order_status')
    {
        return self::create([
            'phone_number' => $phone,
            'message' => $message,
            'response' => $response,
            'status' => $status,
            'order_id' => $orderId,
            'type' => $type,
            'sent_at' => $status === 'sent' ? now() : null
        ]);
    }
}
