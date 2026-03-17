<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'book_id',
        'item_type',
        'quantity',
        'rental_days',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'rental_days' => 'integer',
        'price' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
