<?php

namespace App\Observers;

use App\Models\Order;
use App\Events\OrderStatusChanged;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if the status has changed
        if ($order->isDirty('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;

            Log::info("OrderObserver: Order status changed", [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'customer_loaded' => $order->relationLoaded('customer'),
                'customer_id' => $order->customer_id
            ]);

            // Load customer if not already loaded
            if (!$order->relationLoaded('customer')) {
                $order->load('customer');
            }

            // Dispatch the event
            event(new OrderStatusChanged($order, $oldStatus, $newStatus));
            
            Log::info("OrderObserver: Event dispatched successfully", [
                'order_id' => $order->id,
                'event' => 'OrderStatusChanged'
            ]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
