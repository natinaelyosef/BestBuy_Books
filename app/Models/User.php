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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen_at' => 'datetime',
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