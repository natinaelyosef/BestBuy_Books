@extends('customer.base')

@section('title', 'My Orders - BookHub')

@section('content')
<div class="container">
    <div style="max-width: 1200px; margin: 0 auto;">
        <div style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <div>
                    <h1 style="color: #2c3e50; margin-bottom: 10px;">My Orders</h1>
                    <p style="color: #7f8c8d;">
                        Track your book rentals and purchases
                    </p>
                </div>
                <div style="display: flex; gap: 15px;">
                    <a href="{{ route('customer.dashboard') }}" class="btn" 
                       style="background: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: 600;">
                        <i class="fas fa-book"></i> Browse Books
                    </a>
                </div>
            </div>
        </div>

        <div style="background: white; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; gap: 20px; align-items: center; flex-wrap: wrap;">
                <div>
                    <label style="display: block; margin-bottom: 8px; color: #7f8c8d; font-weight: 600;">Filter by Status</label>
                    <select id="status-filter" style="padding: 10px 15px; border-radius: 8px; border: 1px solid #ddd; min-width: 180px;">
                        <option value="all" {{ ($statusFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Statuses</option>
                        <option value="pending" {{ ($statusFilter ?? '') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($statusFilter ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="preparing" {{ ($statusFilter ?? '') === 'preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="ready" {{ ($statusFilter ?? '') === 'ready' ? 'selected' : '' }}>Ready</option>
                        <option value="out_for_delivery" {{ ($statusFilter ?? '') === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="delivered" {{ ($statusFilter ?? '') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="completed" {{ ($statusFilter ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ ($statusFilter ?? '') === 'cancelled' ? 'selected' : '' }}>Declined</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 8px; color: #7f8c8d; font-weight: 600;">Time Period</label>
                    <select id="time-filter" style="padding: 10px 15px; border-radius: 8px; border: 1px solid #ddd; min-width: 180px;">
                        <option value="all" {{ ($timeFilter ?? 'all') === 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="30days" {{ ($timeFilter ?? '') === '30days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="7days" {{ ($timeFilter ?? '') === '7days' ? 'selected' : '' }}>Last 7 Days</option>
                    </select>
                </div>

                <div style="margin-top: 25px;">
                    <button onclick="applyFilters()" style="background: #2ecc71; color: white; padding: 10px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        @if(!empty($orders) && $orders->count())
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8f9fa;">
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Order #</th>
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Date</th>
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Type</th>
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Items</th>
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Total</th>
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Status</th>
                            <th style="padding: 20px; text-align: left; color: #2c3e50; font-weight: 600;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr style="border-top: 1px solid #f8f9fa;">
                            <td style="padding: 20px;">
                                <strong style="color: #3498db;">{{ $order['order_number'] }}</strong>
                                <br>
                                <small style="color: #7f8c8d;">{{ $order['store_name'] }}</small>
                            </td>
                            <td style="padding: 20px;">
                                {{ $order['created_at']->format('M d, Y') }}
                                <br>
                                <small style="color: #7f8c8d;">{{ $order['created_at']->format('g:i A') }}</small>
                            </td>
                            <td style="padding: 20px;">
                                @if($order['order_type'] === 'rent')
                                <span style="background: #3498db; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                    RENTAL
                                </span>
                                @else
                                <span style="background: #2ecc71; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                    PURCHASE
                                </span>
                                @endif
                            </td>
                            <td style="padding: 20px;">
                                {{ $order['items_count'] }} item{{ $order['items_count'] === 1 ? '' : 's' }}
                                <br>
                                <small style="color: #7f8c8d;">
                                    {{ $order['items_preview'] }}
                                    @if($order['items_count'] > 2)...@endif
                                </small>
                            </td>
                            <td style="padding: 20px; font-weight: 600; color: #2c3e50;">
                                ${{ number_format($order['total_amount'], 2) }}
                            </td>
                            <td style="padding: 20px;">
                                <span style="display: inline-block; padding: 6px 15px; background: 
                                    {{ in_array($order['status'], ['completed','delivered'], true) ? '#27ae60' : ($order['status'] === 'cancelled' ? '#e74c3c' : ($order['status'] === 'out_for_delivery' ? '#9b59b6' : ($order['status'] === 'ready' ? '#f39c12' : '#3498db'))) }}; 
                                    color: white; border-radius: 15px; font-size: 0.8rem; font-weight: 600;">
                                    {{ $order['status_label'] }}
                                </span>
                            </td>
                            <td style="padding: 20px;">
                                <div style="display: flex; gap: 10px;">
                                    <a href="{{ route('orders.show', $order['id']) }}" 
                                       style="color: #3498db; text-decoration: none; padding: 6px 12px; border: 1px solid #3498db; border-radius: 4px; font-size: 0.9rem;">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($order['status'] === 'delivered')
                                    <form method="POST" action="{{ route('orders.finish', $order['id']) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="color: #f39c12; background: transparent; padding: 6px 12px; border: 1px solid #f39c12; border-radius: 4px; font-size: 0.9rem;">
                                            <i class="fas fa-check-double"></i> Finished
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="font-size: 5rem; color: #ecf0f1; margin-bottom: 20px;">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3 style="color: #7f8c8d; margin-bottom: 15px;">No orders found</h3>
            <p style="color: #bdc3c7; max-width: 400px; margin: 0 auto 25px;">
                You haven't placed any orders yet. Start browsing our collection to rent or buy books.
            </p>
            <a href="{{ route('customer.dashboard') }}" class="btn" 
               style="background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block;">
                <i class="fas fa-book"></i> Browse Books
            </a>
        </div>
        @endif

        @if(!empty($activeRentals) && $activeRentals->count())
        <div style="margin-top: 50px;">
            <h2 style="color: #2c3e50; margin-bottom: 20px; border-bottom: 2px solid #f8f9fa; padding-bottom: 15px;">
                <i class="fas fa-history"></i> Active Rentals
            </h2>

            <div class="book-grid" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @foreach($activeRentals as $order)
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                        <div>
                            <h4 style="margin: 0 0 5px 0; color: #2c3e50;">{{ $order['order_number'] }}</h4>
                            <p style="margin: 0; color: #7f8c8d; font-size: 0.9rem;">
                                Due: {{ $order['created_at']->format('M d') }}
                            </p>
                        </div>
                        <span style="background: #f39c12; color: white; padding: 4px 12px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                            ACTIVE
                        </span>
                    </div>

                    <div style="margin: 15px 0;">
                        <p style="margin: 0 0 5px 0; color: #2c3e50; font-weight: 600;">
                            {{ $order['items_preview'] ?: 'Rental Items' }}
                        </p>
                    </div>

                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <a href="{{ route('orders.show', $order['id']) }}" 
                           style="color: #3498db; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                        <a href="#" style="color: #e74c3c; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                            <i class="fas fa-redo"></i> Renew
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function applyFilters() {
    const status = document.getElementById('status-filter').value;
    const time = document.getElementById('time-filter').value;

    const params = new URLSearchParams();
    if (status !== 'all') {
        params.set('status', status);
    }
    if (time !== 'all') {
        params.set('time', time);
    }

    const url = '{{ route('orders.index') }}' + (params.toString() ? ('?' + params.toString()) : '');
    window.location.href = url;
}
</script>

<style>
.btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

table tr:hover {
    background: #f8f9fa;
}

.book-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}
</style>
@endsection
