<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(): JsonResponse
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'data' => $coupons
        ]);
    }

    /**
     * Store a newly created coupon.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|unique:coupons|max:255',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at'
        ]);

        $coupon = Coupon::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Coupon created successfully',
            'data' => $coupon
        ], 201);
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $coupon
        ]);
    }

    /**
     * Update the specified coupon.
     */
    public function update(Request $request, Coupon $coupon): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at'
        ]);

        $coupon->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Coupon updated successfully',
            'data' => $coupon
        ]);
    }

    /**
     * Remove the specified coupon.
     */
    public function destroy(Coupon $coupon): JsonResponse
    {
        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully'
        ]);
    }

    /**
     * Toggle coupon status.
     */
    public function toggleStatus(Coupon $coupon): JsonResponse
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon status updated',
            'data' => $coupon
        ]);
    }

    /**
     * Validate coupon for frontend use.
     */
    public function validate(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code'
            ], 400);
        }

        // Check if coupon is expired
        if ($coupon->expires_at && now()->isAfter($coupon->expires_at)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon has expired'
            ], 400);
        }

        // Check if coupon has started
        if ($coupon->starts_at && now()->isBefore($coupon->starts_at)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon is not yet active'
            ], 400);
        }

        // Check minimum amount requirement
        if ($coupon->minimum_amount && $request->total < $coupon->minimum_amount) {
            return response()->json([
                'valid' => false,
                'message' => "Minimum purchase amount is {$coupon->minimum_amount}"
            ], 400);
        }

        // Check usage limit
        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon usage limit reached'
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Coupon is valid',
            'coupon' => [
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount_type' => $coupon->type,
                'discount_value' => $coupon->value,
                'max_discount' => $coupon->maximum_discount
            ]
        ]);
    }
}