<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SmsService;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    private $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display SMS logs and management dashboard
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check permissions
     //   if (!$user || ($user->role !== 'super_admin' && $user->role !== 'admin')) {
        //    abort(403, 'Unauthorized action.');
      //  }

        // Get SMS logs with pagination
        $query = SmsLog::with('order')->recentFirst();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('phone')) {
            $query->where('phone_number', 'LIKE', '%' . $request->phone . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $smsLogs = $query->paginate(20)->withQueryString();

        // Get statistics
        $stats = [
            'total_sent' => SmsLog::successful()->count(),
            'total_failed' => SmsLog::failed()->count(),
            'today_sent' => SmsLog::successful()->whereDate('created_at', today())->count(),
            'this_month' => SmsLog::successful()->whereMonth('created_at', now()->month)->count()
        ];

        return view('admin.sms.index', compact('smsLogs', 'stats'));
    }

    /**
     * Show SMS compose form
     */
    public function compose()
    {
        $user = auth()->user();
        
       // if (!$user || ($user->role !== 'super_admin' && $user->role !== 'admin')) {
       //     abort(403, 'Unauthorized action.');
      //  }

        return view('admin.sms.compose');
    }

    /**
     * Send custom SMS
     */
    public function send(Request $request)
    {
        $user = auth()->user();
        
      //  if (!$user || ($user->role !== 'super_admin' && $user->role !== 'admin')) {
      //      abort(403, 'Unauthorized action.');
     //   }

        $validator = Validator::make($request->all(), [
            'phone_numbers' => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Parse phone numbers (comma or newline separated)
        $phoneNumbers = collect(preg_split('/[\r\n,]+/', $request->phone_numbers))
            ->map(fn($phone) => trim($phone))
            ->filter()
            ->unique()
            ->values();

        if ($phoneNumbers->isEmpty()) {
            return redirect()->back()
                ->withErrors(['phone_numbers' => 'Please provide at least one valid phone number'])
                ->withInput();
        }

        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($phoneNumbers as $phone) {
            $result = $this->smsService->sendSms($phone, $request->message);
            $results[] = [
                'phone' => $phone,
                'success' => $result['success'],
                'message' => $result['success'] ? 'Sent successfully' : ($result['error'] ?? 'Failed to send')
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        $message = "SMS campaign completed: {$successCount} sent, {$failCount} failed";
        
        return redirect()->route('admin.sms.index')
            ->with('success', $message)
            ->with('sms_results', $results);
    }

    /**
     * Check SMS balance
     */
    public function balance()
    {
        $user = auth()->user();
        
     //   if (!$user || ($user->role !== 'super_admin' && $user->role !== 'admin')) {
       //     abort(403, 'Unauthorized action.');
     //   }

        $balance = $this->smsService->getBalance();
        
        return response()->json([
            'balance' => $balance,
            'formatted' => $balance ? "৳ {$balance}" : 'Unable to fetch balance'
        ]);
    }

    /**
     * Show SMS settings
     */
    public function settings()
    {
        $user = auth()->user();
        
      //  if (!$user || $user->role !== 'super_admin') {
       //     abort(403, 'Unauthorized action.');
     //   }

        $config = config('sms');
        
        return view('admin.sms.settings', compact('config'));
    }

    /**
     * Update SMS settings
     */
    public function updateSettings(Request $request)
    {
        $user = auth()->user();

     //   if (!$user || $user->role !== 'super_admin') {
     //       abort(403, 'Unauthorized action.');
      //  }

        $validator = Validator::make($request->all(), [
            'enabled' => 'boolean',
            'api_key' => 'required|string',
            'sender_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // In a real implementation, you'd update the config file or database
        // For now, we'll just show a success message
        return redirect()->back()
            ->with('success', 'SMS settings updated successfully');
    }

    /**
     * Resend failed SMS
     */
    public function resend($id)
    {
        $user = auth()->user();
        
      //  if (!$user || ($user->role !== 'super_admin' && $user->role !== 'admin')) {
        //    abort(403, 'Unauthorized action.');
    //    }

        $smsLog = SmsLog::findOrFail($id);
        
        if ($smsLog->status !== 'failed') {
            return redirect()->back()
                ->withErrors(['message' => 'Only failed SMS can be resent']);
        }

        $result = $this->smsService->sendSms($smsLog->phone_number, $smsLog->message, $smsLog->order_id);
        
        if ($result['success']) {
            return redirect()->back()
                ->with('success', 'SMS resent successfully');
        } else {
            return redirect()->back()
                ->withErrors(['message' => 'Failed to resend SMS: ' . ($result['error'] ?? 'Unknown error')]);
        }
    }
}
