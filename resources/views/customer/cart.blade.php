@extends('customer.base')

@section('title', 'Shopping Cart - BookHub Store')

@section('content')
<div class="container">
    <h1 class="cart-title">
        <i class="fas fa-shopping-cart"></i>
        Your Shopping Cart
    </h1>

    <div class="cart-container">
        <div class="cart-items">
            @if(!empty($cartItems))
                @foreach($cartItems as $item)
                <div class="cart-item">
                    <div class="item-image">
                        @if($item['book']->cover_image_path)
                            <img src="{{ asset($item['book']->cover_image_path) }}" alt="{{ $item['book']->title }}">
                        @else
                            <div class="item-image-fallback">No Image</div>
                        @endif
                    </div>
                    <div class="item-details">
                        <h3 class="item-title">{{ $item['book']->title }}</h3>
                        <p class="item-author">by {{ $item['book']->author }}</p>
                        <span class="item-type type-{{ $item['type_class'] }}">
                            @if($item['type'] === 'rent')
                                <i class="fas fa-exchange-alt"></i> Rental - {{ $item['period'] }}
                            @else
                                <i class="fas fa-shopping-cart"></i> Purchase
                            @endif
                        </span>
                    </div>
                    <div class="item-price">
                        <div class="price-amount">${{ number_format($item['price'], 2) }}</div>
                        <div class="item-actions">
                            <a href="{{ route('cart.remove', [$item['book']->id, $item['type']]) }}" class="btn btn-small btn-danger">
                                <i class="fas fa-trash"></i> Remove
                            </a>
                            <a href="{{ route('books.show', $item['book']->id) }}" class="btn btn-small btn-secondary">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Your cart is empty</h3>
                    <p>Add some books to get started!</p>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-book"></i> Browse Books
                    </a>
                </div>
            @endif
        </div>

        @if(!empty($cartItems))
        <div class="order-summary">
            <h3>Order Summary</h3>

            @foreach($cartItems as $item)
            <div class="summary-row">
                <span>{{ \Illuminate\Support\Str::limit($item['book']->title, 20) }}</span>
                <span>${{ number_format($item['price'], 2) }}</span>
            </div>
            @endforeach

            <div class="summary-row">
                <span>Subtotal</span>
                <span>${{ number_format($totalPrice, 2) }}</span>
            </div>

            <div class="summary-row">
                <span>Shipping</span>
                <span>$0.00</span>
            </div>

            <div class="summary-row">
                <span>Tax</span>
                <span>$0.00</span>
            </div>

            <div class="summary-row total">
                <span>Total</span>
                <span>${{ number_format($totalPrice, 2) }}</span>
            </div>

            <a href="{{ $checkoutUrl }}" class="btn btn-primary checkout-btn">
                <i class="fas fa-lock"></i> Proceed to Checkout
            </a>

            <div class="continue-shopping">
                <a href="{{ route('customer.dashboard') }}">
                    <i class="fas fa-arrow-left"></i> Continue Shopping
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('extra_css')
<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.cart-title {
    font-size: 2rem;
    color: #2c3e50;
    margin: 20px 0 30px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.cart-title i {
    color: #3498db;
}

.cart-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 30px;
}

.cart-items {
    background-color: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.empty-cart {
    text-align: center;
    padding: 50px 20px;
}

.empty-cart i {
    font-size: 4rem;
    color: #bdc3c7;
    margin-bottom: 20px;
}

.empty-cart h3 {
    margin-bottom: 15px;
    color: #2c3e50;
}

.empty-cart p {
    color: #7f8c8d;
    margin-bottom: 25px;
}

.btn {
    padding: 12px 30px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
    text-decoration: none;
}

.btn-primary {
    background-color: #3498db;
    color: white;
}

.btn-primary:hover {
    background-color: #2980b9;
}

.cart-item {
    display: grid;
    grid-template-columns: auto 2fr 1fr;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #eee;
    align-items: center;
}

.cart-item:last-child {
    border-bottom: none;
}

.item-image {
    width: 100px;
    height: 140px;
    border-radius: 8px;
    overflow: hidden;
    background: #f4f5f7;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 0.85rem;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-image-fallback {
    font-weight: 600;
}

.item-details {
    flex-grow: 1;
}

.item-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2c3e50;
}

.item-author {
    color: #7f8c8d;
    margin-bottom: 5px;
}

.item-type {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 10px;
}

.type-rental {
    background-color: #e8f4fc;
    color: #3498db;
}

.type-purchase {
    background-color: #e8f6ef;
    color: #27ae60;
}

.item-price {
    text-align: right;
}

.price-amount {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 10px;
}

.item-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 10px;
}

.btn-small {
    padding: 8px 15px;
    font-size: 0.9rem;
}

.btn-danger {
    background-color: #e74c3c;
    color: white;
}

.btn-danger:hover {
    background-color: #c0392b;
}

.btn-secondary {
    background-color: #f0f0f0;
    color: #333;
}

.order-summary {
    background-color: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.order-summary h3 {
    margin-bottom: 25px;
    color: #2c3e50;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 15px;
    padding: 8px 0;
}

.summary-row.total {
    border-top: 2px solid #f0f0f0;
    margin-top: 15px;
    padding-top: 20px;
    font-size: 1.2rem;
    font-weight: 700;
    color: #2c3e50;
}

.checkout-btn {
    width: 100%;
    padding: 16px;
    font-size: 1.1rem;
    margin-top: 25px;
}

.continue-shopping {
    margin-top: 20px;
    text-align: center;
}

.continue-shopping a {
    color: #3498db;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.continue-shopping a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .cart-container {
        grid-template-columns: 1fr;
    }

    .cart-item {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .item-price, .item-actions {
        text-align: center;
        justify-content: center;
    }
}
</style>
@endsection
