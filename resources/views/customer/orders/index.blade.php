@extends('customer.base')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Orders</h2>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">No Orders Yet</h4>
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('customer.books.index') }}" class="btn btn-primary">Browse Books</a>
                </div>
            @else
                <div class="row">
                    @foreach($orders as $order)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card order-card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Order #{{ $order->id }}</span>
                                <span class="badge 
                                    @if($order->status === 'pending') status-pending 
                                    @elseif($order->status === 'approved') status-approved 
                                    @elseif($order->status === 'onway') status-onway 
                                    @elseif($order->status === 'delivered') status-delivered 
                                    @else bg-secondary @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">From: {{ $order->store->name }}</h5>
                                <p class="card-text">
                                    <strong>Total:</strong> ${{ number_format($order->total_amount, 2) }}<br>
                                    <strong>Items:</strong> {{ $order->orderItems->count() }}<br>
                                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                {{ $orders->links() }}
            @endif
        </div>
    </div>
</div>
@endsection