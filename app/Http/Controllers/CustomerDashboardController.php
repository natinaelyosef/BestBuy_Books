<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class CustomerDashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/customer/dashboard'));
        }

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

        $sessionWishlist = array_values(array_unique(array_map('intval', $request->session()->get('wishlist', []))));
        $wishlistIds = $sessionWishlist;

        if (Schema::hasTable('wishlist_items')) {
            if (!empty($sessionWishlist)) {
                $booksForSync = Book::query()
                    ->whereIn('id', $sessionWishlist)
                    ->get()
                    ->keyBy('id');

                foreach ($sessionWishlist as $bookId) {
                    $book = $booksForSync->get($bookId);
                    if ($book) {
                        WishlistItem::firstOrCreate(
                            ['customer_id' => $request->user()->id, 'book_id' => $book->id],
                            ['store_id' => $book->user_id]
                        );
                    }
                }
            }

            $wishlistIds = WishlistItem::query()
                ->where('customer_id', $request->user()->id)
                ->pluck('book_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return view('customer.dashboard', [
            'books' => $books,
            'genres' => $genres,
            'totalBooks' => $totalBooks,
            'searchQuery' => $searchQuery,
            'selectedGenre' => $selectedGenre,
            'selectedAvailability' => $selectedAvailability,
            'wishlistIds' => $wishlistIds,
        ])->withViewData([
            'breadcrumb' => [
                ['url' => route('customer.dashboard'), 'label' => 'Customer Dashboard'],
                ['url' => route('customer.books.index'), 'label' => 'Books'],
            ]
        ]);
    }
}
