<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CustomerBookController extends Controller
{
    public function show(Book $book)
    {
        $canRent = $book->available_rent > 0;
        $canBuy = $book->available_sale > 0;

        $similarBooks = Book::query()
            ->where('id', '!=', $book->id)
            ->where('genre', $book->genre)
            ->limit(4)
            ->get();

        return view('customer.book_detail', [
            'book' => $book,
            'canRent' => $canRent,
            'canBuy' => $canBuy,
            'similarBooks' => $similarBooks,
            'store' => null,
            'openingHours' => [],
        ]);
    }
}
