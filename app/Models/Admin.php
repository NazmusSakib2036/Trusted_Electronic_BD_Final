<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Mutator to hash password automatically
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Role check methods
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function canManageAdmins()
    {
        return $this->isSuperAdmin();
    }

    // Scope for active admins
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get role display name
    public function getRoleDisplayAttribute()
    {
        return match($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'moderator' => 'Moderator',
            default => 'Unknown'
        };
    }
}
