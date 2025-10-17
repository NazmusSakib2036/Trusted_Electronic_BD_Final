<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    /**
     * Display a listing of the admins.
     */
    public function index(): JsonResponse
    {
        $admins = Admin::select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('name')
            ->paginate(15);
        
        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => 'required|in:super_admin,admin,moderator',
                'is_active' => 'nullable|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, // Will be hashed by model
                'role' => $request->role,
                'is_active' => $request->boolean('is_active', true),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin created successfully',
                'data' => $admin->only(['id', 'name', 'email', 'role', 'is_active', 'created_at'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $admin): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $admin->only(['id', 'name', 'email', 'role', 'is_active', 'created_at'])
        ]);
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, Admin $admin): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
                'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
                'role' => 'required|in:super_admin,admin,moderator',
                'is_active' => 'nullable|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_active' => $request->boolean('is_active', $admin->is_active),
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = $request->password; // Will be hashed by model
            }

            $admin->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Admin updated successfully',
                'data' => $admin->only(['id', 'name', 'email', 'role', 'is_active', 'created_at'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(Admin $admin): JsonResponse
    {
        // Prevent deletion of super admin if it's the last one
        if ($admin->isSuperAdmin()) {
            $superAdminCount = Admin::where('role', 'super_admin')->where('is_active', true)->count();
            if ($superAdminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the last active super admin'
                ], 422);
            }
        }

        try {
            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting admin: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle admin active status
     */
    public function toggleStatus(Admin $admin): JsonResponse
    {
        // Prevent deactivation of super admin if it's the last one
        if ($admin->isSuperAdmin() && $admin->is_active) {
            $activeSuperAdminCount = Admin::where('role', 'super_admin')->where('is_active', true)->count();
            if ($activeSuperAdminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot deactivate the last active super admin'
                ], 422);
            }
        }

        try {
            $admin->update(['is_active' => !$admin->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Admin status updated successfully',
                'data' => $admin->only(['id', 'name', 'email', 'role', 'is_active'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating admin status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admins by role
     */
    public function byRole(string $role): JsonResponse
    {
        if (!in_array($role, ['super_admin', 'admin', 'moderator'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid role specified'
            ], 400);
        }

        $admins = Admin::where('role', $role)
            ->select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('name')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    /**
     * Get active admins only
     */
    public function active(): JsonResponse
    {
        $admins = Admin::active()
            ->select('id', 'name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('name')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $admins
        ]);
    }

    /**
     * Get admin statistics
     */
    public function stats(): JsonResponse
    {
        $stats = [
            'total_admins' => Admin::count(),
            'active_admins' => Admin::where('is_active', true)->count(),
            'inactive_admins' => Admin::where('is_active', false)->count(),
            'super_admins' => Admin::where('role', 'super_admin')->count(),
            'admins' => Admin::where('role', 'admin')->count(),
            'moderators' => Admin::where('role', 'moderator')->count(),
            'recent_admins' => Admin::latest()
                ->select('id', 'name', 'email', 'role', 'is_active', 'created_at')
                ->limit(5)
                ->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}