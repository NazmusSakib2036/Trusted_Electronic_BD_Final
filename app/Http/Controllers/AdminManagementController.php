<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $admins = Admin::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'moderator' => 'Moderator'
        ];
        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,moderator',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $admin = Admin::findOrFail($id);
        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $admin = Admin::findOrFail($id);
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'moderator' => 'Moderator'
        ];
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $admin = Admin::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'nullable|min:6|confirmed',
            'role' => 'required|in:super_admin,admin,moderator',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active')
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        $admin->update($updateData);

        return redirect()->route('admin.admins.index')->with('success', 'Admin user updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            abort(403, 'You do not have permission to manage admins.');
        }
        
        $admin = Admin::findOrFail($id);
        
        // Prevent deleting own account
        if ($admin->id === Auth::guard('admin')->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Prevent deleting the last super admin
        if ($admin->isSuperAdmin() && Admin::where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last super admin!');
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin user deleted successfully!');
    }

    /**
     * Toggle admin status
     */
    public function toggleStatus(string $id)
    {
        // Check permissions
        if (!Auth::guard('admin')->user()->canManageAdmins()) {
            return response()->json(['error' => 'You do not have permission to manage admins.'], 403);
        }
        
        $admin = Admin::findOrFail($id);
        
        // Prevent disabling own account
        if ($admin->id === Auth::guard('admin')->id()) {
            return response()->json(['error' => 'You cannot disable your own account!'], 400);
        }

        $admin->update(['is_active' => !$admin->is_active]);
        
        return response()->json([
            'success' => true,
            'message' => 'Admin status updated successfully!',
            'is_active' => $admin->is_active
        ]);
    }
}
