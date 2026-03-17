<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('customer_id', Auth::id())
            ->with(['orderItems.book', 'store'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function create(Request $request)
    {
        $cartItems = Auth::user()->cartItems()->with('book.store')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty!');
        }

        // Group cart items by store
        $groupedItems = $cartItems->groupBy('book.store_id');
        
        return view('customer.orders.checkout', compact('groupedItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_address' => 'required|string|max:500',
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cartItems = Auth::user()->cartItems()->with('book.store')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty!');
        }

        // Group cart items by store
        $groupedItems = $cartItems->groupBy('book.store_id');

        foreach ($groupedItems as $storeId => $items) {
            // Calculate total amount for this order
            $totalAmount = $items->sum(function($item) {
                return $item->book->price * $item->quantity;
            });

            // Create order
            $order = Order::create([
                'customer_id' => Auth::id(),
                'store_id' => $storeId,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'delivery_address' => $request->delivery_address,
                'receiver_name' => $request->receiver_name,
                'phone_number' => $request->phone_number,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $cartItem->book_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->book->price,
                ]);

                // Remove item from cart
                $cartItem->delete();
            }
        }

        return redirect()->route('customer.orders.index')->with('success', 'Order placed successfully!');
    }

    public function show(Order $order)
    {
        // Ensure the order belongs to the current customer
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.book', 'store']);

        return view('customer.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        // Only customers can mark orders as received
        if ($order->customer_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'delivered') {
            return redirect()->back()->with('error', 'Cannot mark as received. Order is not delivered yet.');
        }

        $order->update(['status' => 'received']);

        return redirect()->back()->with('success', 'Order marked as received!');
    }
}
