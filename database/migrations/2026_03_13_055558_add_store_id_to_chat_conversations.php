<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('chat_conversations', 'store_id')) {
                $table->foreignId('store_id')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnDelete()
                    ->after('customer_id');
            }
            
            if (!Schema::hasColumn('chat_conversations', 'book_id')) {
                $table->foreignId('book_id')
                    ->nullable()
                    ->constrained('books')
                    ->nullOnDelete()
                    ->after('store_id');
            }
            
            if (!Schema::hasColumn('chat_conversations', 'last_message')) {
                $table->text('last_message')->nullable()->after('last_message_at');
            }
            
            if (!Schema::hasColumn('chat_conversations', 'last_message_sender_id')) {
                $table->foreignId('last_message_sender_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete()
                    ->after('last_message');
            }
        });
    }

    public function down(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropForeign(['book_id']);
            $table->dropForeign(['last_message_sender_id']);
            $table->dropColumn(['store_id', 'book_id', 'last_message', 'last_message_sender_id']);
        });
    }
};