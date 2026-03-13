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
use App\Http\Controllers\StoreChatController; // ADD THIS IMPORT
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard Redirect based on user type
Route::get('/dashboard', function (Request $request) {
    $user = $request->user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    if ($user->account_type === 'store_owner') {
        return redirect()->route('store.dashboard');
    }
    
    if ($user->account_type === 'customer') {
        return redirect()->route('customer.dashboard');
    }
    
    if (in_array($user->account_type, ['sub_admin', 'super_admin'], true)) {
        return redirect()->route('admin.dashboard');
    }
    
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Customer Dashboard
Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('customer.dashboard');

// Customer Book Routes
Route::get('/customer/books/{book}', [CustomerBookController::class, 'show'])
    ->name('books.show');

/*
|--------------------------------------------------------------------------
| Authenticated Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Issue Reports
    Route::get('/customer/issue-reports', [CustomerIssueReportController::class, 'index'])->name('issue-reports.index');
    Route::get('/customer/issue-reports/create', [CustomerIssueReportController::class, 'create'])->name('issue-reports.create');
    Route::post('/customer/issue-reports', [CustomerIssueReportController::class, 'store'])->name('issue-reports.store');
    Route::get('/customer/issue-reports/{issueReport}', [CustomerIssueReportController::class, 'show'])->name('issue-reports.show');

    /*
    |--------------------------------------------------------------------------
    | Customer Chat Routes (FIXED)
    |--------------------------------------------------------------------------
    */
    // Main chat listing
    Route::get('/customer/chat', [CustomerChatController::class, 'index'])->name('chat.index');
    
    // Start new conversation
    Route::post('/customer/chat', [CustomerChatController::class, 'startConversation'])->name('chat.start');
    
    // View conversation
    Route::get('/customer/chat/{conversation}', [CustomerChatController::class, 'show'])->name('chat.show');
    
    // Send message (with attachment support)
    Route::post('/customer/chat/{conversation}/send', [CustomerChatController::class, 'sendMessage'])->name('chat.send');
    
    // Mark messages as read
    Route::post('/customer/chat/{conversation}/mark-read', [CustomerChatController::class, 'markAsRead'])->name('chat.mark-read');
    
    // Poll for new messages
    Route::get('/customer/chat/{conversation}/poll', [CustomerChatController::class, 'pollMessages'])->name('chat.poll');
    
    // Get stores list for new chat
    Route::get('/customer/chat/stores/list', [CustomerChatController::class, 'getStores'])->name('chat.stores');
    
    // Get unread count
    Route::get('/chat/unread-count', [CustomerChatController::class, 'getUnreadCount'])->name('chat.unread');
});

// Include Auth Routes
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Store Owner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Store Dashboard
    Route::get('/store/dashboard', function (Request $request) {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/store/dashboard'));
        }
        return view('store.dashboard');
    })->name('store.dashboard');

    // Store Views
    Route::view('/store/orders', 'store.orders')->name('store.orders');
    Route::view('/store/wishlist', 'store.wishlist')->name('store.wishlist');

    // Book Management
    Route::get('/store/books/add', [BookController::class, 'create'])->name('add.book.registration');
    Route::post('/store/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/store/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/store/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/store/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/store/inventory', [BookController::class, 'index'])->name('view.inventory');
    Route::view('/store/books/manage', 'store.books.manage')->name('manage.books');

    // Store Registration
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

    /*
    |--------------------------------------------------------------------------
    | Store Chat Routes
    |--------------------------------------------------------------------------
    */
    // Main chat listing
    Route::get('/store/chats', [StoreChatController::class, 'index'])->name('store.chat.index');
    
    // View conversation
    Route::get('/store/chat/{conversation}', [StoreChatController::class, 'show'])->name('store.chat.show');
    
    // Send message (with attachment support)
    Route::post('/store/chat/{conversation}/send', [StoreChatController::class, 'sendMessage'])->name('store.chat.send');
    
    // Mark messages as read
    Route::post('/store/chat/{conversation}/mark-read', [StoreChatController::class, 'markAsRead'])->name('store.chat.mark-read');
    
    // Poll for new messages
    Route::get('/store/chat/{conversation}/poll', [StoreChatController::class, 'pollMessages'])->name('store.chat.poll');
    
    // Start new conversation
    Route::post('/store/chat/start', [StoreChatController::class, 'startConversation'])->name('store.chat.start');
    
    // Delete conversation
    Route::delete('/store/chat/{conversation}', [StoreChatController::class, 'destroy'])->name('store.chat.destroy');
    
    // Get unread count
    Route::get('/store/chat/unread-count', [StoreChatController::class, 'getUnreadCount'])->name('store.chat.unread');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Sub Admin & Super Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'account_type:sub_admin,super_admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Issue Reports Management
        Route::get('/issue-reports', [AdminIssueReportController::class, 'index'])->name('issue-reports.index');
        Route::get('/issue-reports/{issueReport}', [AdminIssueReportController::class, 'show'])->name('issue-reports.show');
        Route::put('/issue-reports/{issueReport}', [AdminIssueReportController::class, 'update'])->name('issue-reports.update');
        
        // Support Chats Management
        Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{conversation}', [AdminChatController::class, 'show'])->name('chats.show');
        Route::post('/chats/{conversation}/message', [AdminChatController::class, 'sendMessage'])->name('chats.message');
        
        // Admin Users Management (Super Admin only for create)
        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminUserController::class, 'create'])
            ->middleware('account_type:super_admin')
            ->name('admins.create');
        Route::post('/admins', [AdminUserController::class, 'store'])
            ->middleware('account_type:super_admin')
            ->name('admins.store');
    });

/*
|--------------------------------------------------------------------------
| Customer Routes (Cart, Wishlist, Orders)
|--------------------------------------------------------------------------
*/

// Wishlist Routes
Route::get('/customer/wishlist', [CustomerWishlistController::class, 'index'])
    ->middleware('auth')
    ->name('wishlist.index');
Route::get('/customer/wishlist/add/{book}', [CustomerWishlistController::class, 'add'])
    ->middleware('auth')
    ->name('wishlist.add');
Route::get('/customer/wishlist/remove/{book}', [CustomerWishlistController::class, 'remove'])
    ->middleware('auth')
    ->name('wishlist.remove');
Route::get('/customer/wishlist/clear', [CustomerWishlistController::class, 'clear'])
    ->middleware('auth')
    ->name('wishlist.clear');

// Cart Routes
Route::get('/customer/cart', [CustomerCartController::class, 'index'])
    ->middleware('auth')
    ->name('cart.index');
Route::get('/customer/cart/add/rent/{book}', [CustomerCartController::class, 'addRent'])
    ->middleware('auth')
    ->name('cart.add.rent');
Route::get('/customer/cart/add/buy/{book}', [CustomerCartController::class, 'addBuy'])
    ->middleware('auth')
    ->name('cart.add.buy');
Route::get('/customer/cart/remove/{book}/{type}', [CustomerCartController::class, 'remove'])
    ->middleware('auth')
    ->name('cart.remove');
Route::get('/get-cart-count', [CustomerCartController::class, 'count'])
    ->middleware('auth')
    ->name('cart.count');

// Checkout & Orders
Route::get('/customer/checkout', [CustomerCartController::class, 'checkout'])
    ->middleware('auth')
    ->name('orders.checkout');
Route::get('/customer/orders', [CustomerOrderController::class, 'index'])
    ->middleware('auth')
    ->name('orders.index');
Route::get('/customer/orders/{order}', [CustomerOrderController::class, 'show'])
    ->middleware('auth')
    ->name('orders.show');
Route::post('/customer/orders/{order}/finish', [CustomerOrderController::class, 'markFinished'])
    ->middleware('auth')
    ->name('orders.finish');