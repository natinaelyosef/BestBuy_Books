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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('genre', 100);
            $table->unsignedSmallInteger('publication_year')->nullable();
            $table->unsignedInteger('total_copies');
            $table->unsignedInteger('available_rent');
            $table->unsignedInteger('available_sale');
            $table->decimal('rental_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->string('cover_image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
