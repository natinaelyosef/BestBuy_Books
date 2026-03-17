<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $book->title }} - BookHub Store</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
      }

      body {
        background-color: #f8f9fa;
        color: #333;
        line-height: 1.6;
      }

      .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
      }

      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 30px;
      }

      .logo {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
        text-decoration: none;
      }

      .logo i {
        margin-right: 10px;
        color: #3498db;
      }

      .nav-links {
        display: flex;
        gap: 25px;
      }

      .nav-link {
        text-decoration: none;
        color: #2c3e50;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .nav-link:hover {
        color: #3498db;
      }

      .cart-badge {
        background-color: #e74c3c;
        color: white;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        margin-left: 5px;
      }

      .breadcrumb {
        margin-bottom: 30px;
      }

      .breadcrumb a {
        color: #3498db;
        text-decoration: none;
      }

      .book-detail-container {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 40px;
        margin-bottom: 50px;
      }

      .book-cover {
        background-color: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
      }

      .book-cover img {
        width: 100%;
        max-width: 300px;
        height: auto;
        border-radius: 8px;
        margin-bottom: 20px;
      }

      .store-badge {
        display: inline-block;
        background-color: #3498db;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 15px;
      }

      .book-info {
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }

      .book-title {
        font-size: 2.2rem;
        margin-bottom: 15px;
        color: #2c3e50;
      }

      .book-author {
        font-size: 1.3rem;
        color: #7f8c8d;
        margin-bottom: 20px;
      }

      .book-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        flex-wrap: wrap;
      }

      .meta-item {
        display: flex;
        flex-direction: column;
      }

      .meta-label {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 5px;
      }

      .meta-value {
        font-weight: 600;
        color: #2c3e50;
      }

      .pricing-section {
        background-color: #f8f9fa;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
      }

      .price-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
      }

      .price-option {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.3s;
        cursor: pointer;
      }

      .price-option.selected {
        border-color: #3498db;
        background-color: #f0f8ff;
      }

      .price-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .price-type {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #2c3e50;
      }

      .price-amount {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
      }

      .rent-price {
        color: #3498db;
      }

      .buy-price {
        color: #2ecc71;
      }

      .price-desc {
        font-size: 0.9rem;
        color: #7f8c8d;
      }

      .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
      }

      .btn {
        padding: 15px 30px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        flex: 1;
        text-decoration: none;
      }

      .btn-primary {
        background-color: #3498db;
        color: white;
      }

      .btn-primary:hover {
        background-color: #2980b9;
      }

      .btn-secondary {
        background-color: #2ecc71;
        color: white;
      }

      .btn-secondary:hover {
        background-color: #27ae60;
      }

      .btn-wishlist {
        background-color: #f39c12;
        color: white;
      }

      .btn-wishlist:hover {
        background-color: #d68910;
      }

      .btn-disabled {
        background-color: #bdc3c7;
        color: #7f8c8d;
        cursor: not-allowed;
      }

      .similar-books {
        margin-top: 50px;
      }

      .similar-books h2 {
        margin-bottom: 30px;
        color: #2c3e50;
      }

      .similar-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 25px;
      }

      .similar-card {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        text-decoration: none;
        color: inherit;
      }

      .similar-card:hover {
        transform: translateY(-5px);
      }

      .similar-card img {
        width: 100%;
        height: 180px;
        object-fit: cover;
      }

      .similar-card-content {
        padding: 15px;
      }

      .similar-card h4 {
        margin-bottom: 10px;
        color: #2c3e50;
      }

      .similar-card p {
        color: #7f8c8d;
        font-size: 0.9rem;
        margin-bottom: 15px;
      }

      .similar-card .price {
        font-weight: 600;
        color: #3498db;
      }

      .messages {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
      }

      .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 10px;
        animation: slideIn 0.3s ease-out;
      }

      .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
      }

      .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
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

      @media (max-width: 768px) {
        .book-detail-container {
          grid-template-columns: 1fr;
        }

        .nav-links {
          display: none;
        }

        .price-options {
          grid-template-columns: 1fr;
        }

        .action-buttons {
          flex-direction: column;
        }

        .similar-grid {
          grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
      }
    </style>
  </head>
  <body>
    @php
      $cartCount = count(session('cart', []));
      $wishlistCount = count(session('wishlist', []));
    @endphp

    @if (session('status') || session('error'))
    <div class="messages">
      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
      @endif
    </div>
    @endif

    <div class="container">
      <div class="header">
        <a href="{{ route('customer.dashboard') }}" class="logo">
          <i class="fas fa-book"></i>
          BookHub Store
        </a>
        <div class="nav-links">
          <a href="{{ route('customer.dashboard') }}" class="nav-link">
            <i class="fas fa-home"></i> Home
          </a>
          <a href="{{ route('wishlist.index') }}" class="nav-link">
            <i class="fas fa-heart"></i> Wishlist
            <span class="cart-badge">{{ $wishlistCount }}</span>
          </a>
          <a href="{{ route('cart.index') }}" class="nav-link">
            <i class="fas fa-shopping-cart"></i> Cart
            <span class="cart-badge">{{ $cartCount }}</span>
          </a>
        </div>
      </div>

      <div class="breadcrumb">
        <a href="{{ route('customer.dashboard') }}">Home</a> &gt;
        <a href="{{ route('customer.dashboard', ['genre' => $book->genre]) }}">{{ $book->genre }}</a>
        &gt;
        <span>{{ $book->title }}</span>
      </div>

      <div class="book-detail-container">
        <div class="book-cover">
          <div class="store-badge">Book Store</div>
          @if($book->cover_image_path)
          <img src="{{ asset($book->cover_image_path) }}" alt="{{ $book->title }}" />
          @else
          <img
            src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80"
            alt="{{ $book->title }}"
          />
          @endif
          <div style="margin-top: 20px">
            <div style="display: flex; gap: 10px; justify-content: center">
              <span
                style="
                  background-color: #e8f4fc;
                  color: #3498db;
                  padding: 5px 10px;
                  border-radius: 5px;
                  font-size: 0.9rem;
                "
              >
                {{ $book->genre }}
              </span>
              @if($book->publication_year)
              <span
                style="
                  background-color: #f0f0f0;
                  color: #666;
                  padding: 5px 10px;
                  border-radius: 5px;
                  font-size: 0.9rem;
                "
              >
                Published: {{ $book->publication_year }}
              </span>
              @endif
            </div>
          </div>
        </div>

        <div class="book-info">
          <h1 class="book-title">{{ $book->title }}</h1>
          <h2 class="book-author">by {{ $book->author }}</h2>

          <div class="book-meta">
            <div class="meta-item">
              <span class="meta-label">Total Copies</span>
              <span class="meta-value">{{ $book->total_copies }}</span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Available for Rent</span>
              <span
                class="meta-value"
                style="color: {{ $canRent ? '#27ae60' : '#e74c3c' }};"
              >
                {{ $book->available_rent }} copies
              </span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Available for Purchase</span>
              <span
                class="meta-value"
                style="color: {{ $canBuy ? '#27ae60' : '#e74c3c' }};"
              >
                {{ $book->available_sale }} copies
              </span>
            </div>
          </div>

          <div class="pricing-section">
            <h3 style="margin-bottom: 20px; color: #2c3e50">
              Choose Your Option
            </h3>

            <div class="price-options">
              <div class="price-option" id="rental-option">
                <div class="price-type">Rent This Book</div>
                <div class="price-amount rent-price">
                  ${{ number_format($book->rental_price, 2) }}
                </div>
                <div class="price-desc">per month</div>
                <div style="margin-top: 10px; font-size: 0.9rem">
                  @if($canRent)
                  <span style="color: #27ae60">
                    <i class="fas fa-check-circle"></i> {{ $book->available_rent }} available
                  </span>
                  @else
                  <span style="color: #e74c3c">
                    <i class="fas fa-times-circle"></i> Currently unavailable
                  </span>
                  @endif
                </div>
              </div>

              <div class="price-option" id="purchase-option">
                <div class="price-type">Buy This Book</div>
                <div class="price-amount buy-price">${{ number_format($book->sale_price, 2) }}</div>
                <div class="price-desc">one-time purchase</div>
                <div style="margin-top: 10px; font-size: 0.9rem">
                  @if($canBuy)
                  <span style="color: #27ae60">
                    <i class="fas fa-check-circle"></i> {{ $book->available_sale }} available
                  </span>
                  @else
                  <span style="color: #e74c3c">
                    <i class="fas fa-times-circle"></i> Currently unavailable
                  </span>
                  @endif
                </div>
              </div>
            </div>

            <div class="action-buttons">
              @if($canRent)
              <a href="{{ route('cart.add.rent', $book->id) }}" class="btn btn-primary">
                <i class="fas fa-exchange-alt"></i> Add to Rental Cart
              </a>
              @else
              <button class="btn btn-disabled" disabled>
                <i class="fas fa-exchange-alt"></i> Not Available for Rent
              </button>
              @endif

              @if($canBuy)
              <a href="{{ route('cart.add.buy', $book->id) }}" class="btn btn-secondary">
                <i class="fas fa-shopping-cart"></i> Add to Purchase Cart
              </a>
              @else
              <button class="btn btn-disabled" disabled>
                <i class="fas fa-shopping-cart"></i> Not Available for Purchase
              </button>
              @endif

              <a href="{{ route('wishlist.add', $book->id) }}" class="btn btn-wishlist">
                <i class="fas fa-heart"></i> Add to Wishlist
              </a>
            </div>
          </div>
        </div>
      </div>

      @if(!empty($similarBooks) && $similarBooks->count())
      <div class="similar-books">
        <h2>Similar Books You Might Like</h2>
        <div class="similar-grid">
          @foreach($similarBooks as $similarBook)
          <a href="{{ route('books.show', $similarBook->id) }}" class="similar-card">
            @if($similarBook->cover_image_path)
              <img src="{{ asset($similarBook->cover_image_path) }}" alt="{{ $similarBook->title }}" />
            @else
              <img
                src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80"
                alt="{{ $similarBook->title }}"
              />
            @endif
            <div class="similar-card-content">
              <h4>{{ \Illuminate\Support\Str::limit($similarBook->title, 30) }}</h4>
              <p>{{ $similarBook->author }}</p>
              <div
                style="
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                "
              >
                <span class="price">${{ number_format($similarBook->sale_price, 2) }}</span>
                <span style="font-size: 0.8rem; color: #7f8c8d">
                  @if($similarBook->available_rent > 0)
                  <i class="fas fa-exchange-alt" style="color: #3498db"></i>
                  Rent
                  @endif
                  @if($similarBook->available_sale > 0)
                  <i class="fas fa-shopping-cart" style="color: #2ecc71; margin-left: 5px"></i>
                  Buy
                  @endif
                </span>
              </div>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endif
    </div>

    <script>
      document.querySelectorAll(".price-option").forEach((option) => {
        option.addEventListener("click", function () {
          document.querySelectorAll(".price-option").forEach((opt) => {
            opt.classList.remove("selected");
          });
          this.classList.add("selected");
        });
      });

      setTimeout(() => {
        const messages = document.querySelectorAll(".alert");
        messages.forEach((message) => {
          message.style.transition = "opacity 0.5s";
          message.style.opacity = "0";
          setTimeout(() => message.remove(), 500);
        });
      }, 5000);
    </script>
  </body>
</html>
