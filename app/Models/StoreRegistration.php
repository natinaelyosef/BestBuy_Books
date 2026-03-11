<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreRegistration extends Model
{
    protected $guarded = [];

    protected $casts = [
        'offers_rental' => 'boolean',
        'offers_sale' => 'boolean',
        'offers_delivery' => 'boolean',
        'delivery_methods' => 'array',
        'delivery_bike' => 'boolean',
        'delivery_car' => 'boolean',
        'delivery_pickup' => 'boolean',
        'open_monday' => 'boolean',
        'closed_monday' => 'boolean',
        'open_tuesday' => 'boolean',
        'closed_tuesday' => 'boolean',
        'open_wednesday' => 'boolean',
        'closed_wednesday' => 'boolean',
        'open_thursday' => 'boolean',
        'closed_thursday' => 'boolean',
        'open_friday' => 'boolean',
        'closed_friday' => 'boolean',
        'open_saturday' => 'boolean',
        'closed_saturday' => 'boolean',
        'open_sunday' => 'boolean',
        'closed_sunday' => 'boolean',
        'payment_methods' => 'array',
        'agree_terms' => 'boolean',
    ];
}
