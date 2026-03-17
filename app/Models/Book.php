<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'genre',
        'publication_year',
        'total_copies',
        'available_rent',
        'available_sale',
        'rental_price',
        'sale_price',
        'cover_image_path',
        'pdf_path',
        'pdf_name',
        'pdf_size',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'total_copies' => 'integer',
        'available_rent' => 'integer',
        'available_sale' => 'integer',
        'rental_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pdfRequests()
    {
        return $this->hasMany(BookPdfRequest::class);
    }
}
