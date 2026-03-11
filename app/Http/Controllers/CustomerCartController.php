<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CustomerCartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $items = $this->buildCartItems($cart);

        $totalPrice = collect($items)->sum('price');

        return view('customer.cart', [
            'cartItems' => $items,
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
        $type = $type === 'buy' ? 'buy' : 'rent';
        $cart = $this->getCart($request);

        $cart = array_values(array_filter($cart, function ($item) use ($bookId, $type) {
            return !((int) $item['book_id'] === (int) $bookId && $item['type'] === $type);
        }));

        $request->session()->put('cart', $cart);

        return redirect()
            ->back()
            ->with('status', 'Item removed from cart.');
    }

    public function count(Request $request)
    {
        $cart = $this->getCart($request);
        return response()->json(['count' => count($cart)]);
    }

    public function checkout(Request $request)
    {
        $cart = $this->getCart($request);
        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Your cart is empty.');
        }

        $items = $this->buildCartItems($cart);
        $total = collect($items)->sum('price');

        $orderType = 'buy';
        $hasRent = collect($items)->contains(fn ($item) => $item['type'] === 'rent');
        $hasBuy = collect($items)->contains(fn ($item) => $item['type'] === 'buy');
        if ($hasRent && !$hasBuy) {
            $orderType = 'rent';
        } elseif ($hasRent && $hasBuy) {
            $orderType = 'mixed';
        }

        $orders = $request->session()->get('orders', []);
        $nextId = count($orders) + 1;
        $orderNumber = 'BH' . now()->format('Ymd') . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);

        $orders[] = [
            'id' => $nextId,
            'order_number' => $orderNumber,
            'created_at' => now()->toDateTimeString(),
            'order_type' => $orderType,
            'status' => 'pending',
            'total_amount' => $total,
            'delivery_option' => 'pickup',
            'delivery_fee' => 0,
            'delivery_address' => null,
            'notes' => null,
            'store_notes' => null,
            'store' => [
                'store_name' => 'BookHub',
                'address' => null,
                'city' => null,
                'phone' => null,
                'email' => null,
            ],
            'items' => array_map(function ($item) {
                return [
                    'book_id' => $item['book']->id,
                    'type' => $item['type'],
                    'price' => $item['price'],
                    'quantity' => 1,
                    'rental_days' => $item['type'] === 'rent' ? 30 : null,
                ];
            }, $items),
        ];

        $request->session()->put('orders', $orders);
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
        return array_values(array_filter($cart, fn ($item) => isset($item['book_id'], $item['type'])));
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
}
