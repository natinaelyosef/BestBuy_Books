<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add fields to issue_reports
        Schema::table('issue_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('issue_reports', 'reported_user_id')) {
                $table->foreignId('reported_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete()
                    ->after('user_id');
            }
            if (!Schema::hasColumn('issue_reports', 'reporter_role')) {
                $table->string('reporter_role')->nullable()->after('reported_user_id'); // 'customer' or 'store_owner'
            }
            if (!Schema::hasColumn('issue_reports', 'evidence_path')) {
                $table->string('evidence_path')->nullable()->after('description');
            }
            if (!Schema::hasColumn('issue_reports', 'evidence_name')) {
                $table->string('evidence_name')->nullable()->after('evidence_path');
            }
            if (!Schema::hasColumn('issue_reports', 'evidence_type')) {
                $table->string('evidence_type')->nullable()->after('evidence_name');
            }
            if (!Schema::hasColumn('issue_reports', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('assigned_admin_id');
            }
        });

        // Add moderation fields to users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('account_type');
            }
            if (!Schema::hasColumn('users', 'is_banned')) {
                $table->boolean('is_banned')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('users', 'ban_reason')) {
                $table->text('ban_reason')->nullable()->after('is_banned');
            }
            if (!Schema::hasColumn('users', 'banned_at')) {
                $table->timestamp('banned_at')->nullable()->after('ban_reason');
            }
            if (!Schema::hasColumn('users', 'warning_count')) {
                $table->unsignedSmallInteger('warning_count')->default(0)->after('banned_at');
            }
            if (!Schema::hasColumn('users', 'is_restricted')) {
                $table->boolean('is_restricted')->default(false)->after('warning_count');
            }
            if (!Schema::hasColumn('users', 'restricted_until')) {
                $table->timestamp('restricted_until')->nullable()->after('is_restricted');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'last_seen_at')) {
                $table->timestamp('last_seen_at')->nullable()->after('bio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('issue_reports', function (Blueprint $table) {
            $table->dropForeign(['reported_user_id']);
            $table->dropColumn(['reported_user_id', 'reporter_role', 'evidence_path', 'evidence_name', 'evidence_type', 'admin_notes']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'is_banned', 'ban_reason', 'banned_at', 'warning_count', 'is_restricted', 'restricted_until']);
        });
    }
};
