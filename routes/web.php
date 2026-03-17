<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\AdminChatController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminIssueReportController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminUserManagementController;
use App\Http\Controllers\CustomerBookController;
use App\Http\Controllers\CustomerCartController;
use App\Http\Controllers\CustomerChatController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\CustomerIssueReportController;
use App\Http\Controllers\CustomerWishlistController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CustomerPdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoreRegistrationController;
use App\Http\Controllers\StoreIssueReportController;
use App\Http\Controllers\StoreChatController;
use App\Http\Controllers\StoreOrderController;
use App\Http\Controllers\StoreWishlistController;
use App\Http\Controllers\StorePdfRequestController;
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

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'account_type:customer'])->prefix('customer')->name('customer.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    // Books
    Route::get('/books', [CustomerDashboardController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [CustomerBookController::class, 'show'])->name('books.show');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Issue Reports
    Route::get('/issue-reports', [CustomerIssueReportController::class, 'index'])->name('issue-reports.index');
    Route::get('/issue-reports/create', [CustomerIssueReportController::class, 'create'])->name('issue-reports.create');
    Route::post('/issue-reports', [CustomerIssueReportController::class, 'store'])->name('issue-reports.store');
    Route::get('/issue-reports/{issueReport}', [CustomerIssueReportController::class, 'show'])->name('issue-reports.show');

    /*
    |--------------------------------------------------------------------------
    | Customer Chat Routes
    |--------------------------------------------------------------------------
    */
    // Main chat listing
    Route::get('/chat', [CustomerChatController::class, 'index'])->name('chat.index');
    
    // Start new conversation (POST form)
    Route::post('/chat', [CustomerChatController::class, 'startConversation'])->name('chat.start');
    
    // Start conversation with specific store owner (GET from book page)
    Route::get('/chat/with-store/{storeId}', [CustomerChatController::class, 'startWithStore'])->name('chat.with.store');
    
    // View specific conversation - THIS MUST COME AFTER specific routes
    Route::get('/chat/{conversation}', [CustomerChatController::class, 'show'])->name('chat.show');
    
    // Send message (AJAX with attachment support)
    Route::post('/chat/{conversation}/send', [CustomerChatController::class, 'sendMessage'])->name('chat.send');
    
    // Mark messages as read
    Route::post('/chat/{conversation}/mark-read', [CustomerChatController::class, 'markAsRead'])->name('chat.mark-read');
    
    // Poll for new messages
    Route::get('/chat/{conversation}/poll', [CustomerChatController::class, 'pollMessages'])->name('chat.poll');
    
    // Get stores list for new chat (AJAX)
    Route::get('/chat/stores/list', [CustomerChatController::class, 'getStores'])->name('chat.stores');
    
    // Get unread count (AJAX)
    Route::get('/chat/unread-count', [CustomerChatController::class, 'getUnreadCount'])->name('chat.unread');

    // Customer PDF Requests
    Route::get('/pdfs', [CustomerPdfController::class, 'index'])->name('pdfs.index');
    Route::post('/books/{book}/pdf-request', [CustomerPdfController::class, 'storeRequest'])->name('pdf.request');
    Route::get('/pdfs/{pdfRequest}/download', [CustomerPdfController::class, 'download'])->name('pdfs.download');
});

// Include Auth Routes
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Customer Cart, Wishlist & Order Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'account_type:customer'])->prefix('customer')->name('customer.')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Wishlist Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/wishlist', [CustomerWishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{book}', [CustomerWishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{book}', [CustomerWishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist/clear', [CustomerWishlistController::class, 'clear'])->name('wishlist.clear');

    /*
    |--------------------------------------------------------------------------
    | Cart Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/cart', [CustomerCartController::class, 'index'])->name('cart.index');
    Route::get('/cart/add/rent/{book}', [CustomerCartController::class, 'addRent'])->name('cart.add.rent');
    Route::get('/cart/add/buy/{book}', [CustomerCartController::class, 'addBuy'])->name('cart.add.buy');
    Route::get('/cart/remove/{book}/{type}', [CustomerCartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/count', [CustomerCartController::class, 'count'])->name('cart.count');
    
    /*
    |--------------------------------------------------------------------------
    | Order Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/checkout', [CustomerCartController::class, 'checkout'])->name('orders.checkout');
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/finish', [CustomerOrderController::class, 'markFinished'])->name('orders.finish');
});

/*
|--------------------------------------------------------------------------
| Store Owner Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'account_type:store_owner'])->prefix('store')->name('store.')->group(function () {
    // Store Dashboard
    Route::get('/dashboard', function (Request $request) {
        if ($request->header('X-Inertia')) {
            return Inertia::location(url('/store/dashboard'));
        }
        return view('store.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Book Management
    |--------------------------------------------------------------------------
    */
    Route::get('/inventory', [BookController::class, 'index'])->name('inventory');
    Route::get('/books/manage', [BookController::class, 'manage'])->name('books.manage');
    Route::get('/books/add', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::get('/books/{book}/pdf', [BookController::class, 'downloadPdf'])->name('books.pdf');

    /*
    |--------------------------------------------------------------------------
    | Order Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/orders', [StoreOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [StoreOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [StoreOrderController::class, 'updateStatus'])->name('orders.update-status');

    /*
    |--------------------------------------------------------------------------
    | Wishlist Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/wishlist', [StoreWishlistController::class, 'index'])->name('wishlist.index');

    /*
    |--------------------------------------------------------------------------
    | PDF Requests Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/pdf-requests', [StorePdfRequestController::class, 'index'])->name('pdf-requests.index');
    Route::put('/pdf-requests/{pdfRequest}/approve', [StorePdfRequestController::class, 'approve'])->name('pdf-requests.approve');
    Route::put('/pdf-requests/{pdfRequest}/reject', [StorePdfRequestController::class, 'reject'])->name('pdf-requests.reject');

    /*
    |--------------------------------------------------------------------------
    | Store Registration Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/registration', [StoreRegistrationController::class, 'create'])->name('registration.create');
    Route::post('/registration', [StoreRegistrationController::class, 'store'])->name('registration.store');
    Route::get('/registration/update', [StoreRegistrationController::class, 'edit'])->name('registration.edit');
    Route::post('/registration/update', [StoreRegistrationController::class, 'update'])->name('registration.update');
    Route::delete('/registration/{store}', [StoreRegistrationController::class, 'destroy'])->name('registration.destroy');
    Route::get('/registration/view', [StoreRegistrationController::class, 'show'])->name('registration.view');

    /*
    |--------------------------------------------------------------------------
    | Chat Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/chats', [StoreChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [StoreChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [StoreChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/{conversation}/mark-read', [StoreChatController::class, 'markAsRead'])->name('chat.mark-read');
    Route::get('/chat/{conversation}/poll', [StoreChatController::class, 'pollMessages'])->name('chat.poll');
    Route::post('/chat/start', [StoreChatController::class, 'startConversation'])->name('chat.start');
    Route::delete('/chat/{conversation}', [StoreChatController::class, 'destroy'])->name('chat.destroy');
    Route::get('/chat/unread-count', [StoreChatController::class, 'getUnreadCount'])->name('chat.unread');

    /*
    |--------------------------------------------------------------------------
    | Issue Reports Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/issue-reports', [StoreIssueReportController::class, 'index'])->name('issue-reports.index');
    Route::get('/issue-reports/create', [StoreIssueReportController::class, 'create'])->name('issue-reports.create');
    Route::post('/issue-reports', [StoreIssueReportController::class, 'store'])->name('issue-reports.store');
    Route::get('/issue-reports/{issueReport}', [StoreIssueReportController::class, 'show'])->name('issue-reports.show');
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
        
        /*
        |--------------------------------------------------------------------------
        | Issue Reports Management
        |--------------------------------------------------------------------------
        */
        Route::get('/issue-reports', [AdminIssueReportController::class, 'index'])->name('issue-reports.index');
        Route::get('/issue-reports/{issueReport}', [AdminIssueReportController::class, 'show'])->name('issue-reports.show');
        Route::put('/issue-reports/{issueReport}', [AdminIssueReportController::class, 'update'])->name('issue-reports.update');
        Route::post('/issue-reports/{issueReport}/ban', [AdminIssueReportController::class, 'banUser'])->name('issue-reports.ban');
        Route::post('/issue-reports/{issueReport}/warn', [AdminIssueReportController::class, 'warnUser'])->name('issue-reports.warn');
        Route::post('/issue-reports/{issueReport}/restrict', [AdminIssueReportController::class, 'restrictUser'])->name('issue-reports.restrict');
        Route::post('/issue-reports/{issueReport}/resolve', [AdminIssueReportController::class, 'resolve'])->name('issue-reports.resolve');
        
        /*
        |--------------------------------------------------------------------------
        | Support Chats Management
        |--------------------------------------------------------------------------
        */
        Route::get('/chats', [AdminChatController::class, 'index'])->name('chats.index');
        Route::get('/chats/{conversation}', [AdminChatController::class, 'show'])->name('chats.show');
        Route::post('/chats/{conversation}/message', [AdminChatController::class, 'sendMessage'])->name('chats.message');
        
        /*
        |--------------------------------------------------------------------------
        | Site Users Management (all customers & store owners)
        |--------------------------------------------------------------------------
        */
        Route::get('/users', [AdminUserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserManagementController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/ban', [AdminUserManagementController::class, 'banUser'])->name('users.ban');
        Route::post('/users/{user}/unban', [AdminUserManagementController::class, 'unbanUser'])->name('users.unban');
        Route::post('/users/{user}/warn', [AdminUserManagementController::class, 'warnUser'])->name('users.warn');
        Route::post('/users/{user}/restrict', [AdminUserManagementController::class, 'restrictUser'])->name('users.restrict');
        Route::post('/users/{user}/unrestrict', [AdminUserManagementController::class, 'unrestrictUser'])->name('users.unrestrict');
        
        /*
        |--------------------------------------------------------------------------
        | Admin Users Management
        |--------------------------------------------------------------------------
        */
        Route::get('/admins', [AdminUserController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminUserController::class, 'create'])
            ->middleware('account_type:super_admin')
            ->name('admins.create');
        Route::post('/admins', [AdminUserController::class, 'store'])
            ->middleware('account_type:super_admin')
            ->name('admins.store');
        Route::get('/admins/{admin}/edit', [AdminUserController::class, 'edit'])
            ->middleware('account_type:super_admin')
            ->name('admins.edit');
        Route::put('/admins/{admin}', [AdminUserController::class, 'update'])
            ->middleware('account_type:super_admin')
            ->name('admins.update');
        Route::delete('/admins/{admin}', [AdminUserController::class, 'destroy'])
            ->middleware('account_type:super_admin')
            ->name('admins.destroy');
        Route::post('/admins/{admin}/toggle-active', [AdminUserController::class, 'toggleActive'])
            ->middleware('account_type:super_admin')
            ->name('admins.toggle-active');
    });
Route::get('/store/book/add', [BookController::class, 'create'])->name('add.book.registration');

// Global profile management route for all users (including store owners)
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
// Added missing route for inventory view (for dashboard link)
Route::get('/store/inventory/view', [BookController::class, 'index'])->name('view.inventory');
