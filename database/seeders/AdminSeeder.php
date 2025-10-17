<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        Admin::updateOrCreate(
            ['email' => 'shariarfahim21@gmail.com'],
            [
                'name' => 'Shariar Fahim',
                'email' => 'shariarfahim21@gmail.com',
                'password' => '11223344', // This will be hashed automatically by the model
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // Create a test admin user  
        Admin::updateOrCreate(
            ['email' => 'test@admin.com'],
            [
                'name' => 'Test Admin',
                'email' => 'test@admin.com',
                'password' => 'password', // This will be hashed automatically by the model
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create a moderator user  
        Admin::updateOrCreate(
            ['email' => 'moderator@admin.com'],
            [
                'name' => 'Test Moderator',
                'email' => 'moderator@admin.com',
                'password' => 'password', // This will be hashed automatically by the model
                'role' => 'moderator',
                'is_active' => true,
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Super Admin: shariarfahim21@gmail.com / 11223344');
        $this->command->info('Admin: test@admin.com / password');
        $this->command->info('Moderator: moderator@admin.com / password');
    }
}
