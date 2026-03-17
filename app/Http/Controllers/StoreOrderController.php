<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class StoreOrderController extends Controller
{
    public function index(Request $request)
    {
        $storeId = $request->user()->id;
        $statusFilter = $request->query('status', 'all');
        $search = $request->query('search');

        if (!$this->ordersAvailable()) {
            return view('store.orders', [
                'orders' => collect(),
                'statusFilter' => $statusFilter,
                'search' => $search,
                'pendingCount' => 0,
                'activeCount' => 0,
                'completedCount' => 0,
            ])->with('error', 'Orders are not available yet. Please run migrations.');
        }

        $query = Order::query()
            ->with(['customer', 'items.book'])
            ->where('store_id', $storeId);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        $orders = $query->latest('id')->get()->map(function (Order $order) {
            $itemsCount = $order->items->sum('quantity');
            $itemsPreview = $order->items
                ->take(2)
                ->map(fn ($item) => $item->book?->title ?? 'Unknown')
                ->implode(', ');

            $order->items_count = $itemsCount;
            $order->items_preview = $itemsPreview;
            $order->status_label = $this->statusLabel($order->status);

            return $order;
        });

        $pendingCount = $orders->where('status', 'pending')->count();
        $activeCount = $orders->whereIn('status', ['approved', 'preparing', 'ready', 'out_for_delivery'])->count();
        $completedCount = $orders->whereIn('status', ['delivered', 'completed'])->count();

        return view('store.orders', [
            'orders' => $orders,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'pendingCount' => $pendingCount,
            'activeCount' => $activeCount,
            'completedCount' => $completedCount,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        if (!$this->ordersAvailable()) {
            return redirect()
                ->back()
                ->with('error', 'Orders are not available yet. Please run migrations.');
        }

        if ($order->store_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'status' => 'required|in:pending,approved,preparing,ready,out_for_delivery,delivered,completed,cancelled',
            'store_notes' => 'nullable|string|max:1000',
        ]);

        $order->update($data);

        return redirect()
            ->back()
            ->with('messages', ['Order updated successfully.']);
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
