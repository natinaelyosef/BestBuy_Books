@extends('store.registration-layout')

@section('title', 'Order Details #'.$order->id)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Order Details #{{ $order->id }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Order Items</h4>
                    <span class="badge 
                        @if($order->status === 'pending') status-pending 
                        @elseif($order->status === 'approved') status-approved 
                        @elseif($order->status === 'onway') status-onway 
                        @elseif($order->status === 'delivered') status-delivered 
                        @elseif($order->status === 'cancelled') bg-danger 
                        @else bg-secondary @endif">
                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($order->orderItems as $item)
                            <li class="list-group-item d-flex">
                                <div class="col-2">
                                    @if($item->book->image_url)
                                        <img src="{{ $item->book->image_url }}" alt="{{ $item->book->title }}" class="img-fluid">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 100px;">
                                            <i class="bi bi-book" style="font-size: 2rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-6 ms-3">
                                    <h5>{{ $item->book->title }}</h5>
                                    <p class="text-muted">{{ Str::limit($item->book->description, 100) }}</p>
                                </div>
                                <div class="col-4 text-end">
                                    <p>Quantity: {{ $item->quantity }}</p>
                                    <p class="fw-bold">${{ number_format($item->price, 2) }} × {{ $item->quantity }} = 
                                        ${{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Order Information</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Order ID:</strong></td>
                            <td>#{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Customer:</strong></td>
                            <td>{{ $order->customer->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>{{ $order->customer->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phone:</strong></td>
                            <td>{{ $order->customer->phone ?? 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
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
                        </tr>
                        <tr>
                            <td><strong>Date:</strong></td>
                            <td>{{ $order->created_at->format('M d, Y \a\t g:i A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Amount:</strong></td>
                            <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Delivery Information</h4>
                </div>
                <div class="card-body">
                    <p><strong>Address:</strong> {{ $order->delivery_address }}</p>
                    <p><strong>Receiver:</strong> {{ $order->receiver_name }}</p>
                    <p><strong>Phone:</strong> {{ $order->phone_number }}</p>
                    @if($order->notes)
                        <p><strong>Notes:</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h4>Update Status</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('store.orders.update-status', $order->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Change Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="onway" {{ $order->status === 'onway' ? 'selected' : '' }}>On Way</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection