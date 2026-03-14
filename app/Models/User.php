<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'account_type',
        'avatar',
        'phone',
        'bio',
        'last_seen_at',
        'is_active',
        'is_banned',
        'ban_reason',
        'banned_at',
        'warning_count',
        'is_restricted',
        'restricted_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
        'is_restricted' => 'boolean',
        'banned_at' => 'datetime',
        'restricted_until' => 'datetime',
    ];

    // Add this relationship - Books owned by the user (for store owners)
    public function books()
    {
        return $this->hasMany(Book::class, 'user_id');
    }

    // Chat Relationships
    public function customerConversations()
    {
        return $this->hasMany(ChatConversation::class, 'customer_id');
    }

    public function storeConversations()
    {
        return $this->hasMany(ChatConversation::class, 'store_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasManyThrough(
            ChatMessage::class,
            ChatConversation::class,
            'store_id', // Foreign key on conversations table...
            'conversation_id', // Foreign key on messages table...
            'id', // Local key on users table...
            'id' // Local key on conversations table...
        );
    }

    // Helper methods
    public function isOnline()
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    public function canLogin(): bool
    {
        return ($this->is_active ?? true) && !($this->is_banned ?? false);
    }

    public function isBanned(): bool
    {
        return (bool) ($this->is_banned ?? false);
    }

    public function isRestricted(): bool
    {
        if (!($this->is_restricted ?? false)) return false;
        if ($this->restricted_until && $this->restricted_until->isPast()) {
            $this->update(['is_restricted' => false, 'restricted_until' => null]);
            return false;
        }
        return true;
    }

    public function statusBadge(): string
    {
        if ($this->isBanned()) return 'Banned';
        if (!($this->is_active ?? true)) return 'Inactive';
        if ($this->isRestricted()) return 'Restricted';
        return 'Active';
    }

    public function unreadMessagesCount()
    {
        return ChatMessage::whereHas('conversation', function ($query) {
            if ($this->account_type === 'store_owner') {
                $query->where('store_id', $this->id);
            } else {
                $query->where('customer_id', $this->id);
            }
        })->where('sender_id', '!=', $this->id)
          ->where('is_read', false)
          ->count();
    }
}