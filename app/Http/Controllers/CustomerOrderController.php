<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $timeFilter = $request->query('time', 'all');

        if (!$this->ordersAvailable()) {
            return view('customer.order_history', [
                'orders' => collect(),
                'activeRentals' => collect(),
                'statusFilter' => $statusFilter,
                'timeFilter' => $timeFilter,
            ])->with('error', 'Orders are not available yet. Please run migrations.');
        }

        $query = Order::query()
            ->with(['items.book', 'store'])
            ->where('customer_id', $request->user()->id);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($timeFilter !== 'all') {
            $cutoff = $timeFilter === '7days' ? now()->subDays(7) : now()->subDays(30);
            $query->where('created_at', '>=', $cutoff);
        }

        $orders = $query->latest('id')->get()->map(function (Order $order) {
            $itemsCount = $order->items->sum('quantity');
            $itemsPreview = $order->items
                ->take(2)
                ->map(function ($item) {
                    return $item->book?->title ?? 'Unknown';
                })
                ->implode(', ');

            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'created_at' => Carbon::parse($order->created_at),
                'order_type' => $order->order_type,
                'status' => $order->status,
                'status_label' => $this->statusLabel($order->status),
                'items_count' => $itemsCount,
                'items_preview' => $itemsPreview,
                'store_name' => $order->store?->name ?? 'BookHub',
                'total_amount' => $order->total_amount,
            ];
        });

        $activeRentals = $orders->filter(function ($order) {
            return $order['order_type'] === 'rent' && !in_array($order['status'], ['completed', 'cancelled'], true);
        })->values();

        return view('customer.order_history', [
            'orders' => $orders,
            'activeRentals' => $activeRentals,
            'statusFilter' => $statusFilter,
            'timeFilter' => $timeFilter,
        ]);
    }

    public function show(Request $request, $orderId)
    {
        if (!$this->ordersAvailable()) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Orders are not available yet. Please run migrations.');
        }

        $order = Order::query()
            ->with(['items.book', 'store'])
            ->where('customer_id', $request->user()->id)
            ->find($orderId);

        if (!$order) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Order not found.');
        }

        $orderData = [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'created_at' => Carbon::parse($order->created_at),
            'order_type' => $order->order_type,
            'status' => $order->status,
            'status_label' => $this->statusLabel($order->status),
            'total_amount' => $order->total_amount,
            'delivery_option' => $order->delivery_option,
            'delivery_fee' => $order->delivery_fee,
            'notes' => $order->notes,
            'store_notes' => $order->store_notes,
            'store' => [
                'store_name' => $order->store?->name ?? 'BookHub',
                'address' => null,
                'city' => null,
                'phone' => $order->store?->phone,
                'email' => $order->store?->email,
            ],
        ];

        $items = $order->items->map(function ($item) {
            return [
                'book' => $item->book,
                'item_type' => $item->item_type,
                'quantity' => $item->quantity,
                'rental_days' => $item->rental_days,
                'price' => $item->price,
            ];
        })->filter(fn ($item) => $item['book'])->values();

        $subtotal = $items->sum('price');

        return view('customer.order_detail', [
            'order' => $orderData,
            'items' => $items,
            'subtotal' => $subtotal,
            'delivery' => null,
        ]);
    }

    public function markFinished(Request $request, $orderId)
    {
        if (!$this->ordersAvailable()) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Orders are not available yet. Please run migrations.');
        }

        $order = Order::query()
            ->where('customer_id', $request->user()->id)
            ->find($orderId);

        if (!$order) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Order not found.');
        }

        $order->update(['status' => 'completed']);

        return redirect()
            ->back()
            ->with('status', 'Order marked as completed.');
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'preparing' => 'Preparing',
            'ready' => 'Ready',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'completed' => 'Completed',
            'cancelled' => 'Declined',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
    }

    private function ordersAvailable(): bool
    {
        return Schema::hasTable('orders') && Schema::hasTable('order_items');
    }
}
