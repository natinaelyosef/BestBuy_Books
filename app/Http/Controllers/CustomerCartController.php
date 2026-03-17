<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CustomerCartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $cartItems = $this->buildCartItems($cart);
        $totalPrice = array_sum(array_column($cartItems, 'price'));

        return view('customer.cart', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'checkoutUrl' => route('orders.checkout'),
        ]);
    }

    public function addRent(Request $request, $bookId)
    {
        return $this->addItem($request, $bookId, 'rent');
    }

    public function addBuy(Request $request, $bookId)
    {
        return $this->addItem($request, $bookId, 'buy');
    }

    public function remove(Request $request, $bookId, $type)
    {
        $cart = $this->getCart($request);
        $cart = array_filter($cart, function ($item) use ($bookId, $type) {
            return !((int) $item['book_id'] === (int) $bookId && $item['type'] === $type);
        });

        $request->session()->put('cart', array_values($cart));

        return redirect()
            ->back()
            ->with('status', 'Book removed from cart.');
    }

    public function count(Request $request)
    {
        $cart = $this->getCart($request);
        return response()->json(['count' => count($cart)]);
    }

    public function checkout(Request $request)
    {
        if (!$this->ordersAvailable()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Orders are not available yet. Please run migrations.');
        }

        $cart = $this->getCart($request);
        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $cartItems = $this->buildCartItems($cart);

        $itemsByStore = collect($cartItems)->groupBy(function ($item) {
            return $item['book']->user_id;
        });

        $sequence = (Order::max('id') ?? 0);

        foreach ($itemsByStore as $storeId => $items) {
            $hasRent = false;
            $hasBuy = false;
            $total = 0;

            foreach ($items as $item) {
                $total += $item['price'];
                if ($item['type'] === 'rent') {
                    $hasRent = true;
                } else {
                    $hasBuy = true;
                }
            }

            $orderType = 'buy';
            if ($hasRent && !$hasBuy) {
                $orderType = 'rent';
            } elseif ($hasRent && $hasBuy) {
                $orderType = 'mixed';
            }

            $sequence++;
            $orderNumber = 'BH' . now()->format('Ymd') . str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->user()->id,
                'store_id' => $storeId,
                'order_type' => $orderType,
                'status' => 'pending',
                'total_amount' => $total,
                'delivery_option' => 'pickup',
                'delivery_fee' => 0,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $item['book']->id,
                    'item_type' => $item['type'],
                    'quantity' => 1,
                    'rental_days' => $item['type'] === 'rent' ? 30 : null,
                    'price' => $item['price'],
                ]);
            }
        }
        $request->session()->forget('cart');

        return redirect()
            ->route('orders.index')
            ->with('status', 'Order placed successfully.');
    }

    private function addItem(Request $request, $bookId, string $type)
    {
        $book = Book::find($bookId);
        if (!$book) {
            return redirect()
                ->back()
                ->with('error', 'Book not found.');
        }

        $cart = $this->getCart($request);

        foreach ($cart as $item) {
            if ((int) $item['book_id'] === (int) $book->id && $item['type'] === $type) {
                return redirect()
                    ->back()
                    ->with('status', 'Book already in cart.');
            }
        }

        $cart[] = [
            'book_id' => $book->id,
            'type' => $type,
        ];

        $request->session()->put('cart', $cart);

        return redirect()
            ->back()
            ->with('status', 'Book added to cart.');
    }

    private function getCart(Request $request): array
    {
        $cart = $request->session()->get('cart', []);
        return array_values(array_filter($cart, fn($item) => isset($item['book_id'], $item['type'])));
    }

    private function buildCartItems(array $cart): array
    {
        $items = [];
        foreach ($cart as $item) {
            $book = Book::find($item['book_id']);
            if (!$book) {
                continue;
            }

            $type = $item['type'] === 'buy' ? 'buy' : 'rent';
            $price = $type === 'rent' ? (float) $book->rental_price : (float) $book->sale_price;

            $items[] = [
                'book' => $book,
                'type' => $type,
                'type_label' => $type === 'rent' ? 'Rental' : 'Purchase',
                'type_class' => $type === 'rent' ? 'rental' : 'purchase',
                'period' => $type === 'rent' ? 'Monthly' : null,
                'price' => $price,
            ];
        }
        return $items;
    }

    private function ordersAvailable(): bool
    {
        return Schema::hasTable('orders') && Schema::hasTable('order_items');
    }
}
