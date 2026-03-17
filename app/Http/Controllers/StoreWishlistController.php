<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StoreWishlistController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->user()->id;

        if (!Schema::hasTable('wishlist_items')) {
            return view('store.wishlist', [
                'wishlistItems' => collect(),
                'wishlistCount' => 0,
                'uniqueBooks' => 0,
                'uniqueCustomers' => 0,
            ])->with('error', 'Wishlists are not available yet. Please run migrations.');
        }

        $wishlistItems = WishlistItem::query()
            ->with(['book', 'customer'])
            ->where('store_id', $storeId)
            ->latest('id')
            ->get();

        $uniqueBooks = $wishlistItems->pluck('book_id')->unique()->count();
        $uniqueCustomers = $wishlistItems->pluck('customer_id')->unique()->count();

        return view('store.wishlist', [
            'wishlistItems' => $wishlistItems,
            'wishlistCount' => $wishlistItems->count(),
            'uniqueBooks' => $uniqueBooks,
            'uniqueCustomers' => $uniqueCustomers,
        ]);
    }
}
