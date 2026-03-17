<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerWishlistController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $ids = $this->getWishlistIds($request);
        $hasWishlistTable = $this->hasWishlistTable();

        if ($hasWishlistTable && !empty($ids)) {
            $booksForSync = Book::query()
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');

            foreach ($ids as $bookId) {
                $book = $booksForSync->get($bookId);
                if ($book) {
                    WishlistItem::firstOrCreate(
                        ['customer_id' => $user->id, 'book_id' => $book->id],
                        ['store_id' => $book->user_id]
                    );
                }
            }
        }

        if ($hasWishlistTable) {
            $wishlistItems = WishlistItem::query()
                ->with('book')
                ->where('customer_id', $user->id)
                ->latest('id')
                ->get();

            $books = $wishlistItems
                ->map(fn (WishlistItem $item) => $item->book)
                ->filter()
                ->values();

            $wishlistCount = $wishlistItems->count();
        } else {
            $books = empty($ids)
                ? collect()
                : Book::query()->whereIn('id', $ids)->get();
            $wishlistCount = $books->count();
        }

        return view('customer.wishlist', [
            'wishlistBooks' => $books,
            'wishlistCount' => $wishlistCount,
        ]);
    }

    public function add(Request $request, $bookId)
    {
        $user = $request->user();
        $book = Book::find($bookId);
        if (!$book) {
            return redirect()
                ->back()
                ->with('error', 'Book not found.');
        }

        $ids = $this->getWishlistIds($request);
        if (!in_array($book->id, $ids, true)) {
            $ids[] = $book->id;
            $request->session()->put('wishlist', $ids);
        }

        if ($this->hasWishlistTable()) {
            WishlistItem::firstOrCreate(
                ['customer_id' => $user->id, 'book_id' => $book->id],
                ['store_id' => $book->user_id]
            );
        }

        return redirect()
            ->back()
            ->with('status', 'Book added to wishlist.');
    }

    public function remove(Request $request, $bookId)
    {
        $user = $request->user();
        $ids = $this->getWishlistIds($request);
        $ids = array_values(array_filter($ids, fn ($id) => (int) $id !== (int) $bookId));
        $request->session()->put('wishlist', $ids);

        if ($this->hasWishlistTable()) {
            WishlistItem::query()
                ->where('customer_id', $user->id)
                ->where('book_id', $bookId)
                ->delete();
        }

        return redirect()
            ->back()
            ->with('status', 'Book removed from wishlist.');
    }

    public function clear(Request $request)
    {
        $user = $request->user();
        $request->session()->forget('wishlist');

        if ($this->hasWishlistTable()) {
            WishlistItem::query()
                ->where('customer_id', $user->id)
                ->delete();
        }

        return redirect()
            ->back()
            ->with('status', 'Wishlist cleared.');
    }

    private function getWishlistIds(Request $request): array
    {
        $ids = $request->session()->get('wishlist', []);
        return array_values(array_unique(array_map('intval', $ids)));
    }

    private function hasWishlistTable(): bool
    {
        return Schema::hasTable('wishlist_items');
    }
}
