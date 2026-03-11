<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CustomerWishlistController extends Controller
{
    public function index(Request $request)
    {
        $ids = $this->getWishlistIds($request);

        $books = Book::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(function (Book $book) use ($ids) {
                return array_search($book->id, $ids, true);
            })
            ->values();

        return view('customer.wishlist', [
            'wishlistBooks' => $books,
            'wishlistCount' => $books->count(),
        ]);
    }

    public function add(Request $request, $bookId)
    {
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

        return redirect()
            ->back()
            ->with('status', 'Book added to wishlist.');
    }

    public function remove(Request $request, $bookId)
    {
        $ids = $this->getWishlistIds($request);
        $ids = array_values(array_filter($ids, fn ($id) => (int) $id !== (int) $bookId));
        $request->session()->put('wishlist', $ids);

        return redirect()
            ->back()
            ->with('status', 'Book removed from wishlist.');
    }

    public function clear(Request $request)
    {
        $request->session()->forget('wishlist');

        return redirect()
            ->back()
            ->with('status', 'Wishlist cleared.');
    }

    private function getWishlistIds(Request $request): array
    {
        $ids = $request->session()->get('wishlist', []);
        return array_values(array_unique(array_map('intval', $ids)));
    }
}
