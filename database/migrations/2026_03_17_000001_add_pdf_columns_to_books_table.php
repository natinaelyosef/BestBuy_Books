<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('pdf_path')->nullable()->after('cover_image_path');
            $table->string('pdf_name')->nullable()->after('pdf_path');
            $table->unsignedBigInteger('pdf_size')->nullable()->after('pdf_name');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['pdf_path', 'pdf_name', 'pdf_size']);
        });
    }
};
