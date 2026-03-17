<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'store_id',
        'order_type',
        'status',
        'total_amount',
        'delivery_option',
        'delivery_fee',
        'delivery_address',
        'notes',
        'store_notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
