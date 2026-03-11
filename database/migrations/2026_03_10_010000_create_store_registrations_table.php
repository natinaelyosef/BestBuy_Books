<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('owner_full_name');
            $table->string('email')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('store_type', 50)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('subcity', 100)->nullable();
            $table->string('address')->nullable();
            $table->text('store_description')->nullable();

            $table->boolean('offers_rental')->default(false);
            $table->boolean('offers_sale')->default(false);
            $table->boolean('offers_delivery')->default(false);
            $table->unsignedSmallInteger('delivery_radius')->nullable();
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->json('delivery_methods')->nullable();
            $table->boolean('delivery_bike')->default(false);
            $table->boolean('delivery_car')->default(false);
            $table->boolean('delivery_pickup')->default(false);

            $table->boolean('open_monday')->default(false);
            $table->time('open_time_monday')->nullable();
            $table->time('close_time_monday')->nullable();
            $table->boolean('closed_monday')->default(false);

            $table->boolean('open_tuesday')->default(false);
            $table->time('open_time_tuesday')->nullable();
            $table->time('close_time_tuesday')->nullable();
            $table->boolean('closed_tuesday')->default(false);

            $table->boolean('open_wednesday')->default(false);
            $table->time('open_time_wednesday')->nullable();
            $table->time('close_time_wednesday')->nullable();
            $table->boolean('closed_wednesday')->default(false);

            $table->boolean('open_thursday')->default(false);
            $table->time('open_time_thursday')->nullable();
            $table->time('close_time_thursday')->nullable();
            $table->boolean('closed_thursday')->default(false);

            $table->boolean('open_friday')->default(false);
            $table->time('open_time_friday')->nullable();
            $table->time('close_time_friday')->nullable();
            $table->boolean('closed_friday')->default(false);

            $table->boolean('open_saturday')->default(false);
            $table->time('open_time_saturday')->nullable();
            $table->time('close_time_saturday')->nullable();
            $table->boolean('closed_saturday')->default(false);

            $table->boolean('open_sunday')->default(false);
            $table->time('open_time_sunday')->nullable();
            $table->time('close_time_sunday')->nullable();
            $table->boolean('closed_sunday')->default(false);

            $table->unsignedSmallInteger('rental_period')->nullable();
            $table->decimal('rental_price', 10, 2)->nullable();
            $table->decimal('late_fee', 10, 2)->nullable();
            $table->decimal('security_deposit', 10, 2)->nullable();
            $table->unsignedSmallInteger('max_books')->nullable();
            $table->unsignedSmallInteger('discount_percent')->nullable();

            $table->json('payment_methods')->nullable();
            $table->boolean('agree_terms')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_registrations');
    }
};
