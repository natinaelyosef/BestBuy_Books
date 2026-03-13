<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'sender_role',
        'message',
        'is_read',
        'is_read_at',
        'attachment_path',
        'attachment_name',
        'attachment_size',
        'attachment_type',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_read_at' => 'datetime',
    ];

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class, 'conversation_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('sender_id', '!=', $userId);
    }

    // Methods
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'is_read_at' => now()
            ]);
        }
    }

    public function hasAttachment()
    {
        return !is_null($this->attachment_path);
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    public function isImage()
    {
        return $this->attachment_type && str_starts_with($this->attachment_type, 'image/');
    }
}