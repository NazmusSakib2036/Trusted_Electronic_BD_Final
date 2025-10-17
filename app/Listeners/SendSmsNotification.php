<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendSmsNotification
{
    private $smsService;

    /**
     * Create the event listener.
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(OrderStatusChanged $event): void
    {
        try {
            Log::info("SendSmsNotification: Event received", [
                'order_id' => $event->order->id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'customer_phone' => $event->order->customer_phone ?? 'No phone'
            ]);

            // Prevent duplicate SMS sends within 30 seconds
            $cacheKey = "sms_sent_{$event->order->id}_{$event->newStatus}";
            if (\Cache::has($cacheKey)) {
                Log::info("SendSmsNotification: Duplicate SMS prevented", [
                    'order_id' => $event->order->id,
                    'status' => $event->newStatus,
                    'cache_key' => $cacheKey
                ]);
                return;
            }

            // Check if SMS notifications are enabled
            if (!config('sms.enabled', true)) {
                Log::info('SMS notifications are disabled');
                return;
            }

            // Check if we should send SMS for this status change
            $sendSmsStatuses = config('sms.notifications.order_status_changes', [
                'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'
            ]);

            if (!in_array($event->newStatus, $sendSmsStatuses)) {
                Log::info("SMS not configured for status: {$event->newStatus}");
                return;
            }

            // Check if customer has a phone number
            if (empty($event->order->customer_phone)) {
                Log::warning("No phone number for order #{$event->order->id}", [
                    'customer_id' => $event->order->customer_id,
                    'customer_loaded' => $event->order->relationLoaded('customer'),
                    'customer_exists' => $event->order->customer ? 'yes' : 'no',
                    'customer_phone' => $event->order->customer ? $event->order->customer->phone : 'no customer'
                ]);
                return;
            }

            // Send the SMS notification
            Log::info("Attempting to send SMS for order #{$event->order->id}");
            $result = $this->smsService->sendOrderStatusSms($event->order, $event->newStatus);

            if ($result && $result['success']) {
                Log::info("SMS sent successfully for order #{$event->order->id} status change to {$event->newStatus}");
                
                // Cache the SMS send to prevent duplicates for 30 seconds
                $cacheKey = "sms_sent_{$event->order->id}_{$event->newStatus}";
                \Cache::put($cacheKey, true, 30);
                
            } else {
                Log::error("Failed to send SMS for order #{$event->order->id}", [
                    'order_id' => $event->order->id,
                    'status' => $event->newStatus,
                    'error' => $result['error'] ?? 'Unknown error'
                ]);
            }

        } catch (\Exception $e) {
            Log::error("Exception in SMS listener for order #{$event->order->id}: " . $e->getMessage(), [
                'order_id' => $event->order->id,
                'status' => $event->newStatus,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderStatusChanged $event, \Throwable $exception): void
    {
        Log::error("SMS notification job failed for order #{$event->order->id}", [
            'order_id' => $event->order->id,
            'status' => $event->newStatus,
            'exception' => $exception->getMessage()
        ]);
    }
}
