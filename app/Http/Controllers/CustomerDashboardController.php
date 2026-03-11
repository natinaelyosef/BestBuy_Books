<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->query('search');
        $selectedGenre = $request->query('genre');
        $selectedAvailability = $request->query('availability');

        $booksQuery = Book::query()->latest('id');

        if ($searchQuery) {
            $booksQuery->where(function ($query) use ($searchQuery) {
                $query->where('title', 'like', '%' . $searchQuery . '%')
                    ->orWhere('author', 'like', '%' . $searchQuery . '%')
                    ->orWhere('genre', 'like', '%' . $searchQuery . '%');
            });
        }

        if ($selectedGenre) {
            $booksQuery->where('genre', $selectedGenre);
        }

        if ($selectedAvailability === 'rent') {
            $booksQuery->where('available_rent', '>', 0);
        } elseif ($selectedAvailability === 'buy') {
            $booksQuery->where('available_sale', '>', 0);
        }

        $totalBooks = (clone $booksQuery)->count();

        $books = $booksQuery
            ->paginate(12)
            ->withQueryString();

        $genres = Book::query()
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        return view('customer.dashboard', [
            'books' => $books,
            'genres' => $genres,
            'totalBooks' => $totalBooks,
            'searchQuery' => $searchQuery,
            'selectedGenre' => $selectedGenre,
            'selectedAvailability' => $selectedAvailability,
        ]);
    }
}
