@extends('customer.base')

@section('title', 'Order Details - BookHub')

@section('content')
<div class="container">
    <div style="max-width: 900px; margin: 0 auto;">
        <div style="background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <h2 style="margin: 0; color: #2c3e50;">Order #{{ $order['order_number'] }}</h2>
                    <p style="color: #7f8c8d; margin: 5px 0 0 0;">
                        Placed on {{ $order['created_at']->format('F d, Y') }} at {{ $order['created_at']->format('g:i A') }}
                    </p>
                </div>
                <div style="text-align: right;">
                    <span style="display: inline-block; padding: 8px 20px; background: {{ in_array($order['status'], ['completed','delivered'], true) ? '#27ae60' : ($order['status'] === 'cancelled' ? '#e74c3c' : '#3498db') }}; 
                           color: white; border-radius: 20px; font-weight: 600; font-size: 0.9rem;">
                        {{ $order['status_label'] }}
                    </span>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
                <div>
                    <p style="color: #7f8c8d; margin: 0 0 5px 0; font-size: 0.9rem;">Order Type</p>
                    <p style="margin: 0; font-weight: 600; color: #2c3e50;">
                        {{ $order['order_type'] === 'rent' ? 'Rental' : ($order['order_type'] === 'mixed' ? 'Mixed' : 'Purchase') }}
                    </p>
                </div>
                <div>
                    <p style="color: #7f8c8d; margin: 0 0 5px 0; font-size: 0.9rem;">Total Amount</p>
                    <p style="margin: 0; font-weight: 600; color: #2c3e50;">${{ number_format($order['total_amount'], 2) }}</p>
                </div>
                <div>
                    <p style="color: #7f8c8d; margin: 0 0 5px 0; font-size: 0.9rem;">Delivery Method</p>
                    <p style="margin: 0; font-weight: 600; color: #2c3e50;">
                        {{ ($order['delivery_option'] ?? 'pickup') === 'delivery' ? 'Home Delivery' : 'Store Pickup' }}
                    </p>
                </div>
                <div>
                    <p style="color: #7f8c8d; margin: 0 0 5px 0; font-size: 0.9rem;">Store</p>
                    <p style="margin: 0; font-weight: 600; color: #2c3e50;">{{ $order['store']['store_name'] ?? 'BookHub' }}</p>
                </div>
            </div>
        </div>
        
        <div style="background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; color: #2c3e50; border-bottom: 2px solid #f8f9fa; padding-bottom: 15px;">
                Order Items ({{ $items->count() }})
            </h3>
            
            <div style="margin-top: 20px;">
                @foreach($items as $item)
                <div style="display: flex; padding: 20px; background: #f8f9fa; border-radius: 8px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 5px 0; color: #2c3e50;">{{ $item['book']->title }}</h4>
                        <p style="margin: 0 0 10px 0; color: #7f8c8d;">by {{ $item['book']->author }}</p>
                        <div style="display: flex; gap: 20px; font-size: 0.9rem;">
                            <span style="background: {{ $item['item_type'] === 'rent' ? '#3498db' : '#2ecc71' }}; 
                                  color: white; padding: 4px 12px; border-radius: 12px;">
                                {{ $item['item_type'] === 'rent' ? 'Rental' : 'Purchase' }}
                            </span>
                            <span>Quantity: {{ $item['quantity'] }}</span>
                            @if($item['item_type'] === 'rent' && $item['rental_days'])
                            <span>Rental Period: {{ $item['rental_days'] }} days</span>
                            @endif
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0; font-size: 1.2rem; font-weight: 600; color: #2c3e50;">
                            ${{ number_format($item['price'], 2) }}
                        </p>
                        @if($item['item_type'] === 'rent')
                        <p style="margin: 5px 0 0 0; color: #7f8c8d; font-size: 0.9rem;">
                            ${{ number_format($item['book']->rental_price, 2) }}/day
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #f8f9fa;">
                <div style="max-width: 300px; margin-left: auto;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d;">Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if(($order['delivery_fee'] ?? 0) > 0)
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="color: #7f8c8d;">Delivery Fee</span>
                        <span>${{ number_format($order['delivery_fee'], 2) }}</span>
                    </div>
                    @endif
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 600; padding-top: 10px; border-top: 1px solid #f8f9fa;">
                        <span>Total</span>
                        <span>${{ number_format($order['total_amount'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="background: white; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0; color: #2c3e50; border-bottom: 2px solid #f8f9fa; padding-bottom: 15px;">
                Store Information
            </h3>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 20px;">
                <div>
                    <h4 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 1.1rem;">
                        {{ $order['store']['store_name'] ?? 'BookHub' }}
                    </h4>
                    @if(!empty($order['store']['address']))
                    <p style="margin: 0 0 10px 0; color: #7f8c8d;">
                        <i class="fas fa-map-marker-alt"></i> {{ $order['store']['address'] }}, {{ $order['store']['city'] }}
                    </p>
                    @endif
                    @if(!empty($order['store']['phone']))
                    <p style="margin: 0 0 10px 0; color: #7f8c8d;">
                        <i class="fas fa-phone"></i> {{ $order['store']['phone'] }}
                    </p>
                    @endif
                    @if(!empty($order['store']['email']))
                    <p style="margin: 0 0 10px 0; color: #7f8c8d;">
                        <i class="fas fa-envelope"></i> {{ $order['store']['email'] }}
                    </p>
                    @endif
                </div>
                
                <div>
                    <h4 style="margin: 0 0 10px 0; color: #2c3e50; font-size: 1.1rem;">Order Notes</h4>
                    @if(!empty($order['notes']))
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                        <p style="margin: 0; color: #2c3e50;"><strong>Your Notes:</strong> {{ $order['notes'] }}</p>
                    </div>
                    @endif
                    @if(!empty($order['store_notes']))
                    <div style="background: #e8f4f8; padding: 15px; border-radius: 8px; border-left: 4px solid #3498db;">
                        <p style="margin: 0; color: #2c3e50;"><strong>Store Notes:</strong> {{ $order['store_notes'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div style="display: flex; justify-content: space-between; gap: 15px; margin-top: 30px;">
            <a href="{{ route('orders.index') }}" class="btn" 
               style="background: #95a5a6; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            
            <div style="display: flex; gap: 15px;">
                @if($order['status'] === 'delivered')
                <form method="POST" action="{{ route('orders.finish', $order['id']) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn" 
                        style="background: #16a085; color: white; padding: 12px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; border: none;">
                        <i class="fas fa-check-double"></i> Finished
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.btn:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}
</style>
@endsection
