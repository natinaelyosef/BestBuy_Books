@extends('customer.base')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Checkout</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Delivery Information</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.orders.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <textarea 
                                name="delivery_address" 
                                id="delivery_address" 
                                class="form-control @error('delivery_address') is-invalid @enderror" 
                                rows="3" 
                                required
                            >{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="receiver_name" class="form-label">Receiver Name</label>
                                    <input 
                                        type="text" 
                                        name="receiver_name" 
                                        id="receiver_name" 
                                        class="form-control @error('receiver_name') is-invalid @enderror" 
                                        value="{{ old('receiver_name', auth()->user()->name) }}" 
                                        required
                                    >
                                    @error('receiver_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input 
                                        type="tel" 
                                        name="phone_number" 
                                        id="phone_number" 
                                        class="form-control @error('phone_number') is-invalid @enderror" 
                                        value="{{ old('phone_number', auth()->user()->phone ?? '') }}" 
                                        required
                                    >
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes (Optional)</label>
                            <textarea 
                                name="notes" 
                                id="notes" 
                                class="form-control @error('notes') is-invalid @enderror" 
                                rows="2"
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Place Order</button>
                        <a href="{{ route('customer.cart') }}" class="btn btn-secondary">Back to Cart</a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Order Summary</h4>
                </div>
                <div class="card-body">
                    @foreach($groupedItems as $storeId => $items)
                        <div class="mb-4">
                            <h5>From: {{ $items[0]->book->store->name }}</h5>
                            <ul class="list-group">
                                @foreach($items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">{{ $item->book->title }}</div>
                                            <small>Qty: {{ $item->quantity }}</small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">${{ number_format($item->book->price * $item->quantity, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            
                            <div class="mt-2 pt-2 border-top">
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>${{ number_format($items->sum(function($item) { return $item->book->price * $item->quantity; }), 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection