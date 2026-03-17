<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPdfRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'customer_id',
        'store_id',
        'status',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id');
    }
}
