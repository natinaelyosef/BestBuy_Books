<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reported_user_id',
        'reporter_role',
        'subject',
        'description',
        'priority',
        'status',
        'assigned_admin_id',
        'evidence_path',
        'evidence_name',
        'evidence_type',
        'admin_notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // The user who filed the report
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The user being reported
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id');
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function hasEvidence(): bool
    {
        return !is_null($this->evidence_path);
    }

    public function getEvidenceUrlAttribute(): ?string
    {
        return $this->evidence_path ? asset('storage/' . $this->evidence_path) : null;
    }

    public function isEvidenceImage(): bool
    {
        return $this->evidence_type && str_starts_with($this->evidence_type, 'image/');
    }
}