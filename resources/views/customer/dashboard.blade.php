@extends('customer.base')

@section('title', 'Browse Books · BestBuy_Books')

@section('extra_css')
<style>
/* =============================================================
   DASHBOARD — all tokens inherited from base.blade.php
   ============================================================= */

/* ——— Results / info bar ——— */
.results-bar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.75rem;
    padding: 1rem 1.25rem; margin-bottom: 2rem;
    background: var(--bg-card); border: 1px solid var(--border);
    border-radius: var(--radius-lg); box-shadow: var(--shadow-sm);
}
.results-text { font-size: 0.87rem; color: var(--text-secondary); font-weight: 500; }
.results-text strong { color: var(--text-primary); font-weight: 800; }
.results-text .hl { color: var(--primary); font-style: italic; }

/* Active filter pills */
.active-pills { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.65rem; }
.active-pill {
    display: inline-flex; align-items: center; gap: 0.4rem;
    padding: 0.22rem 0.7rem; border-radius: 999px;
    font-size: 0.7rem; font-weight: 700;
}
.pill-blue   { background: var(--primary-soft); color: var(--primary);  border: 1px solid var(--border); }
.pill-green  { background: var(--success-soft);  color: var(--success);  border: 1px solid rgba(0,201,139,0.2); }
.pill-close  { opacity: 0.6; cursor: pointer; transition: opacity var(--t-fast); text-decoration: none; color: inherit; }
.pill-close:hover { opacity: 1; color: inherit; }

/* ——— Empty state ——— */
.empty-state {
    grid-column: 1 / -1;
    text-align: center; padding: 5rem 2rem;
}
.empty-icon {
    width: 80px; height: 80px; border-radius: var(--radius-lg);
    background: var(--primary-soft); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--primary); margin: 0 auto 1.5rem;
}
.empty-state h3 {
    font-size: 1.25rem; font-weight: 800; color: var(--text-primary);
    letter-spacing: -0.02em; margin-bottom: 0.5rem;
}
.empty-state p { font-size: 0.87rem; color: var(--text-muted); margin-bottom: 1.5rem; }

/* ——— Genre badge on card ——— */
.book-genre-tag {
    display: inline-flex; align-items: center;
    padding: 0.18rem 0.6rem; border-radius: 999px;
    font-size: 0.65rem; font-weight: 700; letter-spacing: 0.3px;
    background: var(--primary-soft); color: var(--primary);
    border: 1px solid var(--border); margin-bottom: 0.6rem;
}

/* ——— Availability rows ——— */
.avail-row {
    display: flex; align-items: center; gap: 0.45rem;
    font-size: 0.74rem; font-weight: 600; margin-bottom: 0.28rem;
}
.avail-dot { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.avail-yes .avail-dot { background: var(--success); }
.avail-no  .avail-dot { background: var(--danger); }
.avail-yes { color: var(--success); }
.avail-no  { color: var(--danger); }

/* ——— 3-col action row: rent | buy | view ——— */
.book-actions-3 {
    display: grid; grid-template-columns: 1fr 1fr auto;
    gap: 0.5rem; margin-top: auto;
}
.btn-view {
    padding: 0.65rem 0.75rem; border-radius: var(--radius-sm);
    font-family: 'Outfit', sans-serif; font-size: 0.8rem; font-weight: 700;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    gap: 0.35rem; transition: all var(--t-fast); text-decoration: none;
    background: var(--accent-soft); color: var(--accent);
    border: 1.5px solid rgba(245,176,66,0.22);
}
.btn-view:hover { background: var(--accent); color: #fff; border-color: var(--accent); }

/* ——— Card stagger animation ——— */
.book-card { animation: cardIn .38s ease both; }
.book-card:nth-child(1){animation-delay:.04s}
.book-card:nth-child(2){animation-delay:.08s}
.book-card:nth-child(3){animation-delay:.12s}
.book-card:nth-child(4){animation-delay:.16s}
.book-card:nth-child(5){animation-delay:.20s}
.book-card:nth-child(6){animation-delay:.24s}
.book-card:nth-child(7){animation-delay:.28s}
.book-card:nth-child(8){animation-delay:.32s}
@keyframes cardIn {
    from { opacity:0; transform:translateY(18px); }
    to   { opacity:1; transform:none; }
}
</style>
@endsection

@section('content')
@php
    $baseParams = request()->except('page', 'genre', 'availability');
    $hasFilters = !empty($searchQuery) || !empty($selectedGenre) || !empty($selectedAvailability);
    $wishlistIds = $wishlistIds ?? array_map('intval', session('wishlist', []));
@endphp

<!-- =============================================================
     FILTER BAR
     ============================================================= -->
<div class="filter-bar" style="flex-direction:column;align-items:flex-start;gap:0.75rem;">

    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;width:100%;">
        <span class="filter-label">Browse by</span>
        <div class="filter-tags">

            <a href="{{ route('customer.dashboard', $baseParams) }}"
               class="filter-tag {{ !$selectedGenre && !$selectedAvailability ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap"></i> All Books
            </a>

            <a href="{{ route('customer.dashboard', array_merge($baseParams, ['availability' => 'rent'])) }}"
               class="filter-tag {{ $selectedAvailability === 'rent' ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat"></i> For Rent
            </a>

            <a href="{{ route('customer.dashboard', array_merge($baseParams, ['availability' => 'buy'])) }}"
               class="filter-tag {{ $selectedAvailability === 'buy' ? 'active' : '' }}">
                <i class="bi bi-bag-check"></i> For Sale
            </a>

            @foreach($genres as $genre)
                <a href="{{ route('customer.dashboard', array_merge($baseParams, ['genre' => $genre])) }}"
                   class="filter-tag {{ $selectedGenre === $genre ? 'active' : '' }}">
                    {{ $genre }}
                </a>
            @endforeach

        </div>
    </div>

    @if($hasFilters)
        <div class="filter-row2">
            <a href="{{ route('customer.dashboard') }}"
               class="nav-btn" style="margin-left:auto;color:var(--danger);background:var(--danger-soft);border-color:rgba(255,77,109,0.22);">
                <i class="bi bi-x-circle-fill"></i> Clear All
            </a>
        </div>
    @endif

</div>

<!-- =============================================================
     RESULTS BAR
     ============================================================= -->
<div class="results-bar">
    <div style="flex:1;">
        <div class="results-text">
            Found <strong>{{ $totalBooks }}</strong> book{{ $totalBooks === 1 ? '' : 's' }}
            @if($searchQuery) matching <span class="hl">"{{ $searchQuery }}"</span>@endif
        </div>

        @if($selectedGenre || $selectedAvailability)
        <div class="active-pills">
            @if($selectedGenre)
            <span class="active-pill pill-blue">
                <i class="bi bi-tag-fill"></i> {{ $selectedGenre }}
            </span>
            @endif
            @if($selectedAvailability)
            <span class="active-pill pill-green">
                <i class="bi bi-check-circle-fill"></i>
                {{ $selectedAvailability === 'rent' ? 'For Rent' : 'For Sale' }}
            </span>
            @endif
        </div>
        @endif
    </div>

    @if($searchQuery)
    <a href="{{ route('customer.dashboard') }}" class="nav-btn" style="flex-shrink:0;">
        <i class="bi bi-x"></i> Clear Search
    </a>
    @endif
</div>

<!-- =============================================================
     SECTION HEADING
     ============================================================= -->
<div class="section-hd">
    <div class="section-hd-left">
        <div class="section-icon">
            @if($searchQuery)<i class="bi bi-search"></i>
            @elseif($selectedGenre)<i class="bi bi-tag-fill"></i>
            @else<i class="bi bi-collection-fill"></i>@endif
        </div>
        <div>
            <div class="section-title">
                @if($searchQuery) Search Results
                @elseif($selectedGenre) {{ $selectedGenre }}
                @elseif($selectedAvailability === 'rent') Books for Rent
                @elseif($selectedAvailability === 'buy') Books for Sale
                @else All Books @endif
            </div>
            <div class="section-sub">{{ $totalBooks }} book{{ $totalBooks === 1 ? '' : 's' }} available</div>
        </div>
    </div>
</div>

<!-- =============================================================
     BOOKS GRID
     ============================================================= -->
<div class="book-grid">

@forelse($books as $book)
<div class="book-card">

    <div class="book-img">
        @php
            $isWished = in_array((int) $book->id, $wishlistIds, true);
            $wishlistRoute = $isWished ? route('customer.wishlist.remove', $book->id) : route('customer.wishlist.add', $book->id);
        @endphp
        <a href="{{ $wishlistRoute }}"
           class="book-wish {{ $isWished ? 'active' : '' }}"
           aria-label="{{ $isWished ? 'Remove from wishlist' : 'Add to wishlist' }}">
            <i class="bi {{ $isWished ? 'bi-heart-fill' : 'bi-heart' }}"></i>
        </a>

        <a href="{{ route('customer.books.show', $book->id) }}" class="quick-view">
            <i class="bi bi-eye"></i> Quick View
        </a>

        @if($book->cover_image_path)
            <img src="{{ asset($book->cover_image_path) }}"
                 alt="{{ $book->title }}"
                 loading="lazy">
        @else
            <div class="d-flex align-items-center justify-content-center"
                 style="height:220px;background:var(--bg-raised);color:var(--text-muted);font-size:0.85rem;border-radius:14px;">
                No Image
            </div>
        @endif
    </div>

    <div class="book-body">

        @if($book->genre)
        <span class="book-genre-tag">{{ $book->genre }}</span>
        @endif

        <div class="book-title">{{ $book->title }}</div>
        <div class="book-author">{{ $book->author }}</div>

        <div class="pricing-row">
            <div class="price-pill price-rent">
                <span class="price-tag">Rent</span>
                <span class="price-amount">${{ number_format($book->rental_price, 2) }}</span>
                <span class="price-period">/month</span>
            </div>
            <div class="price-pill price-buy">
                <span class="price-tag">Buy</span>
                <span class="price-amount">${{ number_format($book->sale_price, 2) }}</span>
                <span class="price-period">one-time</span>
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <div class="avail-row {{ $book->available_rent > 0 ? 'avail-yes' : 'avail-no' }}">
                <span class="avail-dot"></span>
                @if($book->available_rent > 0)
                    {{ $book->available_rent }} available for rent
                @else
                    Not available for rent
                @endif
            </div>
            <div class="avail-row {{ $book->available_sale > 0 ? 'avail-yes' : 'avail-no' }}">
                <span class="avail-dot"></span>
                @if($book->available_sale > 0)
                    {{ $book->available_sale }} available to buy
                @else
                    Not available to buy
                @endif
            </div>
        </div>

        <div class="book-actions-3">
            @if($book->available_rent > 0)
            <a href="{{ route('customer.cart.add.rent', $book->id) }}" class="btn btn-rent">
                <i class="bi bi-arrow-repeat"></i> Rent
            </a>
            @else
            <span class="btn btn-disabled">
                <i class="bi bi-arrow-repeat"></i> Rent
            </span>
            @endif

            @if($book->available_sale > 0)
            <a href="{{ route('customer.cart.add.buy', $book->id) }}" class="btn btn-buy">
                <i class="bi bi-bag-check"></i> Buy
            </a>
            @else
            <span class="btn btn-disabled">
                <i class="bi bi-bag-check"></i> Buy
            </span>
            @endif

            <a href="{{ route('customer.books.show', $book->id) }}" class="btn-view">
                <i class="bi bi-eye-fill"></i>
            </a>
        </div>

    </div>
</div>

@empty
<div class="empty-state">
    <div class="empty-icon"><i class="bi bi-search"></i></div>
    <h3>No books found</h3>
    <p>Try different keywords or clear the active filters to see all books.</p>
    <a href="{{ route('customer.dashboard') }}" class="nav-pill" style="display:inline-flex;">
        <i class="bi bi-x-circle"></i> Clear All Filters
    </a>
</div>
@endforelse

</div>

@if($books->hasPages())
<div class="bh-pagination">
    @if($books->previousPageUrl())
    <a class="page-btn" href="{{ $books->previousPageUrl() }}">
        <i class="bi bi-chevron-left"></i> Previous
    </a>
    @endif

    <span class="page-btn current">
        Page {{ $books->currentPage() }} of {{ $books->lastPage() }}
    </span>

    @if($books->nextPageUrl())
    <a class="page-btn" href="{{ $books->nextPageUrl() }}">
        Next <i class="bi bi-chevron-right"></i>
    </a>
    @endif
</div>
@endif

@endsection
