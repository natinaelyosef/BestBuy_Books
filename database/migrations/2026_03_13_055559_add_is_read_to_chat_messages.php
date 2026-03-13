<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_messages', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('message');
            }
            
            if (!Schema::hasColumn('chat_messages', 'is_read_at')) {
                $table->timestamp('is_read_at')->nullable()->after('is_read');
            }
            
            if (!Schema::hasColumn('chat_messages', 'attachment_path')) {
                $table->string('attachment_path')->nullable()->after('is_read_at');
            }
            
            if (!Schema::hasColumn('chat_messages', 'attachment_name')) {
                $table->string('attachment_name')->nullable()->after('attachment_path');
            }
            
            if (!Schema::hasColumn('chat_messages', 'attachment_size')) {
                $table->integer('attachment_size')->nullable()->after('attachment_name');
            }
            
            if (!Schema::hasColumn('chat_messages', 'attachment_type')) {
                $table->string('attachment_type')->nullable()->after('attachment_size');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn([
                'is_read', 
                'is_read_at', 
                'attachment_path', 
                'attachment_name', 
                'attachment_size', 
                'attachment_type'
            ]);
        });
    }
};