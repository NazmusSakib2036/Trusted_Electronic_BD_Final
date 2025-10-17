<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the table with new enum values
        // First, let's check if we're using SQLite
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver");
        
        if ($connection === 'sqlite') {
            // SQLite doesn't support ALTER ENUM, so we'll change the column type
            DB::statement('ALTER TABLE orders ADD COLUMN payment_status_new TEXT DEFAULT "pending"');
            
            // Copy existing data with mapping
            DB::statement('UPDATE orders SET payment_status_new = CASE 
                WHEN payment_status = "paid" THEN "completed"
                ELSE payment_status 
            END');
            
            // Remove old column and rename new one
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
            
            DB::statement('ALTER TABLE orders RENAME COLUMN payment_status_new TO payment_status');
        } else {
            // For MySQL/PostgreSQL
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'processing', 'completed', 'failed', 'refunded') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = config('database.default');
        $connection = config("database.connections.{$driver}.driver");
        
        if ($connection === 'sqlite') {
            DB::statement('ALTER TABLE orders ADD COLUMN payment_status_old TEXT DEFAULT "pending"');
            
            // Map back the data
            DB::statement('UPDATE orders SET payment_status_old = CASE 
                WHEN payment_status = "completed" THEN "paid"
                WHEN payment_status = "processing" THEN "pending"
                ELSE payment_status 
            END');
            
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
            
            DB::statement('ALTER TABLE orders RENAME COLUMN payment_status_old TO payment_status');
        } else {
            DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending'");
        }
    }
};
