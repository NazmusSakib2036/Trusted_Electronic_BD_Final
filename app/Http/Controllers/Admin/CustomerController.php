<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(): JsonResponse
    {
        $customers = Customer::withCount('orders')
            ->with(['orders' => function($query) {
                $query->select('customer_id', 'total_amount');
            }])
            ->orderBy('name')
            ->paginate(15);
        
        // Add calculated fields
        $customers->getCollection()->transform(function ($customer) {
            $customer->orders_count = $customer->orders_count;
            $customer->total_spent = $customer->orders->sum('total_amount');
            unset($customer->orders); // Remove orders from response to keep it clean
            return $customer;
        });
        
        return response()->json([
            'success' => true,
            'data' => $customers
        ]);
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'division' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $customer = Customer::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): JsonResponse
    {
        // Load customer with orders and order items
        $customer->load(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'orders.orderItems']);
        
        // Calculate statistics
        $customer->orders_count = $customer->orders->count();
        $customer->total_spent = $customer->orders->sum('total_amount');
        $customer->last_order_date = $customer->orders->first()?->created_at;
        
        // Get recent orders (last 5)
        $customer->recent_orders = $customer->orders->take(5)->map(function($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status,
                'total' => $order->total_amount,
                'created_at' => $order->created_at,
            ];
        });
        
        // Remove the full orders relationship to keep response clean
        unset($customer->orders);
        
        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address_line_1' => 'nullable|string',
            'address_line_2' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        if ($customer->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete customer with existing orders'
            ], 422);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    }

    /**
     * Get customer orders.
     */
    public function orders(Customer $customer): JsonResponse
    {
        $orders = $customer->orders()->with('orderItems.product')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    /**
     * Export customers to CSV.
     */
    public function export(Request $request)
    {
        $query = Customer::withCount('orders');
        
        // Apply filters if provided
        if ($request->has('status') && $request->status) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $customers = $query->orderBy('name')->get();
        
        // Calculate additional statistics for each customer
        $customers->transform(function ($customer) {
            $customer->load('orders:id,customer_id,total_amount,created_at');
            $customer->orders_count = $customer->orders_count;
            $customer->total_spent = $customer->orders->sum('total_amount');
            $customer->last_order_date = $customer->orders->max('created_at');
            unset($customer->orders); // Remove orders to keep response clean
            return $customer;
        });
        
        $filename = 'customers_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($customers) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Customer ID',
                'Name',
                'Email',
                'Phone',
                'Status',
                'Registration Date',
                'Date of Birth',
                'Gender',
                'Address Line 1',
                'Address Line 2',
                'City',
                'State',
                'Postal Code',
                'Country',
                'Total Orders',
                'Total Spent',
                'Last Order Date'
            ]);
            
            // Add data rows
            foreach ($customers as $customer) {
                $address = $customer->address_line_1 ?? '';
                if ($customer->address_line_2) {
                    $address .= ', ' . $customer->address_line_2;
                }
                
                fputcsv($file, [
                    $customer->id,
                    $customer->name,
                    $customer->email,
                    $customer->phone ?? '',
                    $customer->is_active ? 'Active' : 'Inactive',
                    $customer->created_at->format('Y-m-d H:i:s'),
                    $customer->date_of_birth ? $customer->date_of_birth->format('Y-m-d') : '',
                    $customer->gender ?? '',
                    $customer->address_line_1 ?? '',
                    $customer->address_line_2 ?? '',
                    $customer->city ?? '',
                    $customer->state ?? '',
                    $customer->postal_code ?? '',
                    $customer->country ?? '',
                    $customer->orders_count,
                    number_format($customer->total_spent, 2),
                    $customer->last_order_date ? $customer->last_order_date->format('Y-m-d H:i:s') : 'Never'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}