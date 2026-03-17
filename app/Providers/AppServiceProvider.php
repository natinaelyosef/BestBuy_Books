<?php

namespace App\Providers;

use App\Models\Book;
use App\Models\BookPdfRequest;
use App\Models\Order;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        View::composer('store.*', function ($view) {
            $user = auth()->user();
            if (!$user || $user->account_type !== 'store_owner') {
                return;
            }

            $storeId = $user->id;

            $pendingOrdersCount = 0;
            if (Schema::hasTable('orders')) {
                $pendingOrdersCount = Order::query()
                    ->where('store_id', $storeId)
                    ->where('status', 'pending')
                    ->count();
            }

            $wishlistCount = 0;
            if (Schema::hasTable('wishlist_items')) {
                $wishlistCount = WishlistItem::query()
                    ->where('store_id', $storeId)
                    ->count();
            }

            $pendingPdfRequestsCount = 0;
            if (Schema::hasTable('book_pdf_requests')) {
                $pendingPdfRequestsCount = BookPdfRequest::query()
                    ->join('books', 'book_pdf_requests.book_id', '=', 'books.id')
                    ->where('books.user_id', $storeId)
                    ->where('book_pdf_requests.status', 'pending')
                    ->count();
            }

            $view->with([
                'pendingOrdersCount' => $pendingOrdersCount,
                'wishlistCount' => $wishlistCount,
                'pendingPdfRequestsCount' => $pendingPdfRequestsCount,
                'totalUnread' => $user->unreadMessagesCount(),
                'storeMetrics' => [
                    'total_books' => Book::query()->where('user_id', $storeId)->count(),
                ],
            ]);
        });
    }
}
