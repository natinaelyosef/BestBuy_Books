@extends('store.registration-layout')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Orders</h2>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if($orders->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">No Orders Yet</h4>
                    <p class="text-muted">You haven't received any orders yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer->name }}<br><small class="text-muted">{{ $order->customer->email }}</small></td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($order->status === 'pending') status-pending 
                                        @elseif($order->status === 'approved') status-approved 
                                        @elseif($order->status === 'onway') status-onway 
                                        @elseif($order->status === 'delivered') status-delivered 
                                        @elseif($order->status === 'cancelled') bg-danger 
                                        @else bg-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('store.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{ $orders->links() }}
            @endif
        </div>
    </div>
</div>
@endsection