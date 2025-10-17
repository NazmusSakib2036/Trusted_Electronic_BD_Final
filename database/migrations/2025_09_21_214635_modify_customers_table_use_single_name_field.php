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
        Schema::table('customers', function (Blueprint $table) {
            // First, combine existing data into a single name field
            $table->string('name')->nullable()->after('id');
        });

        // Update existing records to combine first_name and last_name
        DB::statement("UPDATE customers SET name = CONCAT(IFNULL(first_name, ''), ' ', IFNULL(last_name, ''))");
        
        // Clean up the name field (remove extra spaces)
        DB::statement("UPDATE customers SET name = TRIM(name)");

        Schema::table('customers', function (Blueprint $table) {
            // Now drop the old columns
            $table->dropColumn(['first_name', 'last_name']);
            // Make name field required
            $table->string('name')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add back the original columns
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
        });

        // Split the name back into first_name and last_name
        DB::statement("UPDATE customers SET first_name = SUBSTRING_INDEX(name, ' ', 1), last_name = SUBSTRING_INDEX(name, ' ', -1) WHERE name IS NOT NULL");

        Schema::table('customers', function (Blueprint $table) {
            // Drop the name column
            $table->dropColumn('name');
        });
    }
};
