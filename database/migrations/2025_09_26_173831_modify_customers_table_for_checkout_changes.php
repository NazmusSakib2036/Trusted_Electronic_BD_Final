<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('division')->nullable()->after('phone');
            $table->string('district')->nullable()->after('division');
            $table->string('email')->nullable()->change(); // Make email nullable since we're using phone as primary identifier
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['division', 'district']);
            $table->string('email')->nullable(false)->change(); // Revert email to required
        });
    }
};
