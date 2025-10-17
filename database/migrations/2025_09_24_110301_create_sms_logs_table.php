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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->text('message');
            $table->string('response')->nullable();
            $table->enum('status', ['sent', 'failed', 'pending'])->default('pending');
            $table->string('order_id')->nullable();
            $table->string('type')->default('order_status'); // order_status, promotional, etc.
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['phone_number', 'created_at']);
            $table->index(['status', 'created_at']);
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
