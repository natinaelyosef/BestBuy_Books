<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('users')->cascadeOnDelete();
            $table->string('order_type', 20)->default('buy');
            $table->string('status', 30)->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('delivery_option', 50)->default('pickup');
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->string('delivery_address')->nullable();
            $table->text('notes')->nullable();
            $table->text('store_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
