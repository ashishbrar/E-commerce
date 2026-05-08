<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        $cartAdjusted = false;

        foreach ($cartItems as $cartItem) {
            if (! $cartItem->product || (int) $cartItem->product->quantity < 1) {
                $cartItem->delete();
                $cartAdjusted = true;
                continue;
            }

            if ((int) $cartItem->quantity > (int) $cartItem->product->quantity) {
                $cartItem->update(['quantity' => (int) $cartItem->product->quantity]);
                $cartAdjusted = true;
            }
        }

        if ($cartAdjusted) {
            session()->flash('warning', 'Some cart items were updated because stock changed.');

            $cartItems = Cart::with('product')
                ->where('user_id', $user->id)
                ->get();
        }

        $total = $cartItems->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });

        return view('livewire.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        if ((int) $product->quantity < 1) {
            return back()->with('warning', 'This product is out of stock.');
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            if ((int) $cartItem->quantity >= (int) $product->quantity) {
                return back()->with('warning', "Only {$product->quantity} item(s) available for {$product->name}.");
            }

            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
                'quantity'   => 1,
            ]);
        }

        return back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::find($id);

        if (!$user || !$product) {
            return response()->json(['error' => 'User or Product not found.'], 404);
        }

        $quantity = (int) $request->quantity;

        if ((int) $product->quantity < 1) {
            return response()->json(['error' => "{$product->name} is out of stock."], 400);
        }

        if ((int) $product->quantity < $quantity) {
            return response()->json(['error' => "Only {$product->quantity} item(s) available for {$product->name}."], 400);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        }

        $cartItem->update(['quantity' => $quantity]);

        $cartTotal = Cart::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->sum(fn($item) => optional($item->product)->price * $item->quantity);
        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
        ]);
    }

    public function remove(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Please log in first.'], 401);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Cart item not found.'], 404);
        }

        $cartItem->delete();

        $cartTotal = Cart::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->sum(fn($item) => optional($item->product)->price * $item->quantity);
        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product removed successfully.',
            'cart_total' => $cartTotal,
            'cart_count' => $cartCount,
        ]);
    }
}
