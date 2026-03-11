<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        $timeFilter = $request->query('time', 'all');

        $orders = collect($request->session()->get('orders', []));

        if ($statusFilter !== 'all') {
            $orders = $orders->where('status', $statusFilter);
        }

        if ($timeFilter !== 'all') {
            $cutoff = $timeFilter === '7days' ? now()->subDays(7) : now()->subDays(30);
            $orders = $orders->filter(function ($order) use ($cutoff) {
                return Carbon::parse($order['created_at'])->greaterThanOrEqualTo($cutoff);
            });
        }

        $orders = $orders->map(function ($order) {
            $order['created_at'] = Carbon::parse($order['created_at']);
            $order['status_label'] = $this->statusLabel($order['status']);
            $order['items_count'] = count($order['items'] ?? []);
            $order['items_preview'] = collect($order['items'] ?? [])
                ->take(2)
                ->map(function ($item) {
                    $book = Book::find($item['book_id']);
                    return $book?->title ?? 'Unknown';
                })
                ->implode(', ');
            $order['store_name'] = $order['store']['store_name'] ?? 'BookHub';
            return $order;
        })->values();

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
        $orders = collect($request->session()->get('orders', []));
        $order = $orders->firstWhere('id', (int) $orderId);

        if (!$order) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Order not found.');
        }

        $order['created_at'] = Carbon::parse($order['created_at']);
        $order['status_label'] = $this->statusLabel($order['status']);

        $items = collect($order['items'] ?? [])->map(function ($item) {
            $book = Book::find($item['book_id']);
            return [
                'book' => $book,
                'item_type' => $item['type'],
                'quantity' => $item['quantity'] ?? 1,
                'rental_days' => $item['rental_days'] ?? null,
                'price' => $item['price'] ?? 0,
            ];
        })->filter(fn ($item) => $item['book'])->values();

        $subtotal = $items->sum('price');

        return view('customer.order_detail', [
            'order' => $order,
            'items' => $items,
            'subtotal' => $subtotal,
            'delivery' => null,
        ]);
    }

    public function markFinished(Request $request, $orderId)
    {
        $orders = $request->session()->get('orders', []);
        foreach ($orders as &$order) {
            if ((int) $order['id'] === (int) $orderId) {
                $order['status'] = 'completed';
            }
        }
        unset($order);

        $request->session()->put('orders', $orders);

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
}
