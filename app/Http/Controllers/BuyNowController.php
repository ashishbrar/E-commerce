<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class BuyNowController extends Controller
{
    public function buyNow(Request $request)
    {
        if ($request->has('product_ids')) {
            $productIds = $request->input('product_ids');
        } else {
            $productIds = [$request->input('product_id')];
        }

        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }

        return redirect()->route('buy.now.checkout', ['product_ids' => $productIds]);
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $productIds = $request->input('product_ids', []);
        if (empty($productIds) && $request->has('product_id')) {
            $productIds = [$request->input('product_id')];
        }
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }

        $products = Product::whereIn('id', $productIds)->get();
        $cart = collect();
        if ($user) {
            $cart = Cart::where('user_id', $user->id)
                        ->whereIn('product_id', $productIds)
                        ->get();
        }

        if ($products->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'No items selected');
        }

        return view('checkout.buy_now', compact('products', 'cart'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'full_name'   => 'required|string',
            'email'       => 'required|email',
            'phone'       => 'required|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'postal_code' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $productIds = $request->input('product_ids', []);
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $total = 0;
        foreach ($productIds as $id) {
            $product = $products->get($id);
            if (! $product) continue;
            $quantity = intval($request->input("quantity.{$id}", 1));
            if ($quantity < 1) $quantity = 1;
            $total += $product->price * $quantity;
        }

        $order = Order::create([
            'user_id'        => Auth::id(),
            'total'          => $total,
            'address'        => $request->address,
            'status'         => 'complete',
            'full_name'      => $request->full_name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'city'           => $request->city,
            'postal_code'    => $request->postal_code,
            'payment_method' => $request->payment_method,
        ]);

        foreach ($productIds as $id) {
            $product = $products->get($id);
            if (! $product) continue;
            $quantity = intval($request->input("quantity.{$id}", 1));
            if ($quantity < 1) $quantity = 1;

            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $product->price * $quantity,
                'address'    => $request->address,
            ]);
        }

        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->whereIn('product_id', $productIds)
                ->delete();
        }

        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}
