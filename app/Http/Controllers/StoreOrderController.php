<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('store_id', Auth::id())
            ->with(['orderItems.book', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('store.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the current store
        if ($order->store_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.book', 'customer']);

        return view('store.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Only store owners can update order status
        if ($order->store_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,onway,delivered,cancelled'
        ]);

        $previousStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Add notification or log if needed
        activity()
            ->causedBy(Auth::user())
            ->performedOn($order)
            ->withProperties([
                'previous_status' => $previousStatus,
                'new_status' => $request->status
            ])
            ->log('Order status updated');

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
