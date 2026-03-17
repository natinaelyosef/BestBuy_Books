@extends('customer.base')

@section('title', $book->title . ' - BookHub')

@section('content')
<div class="book-detail-container">
    <!-- Breadcrumb Navigation -->
    <div class="breadcrumb-nav">
        <a href="{{ route('customer.dashboard') }}">Home</a>
        <span class="separator">›</span>
        <a href="{{ route('customer.dashboard', ['genre' => $book->genre]) }}">{{ $book->genre }}</a>
        <span class="separator">›</span>
        <span class="current">{{ $book->title }}</span>
    </div>

    <!-- Main Book Detail Section -->
    <div class="book-main">
        <!-- Left Column - Book Cover -->
        <div class="book-cover-section">
            <div class="book-cover-wrapper">
                @if($book->cover_image_path)
                    <img src="{{ asset($book->cover_image_path) }}" 
                         alt="{{ $book->title }}" 
                         class="book-cover-image">
                @else
                    <div class="book-cover-placeholder">
                        <i class="bi bi-book"></i>
                        <span>No Cover Available</span>
                    </div>
                @endif
            </div>
            
            <!-- Quick Actions -->
            <div class="book-actions-vertical">
                @php
                    $wishlistIds = $wishlistIds ?? array_map('intval', session('wishlist', []));
                    $isInWishlist = in_array((int) $book->id, $wishlistIds, true);
                @endphp
                
                <form id="wishlist-form-{{ $book->id }}" method="POST" action="{{ $isInWishlist ? route('wishlist.remove', $book->id) : route('wishlist.add', $book->id) }}" style="display: none;">
                    @csrf
                </form>
                
                <button class="action-btn wishlist-btn {{ $isInWishlist ? 'active' : '' }}" 
                        onclick="document.getElementById('wishlist-form-{{ $book->id }}').submit();">
                    <i class="bi {{ $isInWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    <span>{{ $isInWishlist ? 'In Wishlist' : 'Add to Wishlist' }}</span>
                </button>
                
                <button class="action-btn share-btn" onclick="shareBook()">
                    <i class="bi bi-share"></i>
                    <span>Share</span>
                </button>
            </div>
        </div>

        <!-- Right Column - Book Info -->
        <div class="book-info-section">
            <!-- Book Title & Author -->
            <h1 class="book-title">{{ $book->title }}</h1>
            <h2 class="book-author">by {{ $book->author }}</h2>
            
            <!-- Store Owner Info -->
            @if($book->user)
            <div class="store-owner-card">
                <div class="owner-avatar">
                    @if($book->user->avatar)
                        <img src="{{ asset('storage/' . $book->user->avatar) }}" alt="{{ $book->user->name }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ substr($book->user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="owner-info">
                    <span class="owner-label">Sold by</span>
                    <span class="owner-name">{{ $book->user->name }}</span>
                    @if($book->user->isOnline())
                        <span class="online-status online"><i class="bi bi-circle-fill"></i> Online</span>
                    @else
                        <span class="online-status offline"><i class="bi bi-circle-fill"></i> Offline</span>
                    @endif
                </div>
                <a href="{{ route('chat.with.store', $book->user->id) }}?book={{ $book->id }}" 
                   class="message-owner-btn">
                    <i class="bi bi-chat-dots-fill"></i>
                    Message Store Owner
                </a>
            </div>
            @endif

            <!-- Book Meta Information -->
            <div class="book-meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Genre</span>
                    <span class="meta-value">{{ $book->genre }}</span>
                </div>
                @if($book->publication_year)
                <div class="meta-item">
                    <span class="meta-label">Published</span>
                    <span class="meta-value">{{ $book->publication_year }}</span>
                </div>
                @endif
                <div class="meta-item">
                    <span class="meta-label">Total Copies</span>
                    <span class="meta-value">{{ $book->total_copies }}</span>
                </div>
            </div>

            <!-- Availability Status -->
            <div class="availability-section">
                <div class="availability-item {{ $book->available_rent > 0 ? 'available' : 'unavailable' }}">
                    <i class="bi bi-arrow-repeat"></i>
                    <div class="availability-details">
                        <span class="availability-label">Rental</span>
                        <span class="availability-count">{{ $book->available_rent }} copies available</span>
                    </div>
                </div>
                <div class="availability-item {{ $book->available_sale > 0 ? 'available' : 'unavailable' }}">
                    <i class="bi bi-bag-check"></i>
                    <div class="availability-details">
                        <span class="availability-label">Purchase</span>
                        <span class="availability-count">{{ $book->available_sale }} copies available</span>
                    </div>
                </div>
            </div>

            <!-- Pricing and Actions -->
            <div class="pricing-section">
                <div class="price-card rental-card">
                    <div class="price-header">
                        <i class="bi bi-calendar-week"></i>
                        <span>Rental</span>
                    </div>
                    <div class="price-amount">${{ number_format($book->rental_price, 2) }}</div>
                    <div class="price-period">per month</div>
                    @if($book->available_rent > 0)
                        <a href="{{ route('cart.add.rent', $book->id) }}" class="action-button rental-button">
                            <i class="bi bi-cart-plus"></i>
                            Add to Cart
                        </a>
                    @else
                        <button class="action-button disabled" disabled>Out of Stock</button>
                    @endif
                </div>

                <div class="price-card purchase-card">
                    <div class="price-header">
                        <i class="bi bi-tag-fill"></i>
                        <span>Purchase</span>
                    </div>
                    <div class="price-amount">${{ number_format($book->sale_price, 2) }}</div>
                    <div class="price-period">one-time payment</div>
                    @if($book->available_sale > 0)
                        <a href="{{ route('cart.add.buy', $book->id) }}" class="action-button purchase-button">
                            <i class="bi bi-cart-plus"></i>
                            Add to Cart
                        </a>
                    @else
                        <button class="action-button disabled" disabled>Out of Stock</button>
                    @endif
                </div>
            </div>

            @if($book->pdf_path)
            <div class="pdf-section">
                <div class="pdf-card">
                    <div class="pdf-info">
                        <div class="pdf-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                        <div>
                            <h3>Digital PDF</h3>
                            <p>Request a downloadable copy from the store owner.</p>
                        </div>
                    </div>
                    <div class="pdf-actions">
                        @if($pdfRequest && $pdfRequest->status === 'approved')
                            <a href="{{ route('customer.pdfs.download', $pdfRequest->id) }}" class="pdf-btn pdf-btn-primary">
                                <i class="bi bi-download"></i> Download PDF
                            </a>
                        @elseif($pdfRequest && $pdfRequest->status === 'pending')
                            <button class="pdf-btn pdf-btn-muted" disabled>Request Pending</button>
                        @elseif($pdfRequest && $pdfRequest->status === 'rejected')
                            <form method="POST" action="{{ route('customer.pdf.request', $book->id) }}">
                                @csrf
                                <button type="submit" class="pdf-btn pdf-btn-secondary">
                                    <i class="bi bi-arrow-repeat"></i> Request Again
                                </button>
                            </form>
                            <span class="pdf-note">Last request was declined.</span>
                        @else
                            <form method="POST" action="{{ route('customer.pdf.request', $book->id) }}">
                                @csrf
                                <button type="submit" class="pdf-btn pdf-btn-primary">
                                    <i class="bi bi-send"></i> Request PDF
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Message Preview -->
            <div class="quick-message-section">
                <h3>Have a question about this book?</h3>
                <form action="{{ route('chat.with.store', $book->user->id) }}" method="GET" class="quick-message-form">
                    <input type="hidden" name="book" value="{{ $book->id }}">
                    <input type="text" name="message_preview" 
                           placeholder="e.g., Is this book available for rent?" 
                           class="message-preview-input">
                    <button type="submit" class="send-message-btn">
                        <i class="bi bi-send-fill"></i>
                        Send
                    </button>
                </form>
                <p class="form-note">
                    <i class="bi bi-info-circle"></i>
                    This will start a conversation with the store owner
                </p>
            </div>
        </div>
    </div>

    <!-- Similar Books Section -->
    @if(isset($similarBooks) && $similarBooks->count() > 0)
    <div class="similar-books-section">
        <h2 class="section-title">Similar Books You Might Like</h2>
        <div class="similar-books-grid">
            @foreach($similarBooks as $similar)
            <div class="similar-book-card">
                <a href="{{ route('books.show', $similar->id) }}" class="similar-book-link">
                    <div class="similar-book-cover">
                        @if($similar->cover_image_path)
                            <img src="{{ asset($similar->cover_image_path) }}" alt="{{ $similar->title }}">
                        @else
                            <div class="no-cover"><i class="bi bi-book"></i></div>
                        @endif
                    </div>
                    <div class="similar-book-info">
                        <h3 class="similar-book-title">{{ $similar->title }}</h3>
                        <p class="similar-book-author">{{ $similar->author }}</p>
                        <div class="similar-book-price">
                            <span class="rent-price">${{ number_format($similar->rental_price, 2) }}/mo</span>
                            <span class="buy-price">${{ number_format($similar->sale_price, 2) }}</span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<!-- Success/Error Messages -->
@if(session('status'))
<div class="alert alert-success" id="status-alert">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('status') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-error" id="error-alert">
    <i class="bi bi-exclamation-circle-fill"></i>
    {{ session('error') }}
</div>
@endif
@endsection

@section('extra_css')
<style>
/* Main Container */
.book-detail-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1.5rem;
}

/* Breadcrumb Navigation */
.breadcrumb-nav {
    margin-bottom: 2rem;
    padding: 0.75rem 1rem;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    font-size: 0.9rem;
}

.breadcrumb-nav a {
    color: var(--primary);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-nav a:hover {
    color: var(--primary-dark);
    text-decoration: underline;
}

.breadcrumb-nav .separator {
    margin: 0 0.5rem;
    color: var(--text-muted);
}

.breadcrumb-nav .current {
    color: var(--text-secondary);
    font-weight: 500;
}

/* Main Layout */
.book-main {
    display: grid;
    grid-template-columns: 350px 1fr;
    gap: 2.5rem;
    margin-bottom: 3rem;
}

/* Book Cover Section */
.book-cover-section {
    position: relative;
}

.book-cover-wrapper {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
    aspect-ratio: 2/3;
    margin-bottom: 1rem;
}

.book-cover-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.book-cover-image:hover {
    transform: scale(1.02);
}

.book-cover-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--bg-raised), var(--bg-card));
    color: var(--text-muted);
}

.book-cover-placeholder i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

/* Vertical Actions */
.book-actions-vertical {
    display: flex;
    gap: 0.75rem;
    margin-top: 1rem;
}

.action-btn {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border-radius: var(--radius);
    font-size: 0.9rem;
    font-weight: 600;
    border: 1px solid var(--border);
    background: var(--bg-card);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
}

.action-btn:hover {
    background: var(--bg-raised);
    border-color: var(--primary);
    color: var(--primary);
}

.action-btn.wishlist-btn.active {
    background: var(--danger-soft);
    border-color: var(--danger);
    color: var(--danger);
}

/* Book Info Section */
.book-info-section {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    padding: 2rem;
    box-shadow: var(--shadow-lg);
}

.book-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.book-author {
    font-size: 1.2rem;
    font-weight: 500;
    color: var(--text-secondary);
    margin: 0 0 1.5rem 0;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border);
}

/* Store Owner Card */
.store-owner-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    margin-bottom: 2rem;
}

.owner-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
}

.owner-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 700;
}

.owner-info {
    flex: 1;
}

.owner-label {
    display: block;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 0.2rem;
}

.owner-name {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.3rem;
}

.online-status {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    font-size: 0.8rem;
}

.online-status i {
    font-size: 0.6rem;
}

.online-status.online {
    color: var(--success);
}

.online-status.offline {
    color: var(--text-muted);
}

.message-owner-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: var(--primary);
    color: white;
    text-decoration: none;
    border-radius: var(--radius);
    font-weight: 600;
    transition: all 0.2s;
    white-space: nowrap;
}

.message-owner-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

/* Meta Grid */
.book-meta-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.meta-item {
    padding: 0.75rem;
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    text-align: center;
}

.meta-label {
    display: block;
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 0.3rem;
}

.meta-value {
    display: block;
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
}

/* Availability Section */
.availability-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 2rem;
}

.availability-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: var(--radius);
    border: 1px solid transparent;
}

.availability-item.available {
    background: var(--success-soft);
    border-color: var(--success);
}

.availability-item.unavailable {
    background: var(--bg-raised);
    border-color: var(--border);
    opacity: 0.7;
}

.availability-item i {
    font-size: 1.5rem;
}

.available i {
    color: var(--success);
}

.unavailable i {
    color: var(--text-muted);
}

.availability-details {
    flex: 1;
}

.availability-label {
    display: block;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.2rem;
}

.availability-count {
    display: block;
    font-size: 0.8rem;
    color: var(--text-secondary);
}

/* Pricing Section */
.pricing-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.price-card {
    padding: 1.5rem;
    border-radius: var(--radius-lg);
    border: 2px solid var(--border);
    text-align: center;
    transition: all 0.2s;
}

.price-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.rental-card {
    background: linear-gradient(145deg, var(--primary-soft), var(--bg-card));
    border-color: var(--primary);
}

.purchase-card {
    background: linear-gradient(145deg, var(--success-soft), var(--bg-card));
    border-color: var(--success);
}

.price-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.rental-card .price-header {
    color: var(--primary);
}

.purchase-card .price-header {
    color: var(--success);
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.3rem;
}

.rental-card .price-amount {
    color: var(--primary);
}

.purchase-card .price-amount {
    color: var(--success);
}

.price-period {
    font-size: 0.9rem;
    color: var(--text-muted);
    margin-bottom: 1.5rem;
}

.action-button {
    display: inline-block;
    width: 100%;
    padding: 1rem;
    border-radius: var(--radius);
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}

.rental-button {
    background: var(--primary);
    color: white;
}

.rental-button:hover {
    background: var(--primary-dark);
    transform: scale(1.02);
}

.purchase-button {
    background: var(--success);
    color: white;
}

.purchase-button:hover {
    background: #00b374;
    transform: scale(1.02);
}

.action-button.disabled {
    background: var(--bg-raised);
    color: var(--text-muted);
    cursor: not-allowed;
    pointer-events: none;
}

/* Quick Message Section */
.quick-message-section {
    background: var(--bg-raised);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
}

.quick-message-section h3 {
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
    color: var(--text-primary);
}

.quick-message-form {
    display: flex;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
}

.message-preview-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--bg-card);
    color: var(--text-primary);
    font-size: 0.95rem;
}

.message-preview-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-soft);
}

.send-message-btn {
    padding: 0.75rem 1.5rem;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius);
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}

.send-message-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.form-note {
    margin: 0;
    font-size: 0.8rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 0.3rem;
}

/* Similar Books Section */
.similar-books-section {
    margin-top: 3rem;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 1.5rem;
}

.similar-books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
}

.similar-book-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: all 0.2s;
}

.similar-book-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.similar-book-link {
    text-decoration: none;
    color: inherit;
}

.similar-book-cover {
    aspect-ratio: 2/3;
    overflow: hidden;
}

.similar-book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.similar-book-card:hover .similar-book-cover img {
    transform: scale(1.05);
}

.no-cover {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--bg-raised), var(--bg-card));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.no-cover i {
    font-size: 2rem;
}

.similar-book-info {
    padding: 1rem;
}

.similar-book-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.3rem 0;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.similar-book-author {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin: 0 0 0.5rem 0;
}

.similar-book-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
}

.rent-price {
    color: var(--primary);
    font-weight: 600;
}

.buy-price {
    color: var(--success);
    font-weight: 600;
}

/* Alerts */
.alert {
    position: fixed;
    top: 1rem;
    right: 1rem;
    padding: 1rem 1.5rem;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    box-shadow: var(--shadow-lg);
    animation: slideIn 0.3s ease;
    z-index: 9999;
}

.alert-success {
    background: var(--success-soft);
    border: 1px solid var(--success);
    color: var(--success);
}

.alert-error {
    background: var(--danger-soft);
    border: 1px solid var(--danger);
    color: var(--danger);
}

/* PDF Request */
.pdf-section {
    margin-top: 2rem;
}

.pdf-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1.1rem 1.3rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--border);
    background: var(--bg-raised);
}

.pdf-info {
    display: flex;
    align-items: center;
    gap: 0.85rem;
}

.pdf-icon {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(245, 176, 66, 0.15);
    color: var(--accent);
    font-size: 1.3rem;
}

.pdf-info h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-primary);
}

.pdf-info p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.88rem;
}

.pdf-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.pdf-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    border-radius: 999px;
    padding: 0.6rem 1.15rem;
    font-size: 0.85rem;
    font-weight: 700;
    border: 1px solid transparent;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
}

.pdf-btn-primary {
    background: var(--primary);
    color: #fff;
}

.pdf-btn-primary:hover {
    background: var(--primary-dark);
    color: #fff;
}

.pdf-btn-secondary {
    background: var(--accent-soft);
    color: var(--accent);
    border-color: rgba(245, 176, 66, 0.3);
}

.pdf-btn-secondary:hover {
    background: var(--accent);
    color: #fff;
}

.pdf-btn-muted {
    background: var(--bg-card);
    color: var(--text-muted);
    border-color: var(--border);
    cursor: not-allowed;
}

.pdf-note {
    font-size: 0.8rem;
    color: var(--text-muted);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 968px) {
    .book-main {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .book-cover-section {
        max-width: 400px;
        margin: 0 auto;
    }
}

@media (max-width: 768px) {
    .book-title {
        font-size: 2rem;
    }
    
    .pricing-section,
    .availability-section,
    .quick-message-form {
        grid-template-columns: 1fr;
    }
    
    .store-owner-card {
        flex-wrap: wrap;
    }
    
    .message-owner-btn {
        width: 100%;
        justify-content: center;
    }
    
    .quick-message-form {
        flex-direction: column;
    }
    
    .send-message-btn {
        width: 100%;
        justify-content: center;
    }

    .pdf-card {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .similar-books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    }
}

@media (max-width: 480px) {
    .book-detail-container {
        padding: 0 1rem;
    }
    
    .book-info-section {
        padding: 1.5rem;
    }
    
    .book-meta-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('extra_js')
<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.animation = 'slideIn 0.3s ease reverse';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Share functionality
function shareBook() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $book->title }}',
            text: 'Check out this book on BookHub!',
            url: window.location.href
        }).catch(console.error);
    } else {
        // Fallback - copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        }).catch(() => {
            alert('Press Ctrl+C to copy the link');
        });
    }
}
</script>
@endsection
