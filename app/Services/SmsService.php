<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\SmsLog;

class SmsService
{
    private $api_url;
    private $api_key;
    private $sender_id;
    
    public function __construct()
    {
        $this->api_url = config('sms.bulksmsbd.url', 'http://bulksmsbd.net/api/smsapi');
        $this->api_key = config('sms.bulksmsbd.api_key', '4CaUBCVpiLpBNKd2YrqI');
        $this->sender_id = config('sms.bulksmsbd.sender_id', '8809617629096');
    }

    /**
     * Send SMS using BulkSMSBD API
     */
    public function sendSms($number, $message)
    {
        try {
            // Ensure number has country code
            $number = $this->formatPhoneNumber($number);
            
            $data = [
                'api_key' => $this->api_key,
                'senderid' => $this->sender_id,
                'number' => $number,
                'message' => $message,
                'type' => 'text'
            ];

            // Use cURL for sending SMS
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->api_url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            
            if (curl_errno($ch)) {
                throw new Exception('cURL Error: ' . curl_error($ch));
            }
            
            curl_close($ch);

            // Log the SMS attempt
            $this->logSms($number, $message, $response, $httpCode);
            
            $success = $this->isSuccessResponse($response);
            $error = null;
            
            // Extract error message if not successful
            if (!$success && is_string($response) && str_starts_with(trim($response), '{')) {
                $decoded = json_decode($response, true);
                if ($decoded && isset($decoded['error_message'])) {
                    $error = $decoded['error_message'];
                    
                    // Special handling for IP whitelist error in development
                    if (strpos($error, 'not Whitelisted') !== false) {
                        Log::warning('SMS blocked due to IP whitelist, but continuing in development mode', [
                            'number' => $number,
                            'message' => $message,
                            'error' => $error
                        ]);
                        
                        // In development, treat IP whitelist as a warning but continue
                        if (config('app.env') === 'local' || config('app.debug')) {
                            $success = true; // Override success for development
                            $error = 'Development mode: IP whitelist bypassed';
                        }
                    }
                }
            }
            
            return [
                'success' => $success,
                'response' => $response,
                'code' => $httpCode,
                'error' => $error
            ];
            
        } catch (Exception $e) {
            Log::error('SMS Send Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'response' => null
            ];
        }
    }

    /**
     * Send bulk SMS to multiple numbers
     */
    public function sendBulkSms($numbers, $message)
    {
        $results = [];
        
        foreach ($numbers as $number) {
            $results[] = [
                'number' => $number,
                'result' => $this->sendSms($number, $message)
            ];
            
            // Small delay to avoid overwhelming the API
            usleep(500000); // 0.5 seconds
        }
        
        return $results;
    }

    /**
     * Get account balance
     */
    public function getBalance()
    {
        try {
            $balanceUrl = 'http://bulksmsbd.net/api/getBalanceApi';
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $balanceUrl . '?api_key=' . $this->api_key);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
            
        } catch (Exception $e) {
            Log::error('SMS Balance Check Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format phone number with country code
     */
    private function formatPhoneNumber($number)
    {
        // Remove any non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);
        
        // Add Bangladesh country code if not present
        if (!str_starts_with($number, '880')) {
            if (str_starts_with($number, '0')) {
                $number = '88' . $number;
            } else {
                $number = '880' . $number;
            }
        }
        
        return $number;
    }

    /**
     * Check if SMS response indicates success
     */
    private function isSuccessResponse($response)
    {
        // Handle JSON response format
        if (is_string($response) && str_starts_with(trim($response), '{')) {
            $decoded = json_decode($response, true);
            if ($decoded && isset($decoded['response_code'])) {
                return $decoded['response_code'] == 202;
            }
        }
        
        // Handle plain text response
        return $response == '202' || str_contains($response, '202');
    }

    /**
     * Log SMS attempts for audit trail
     */
    private function logSms($number, $message, $response, $httpCode)
    {
        // Create SMS log entry using the model's static method
        SmsLog::logSms(
            $number,
            $message,
            $response,
            $this->isSuccessResponse($response) ? 'sent' : 'failed'
        );
        
        // Also log to Laravel logs
        Log::info('SMS Sent', [
            'number' => $number,
            'message' => $message,
            'response' => $response,
            'http_code' => $httpCode
        ]);
    }

    /**
     * Get SMS response message meaning
     */
    public function getResponseMessage($code)
    {
        $messages = [
            '202' => 'SMS Submitted Successfully',
            '1001' => 'Invalid Number',
            '1002' => 'Sender ID not correct/Sender ID is disabled',
            '1003' => 'Please Required all fields/Contact Your System Administrator',
            '1005' => 'Internal Error',
            '1006' => 'Balance Validity Not Available',
            '1007' => 'Balance Insufficient',
            '1011' => 'User ID not found',
            '1012' => 'Masking SMS must be sent in Bengali',
            '1013' => 'Sender ID has not found Gateway by API key',
            '1014' => 'Sender Type Name not found using this sender by API key',
            '1015' => 'Sender ID has not found Any Valid Gateway by API key',
            '1016' => 'Sender Type Name Active Price Info not found by this sender ID',
            '1017' => 'Sender Type Name Price Info not found by this sender ID',
            '1018' => 'The Owner of this (username) Account is disabled',
            '1019' => 'The (sender type name) Price of this (username) Account is disabled',
            '1020' => 'The parent of this account is not found',
            '1021' => 'The parent active (sender type name) price of this account is not found'
        ];
        
        return $messages[$code] ?? 'Unknown Response Code';
    }

    /**
     * Send order status SMS notification
     */
    public function sendOrderStatusSms($order, $newStatus)
    {
        if (!$order->customer_phone) {
            Log::warning('Cannot send SMS: No phone number for order #' . $order->id);
            return false;
        }

        $message = $this->buildOrderStatusMessage($order, $newStatus);
        
        // Create SMS log entry with order ID
        $smsLog = SmsLog::logSms(
            $order->customer_phone, 
            $message, 
            null, 
            'pending', 
            $order->id, 
            'order_status'
        );
        
        // Send SMS
        $result = $this->sendSms($order->customer_phone, $message);
        
        // Update the SMS log we just created
        if ($result['success']) {
            $smsLog->markAsSent($result['response'] ?? null);
        } else {
            $smsLog->markAsFailed($result['error'] ?? 'Unknown error');
        }
        
        return $result;
    }

    /**
     * Build SMS message for order status
     */
    private function buildOrderStatusMessage($order, $status)
    {
        $messages = [
            'pending' => "Dear {customer}, your order #{order_id} is being processed. Total: {total}. We'll notify you once it's ready. Thanks! - TrustedElectronics",
            'confirmed' => "Great news! Your order #{order_id} has been confirmed. Total: {total}. We're preparing your items. Thanks for choosing TrustedElectronics!",
            'processing' => "Your order #{order_id} is now being processed. Total: {total}. We'll update you on shipping details soon. - TrustedElectronics",
            'shipped' => "Your order #{order_id} has been shipped! Total: {total}. Track your delivery for updates. Thanks! - TrustedElectronics",
            'delivered' => "Order #{order_id} delivered successfully! Total: {total}. Thank you for shopping with TrustedElectronics. Rate your experience!",
            'cancelled' => "Sorry, your order #{order_id} has been cancelled. Total: {total}. Refund will be processed if applicable. Contact us for support. - TrustedElectronics"
        ];

        $template = $messages[$status] ?? "Order #{order_id} status updated to: {status}. Total: {total}. - TrustedElectronics";
        
        return str_replace([
            '{customer}',
            '{order_id}',
            '{total}',
            '{status}'
        ], [
            $order->customer_name,
            $order->id,
            '৳' . number_format($order->total_amount, 2),
            ucfirst($status)
        ], $template);
    }
}