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

        $cartItems = Cart::with('product')
            ->where('user_id', $user->id)
            ->get();

        $total = $cartItems->sum(function ($item) {
            return optional($item->product)->price * $item->quantity;
        });

        return view('livewire.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request, $id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($id);

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            if ($cartItem->quantity >= $product->quantity) {
                return back()->with('warning', 'Not enough stock available');
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
            'quantity' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        $product = Product::find($id);

        if (!$user || !$product) {
            return response()->json(['error' => 'User or Product not found.'], 404);
        }

        $quantity = (int) $request->quantity;

        if ($quantity > $product->quantity) {
            return response()->json(['error' => 'Requested quantity exceeds available stock.'], 400);
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();

        if ($quantity == 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }

        $cartTotal = Cart::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->sum(fn($item) => optional($item->product)->price * $item->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'cart_total' => $cartTotal,
        ]);
    }

    public function remove(Request $request, $id)
    {
        $user = Auth::user();

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $id)
            ->first();

        $cartItem->delete();

        $cartTotal = Cart::with('product')
            ->where('user_id', $user->id)
            ->get()
            ->sum(fn($item) => optional($item->product)->price * $item->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Product removed successfully.',
            'cart_total' => $cartTotal,
        ]);
    }
}
