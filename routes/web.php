<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminIssueReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CustomerBookController;
use App\Http\Controllers\CustomerCartController;
use App\Http\Controllers\CustomerChatController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerIssueReportController;
use App\Http\Controllers\CustomerWishlistController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreRegistrationController;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function (Request $request) {
    $user = $request->user();

    if ($user?->account_type === 'store_owner') {
        return redirect()->route('store.dashboard');
    }

    if ($user?->account_type === 'customer') {
        return redirect()->route('customer.dashboard');
    }

    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
    ->name('customer.dashboard');

Route::get('/customer/books/{book}', [CustomerBookController::class, 'show'])
    ->name('books.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/customer/chat', [CustomerChatController::class, 'index'])->name('chat.index');
    Route::post('/customer/chat', [CustomerChatController::class, 'store'])->name('chat.start');
    Route::get('/customer/chat/{conversation}', [CustomerChatController::class, 'show'])->name('chat.show');
    Route::post('/customer/chat/{conversation}/message', [CustomerChatController::class, 'sendMessage'])->name('chat.message');
    Route::get('/chat/unread-count', [CustomerChatController::class, 'unreadCount'])->name('chat.unread');

    Route::get('/customer/issue-reports', [CustomerIssueReportController::class, 'index'])->name('issue-reports.index');
    Route::get('/customer/issue-reports/create', [CustomerIssueReportController::class, 'create'])->name('issue-reports.create');
    Route::post('/customer/issue-reports', [CustomerIssueReportController::class, 'store'])->name('issue-reports.store');
    Route::get('/customer/issue-reports/{issueReport}', [CustomerIssueReportController::class, 'show'])->name('issue-reports.show');
});

require __DIR__.'/auth.php';



// routes/web.php

Route::middleware(['auth'])->group(function () {
    // Store Dashboard
    Route::get('/store/dashboard', function (Request $request) {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/store/dashboard'));
        }

        return view('store.dashboard');
    })->name('store.dashboard');

    Route::view('/store/orders', 'store.orders')->name('store.orders');
    Route::view('/store/wishlist', 'store.wishlist')->name('store.wishlist');
    Route::get('/store/books/add', [BookController::class, 'create'])->name('add.book.registration');
    Route::post('/store/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/store/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/store/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/store/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/store/inventory', [BookController::class, 'index'])->name('view.inventory');
    Route::view('/store/books/manage', 'store.books.manage')->name('manage.books');

    Route::get('/store/registration', [StoreRegistrationController::class, 'create'])
        ->name('store.registration.create');
    Route::post('/store/registration', [StoreRegistrationController::class, 'store'])
        ->name('store.registration.store');
    Route::get('/store/registration/update', [StoreRegistrationController::class, 'edit'])
        ->name('store.registration.edit');
    Route::post('/store/registration/update', [StoreRegistrationController::class, 'update'])
        ->name('store.registration.update');
    Route::delete('/store/registration/{store}', [StoreRegistrationController::class, 'destroy'])
        ->name('store.registration.destroy');
    Route::get('/store/registration/view', [StoreRegistrationController::class, 'show'])
        ->name('store.registration.view');
});

Route::middleware(['auth', 'account_type:sub_admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/issue-reports', [AdminIssueReportController::class, 'index'])->name('issue-reports.index');
        Route::get('/issue-reports/{issueReport}', [AdminIssueReportController::class, 'show'])->name('issue-reports.show');
        Route::put('/issue-reports/{issueReport}', [AdminIssueReportController::class, 'update'])->name('issue-reports.update');

        Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{conversation}', [AdminChatController::class, 'show'])->name('chats.show');
        Route::post('/chats/{conversation}/message', [AdminChatController::class, 'sendMessage'])->name('chats.message');

        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminUserController::class, 'create'])
            ->middleware('account_type:super_admin')
            ->name('admins.create');
        Route::post('/admins', [AdminUserController::class, 'store'])
            ->middleware('account_type:super_admin')
            ->name('admins.store');
    });
    Route::get('/customer/wishlist', [CustomerWishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/customer/wishlist/add/{book}', [CustomerWishlistController::class, 'add'])->name('wishlist.add');
    Route::get('/customer/wishlist/remove/{book}', [CustomerWishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/customer/wishlist/clear', [CustomerWishlistController::class, 'clear'])->name('wishlist.clear');

    Route::get('/customer/cart', [CustomerCartController::class, 'index'])->name('cart.index');
    Route::get('/customer/cart/add/rent/{book}', [CustomerCartController::class, 'addRent'])->name('cart.add.rent');
    Route::get('/customer/cart/add/buy/{book}', [CustomerCartController::class, 'addBuy'])->name('cart.add.buy');
    Route::get('/customer/cart/remove/{book}/{type}', [CustomerCartController::class, 'remove'])->name('cart.remove');
    Route::get('/get-cart-count', [CustomerCartController::class, 'count'])->name('cart.count');

    Route::get('/customer/checkout', [CustomerCartController::class, 'checkout'])->name('orders.checkout');
    Route::get('/customer/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/customer/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/customer/orders/{order}/finish', [CustomerOrderController::class, 'markFinished'])->name('orders.finish');
