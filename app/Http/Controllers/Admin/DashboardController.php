<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display dashboard data.
     */
    public function index(): JsonResponse
    {
        $data = [
            'total_orders' => Order::count(),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_categories' => Category::count(),
            'recent_orders' => Order::with(['customer', 'orderItems'])
                ->latest()
                ->limit(5)
                ->get(),
            'low_stock_products' => Product::where('stock_quantity', '<=', 10)
                ->with('category')
                ->limit(10)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Get dashboard statistics.
     */
    public function stats(): JsonResponse
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $thisMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        $orderStatusCounts = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return response()->json([
            'success' => true,
            'data' => [
                'total_revenue' => $totalRevenue,
                'pending_orders' => $pendingOrders,
                'today_orders' => $todayOrders,
                'this_month_revenue' => $thisMonthRevenue,
                'top_products' => $topProducts,
                'order_status_counts' => $orderStatusCounts
            ]
        ]);
    }
}
