<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'book_id',
        'store_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id');
    }
}
