<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookPdfRequest;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Schema;

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

        $wishlistIds = [];
        $pdfRequest = null;
        if (auth()->check()) {
            $sessionWishlist = array_values(array_unique(array_map('intval', session('wishlist', []))));
            $wishlistIds = $sessionWishlist;

            if (Schema::hasTable('wishlist_items')) {
                if (!empty($sessionWishlist)) {
                    $booksForSync = Book::query()
                        ->whereIn('id', $sessionWishlist)
                        ->get()
                        ->keyBy('id');

                    foreach ($sessionWishlist as $bookId) {
                        $syncBook = $booksForSync->get($bookId);
                        if ($syncBook) {
                            WishlistItem::firstOrCreate(
                                ['customer_id' => auth()->id(), 'book_id' => $syncBook->id],
                                ['store_id' => $syncBook->user_id]
                            );
                        }
                    }
                }

                $wishlistIds = WishlistItem::query()
                    ->where('customer_id', auth()->id())
                    ->pluck('book_id')
                    ->map(fn ($id) => (int) $id)
                    ->all();
            }

            if (Schema::hasTable('book_pdf_requests')) {
                $pdfRequest = BookPdfRequest::query()
                    ->where('book_id', $book->id)
                    ->where('user_id', auth()->id())
                    ->latest('id')
                    ->first();
            }
        }

        return view('customer.book_detail', [
            'book' => $book,
            'canRent' => $canRent,
            'canBuy' => $canBuy,
            'similarBooks' => $similarBooks,
            'store' => null,
            'openingHours' => [],
            'wishlistIds' => $wishlistIds,
            'pdfRequest' => $pdfRequest,
        ]);
    }
}
