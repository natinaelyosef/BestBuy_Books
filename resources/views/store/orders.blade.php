@extends('store.registration-layout')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Order Management</h1>
            <p class="text-muted mb-0">Review, update, and fulfill customer orders.</p>
        </div>
        <form method="GET" class="d-flex flex-wrap gap-2">
            <input type="text" name="search" value="{{ $search }}" class="form-control" style="min-width: 220px;" placeholder="Search order or customer">
            <select name="status" class="form-select" style="min-width: 180px;">
                <option value="all" @selected(($statusFilter ?? 'all') === 'all')>All Statuses</option>
                <option value="pending" @selected(($statusFilter ?? '') === 'pending')>Pending</option>
                <option value="approved" @selected(($statusFilter ?? '') === 'approved')>Approved</option>
                <option value="preparing" @selected(($statusFilter ?? '') === 'preparing')>Preparing</option>
                <option value="ready" @selected(($statusFilter ?? '') === 'ready')>Ready</option>
                <option value="out_for_delivery" @selected(($statusFilter ?? '') === 'out_for_delivery')>Out for Delivery</option>
                <option value="delivered" @selected(($statusFilter ?? '') === 'delivered')>Delivered</option>
                <option value="completed" @selected(($statusFilter ?? '') === 'completed')>Completed</option>
                <option value="cancelled" @selected(($statusFilter ?? '') === 'cancelled')>Declined</option>
            </select>
            <button type="submit" class="btn btn-outline-primary">Filter</button>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Pending Orders</div>
                    <div class="fs-3 fw-bold">{{ $pendingCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Active Orders</div>
                    <div class="fs-3 fw-bold">{{ $activeCount ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Completed Orders</div>
                    <div class="fs-3 fw-bold">{{ $completedCount ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-end">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            @php
                                $statusClass = match($order->status) {
                                    'pending' => 'warning',
                                    'approved' => 'primary',
                                    'preparing' => 'info',
                                    'ready' => 'info',
                                    'out_for_delivery' => 'secondary',
                                    'delivered', 'completed' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $order->order_number }}</div>
                                    <div class="text-muted small">{{ $order->created_at->format('M d, Y g:i A') }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer?->name ?? 'Customer' }}</div>
                                    <div class="text-muted small">{{ $order->customer?->email }}</div>
                                </td>
                                <td>
                                    <div class="small">{{ $order->items_count }} item{{ $order->items_count === 1 ? '' : 's' }}</div>
                                    <div class="text-muted small">
                                        {{ $order->items_preview }}@if($order->items_count > 2)...@endif
                                    </div>
                                </td>
                                <td class="fw-semibold">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ $order->status_label }}</span>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('store.orders.update-status', $order->id) }}" class="d-inline-flex gap-2 align-items-center flex-wrap justify-content-end">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" style="min-width: 160px;">
                                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                            <option value="approved" @selected($order->status === 'approved')>Approved</option>
                                            <option value="preparing" @selected($order->status === 'preparing')>Preparing</option>
                                            <option value="ready" @selected($order->status === 'ready')>Ready</option>
                                            <option value="out_for_delivery" @selected($order->status === 'out_for_delivery')>Out for Delivery</option>
                                            <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                                            <option value="completed" @selected($order->status === 'completed')>Completed</option>
                                            <option value="cancelled" @selected($order->status === 'cancelled')>Declined</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
