<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;


class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('status', 'Active')->latest()->get();
        $query = Product::query()
            ->with('category')
            ->where('status', 'Active');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->sort == 'low_high') {
            $query->orderBy('price');
        } elseif ($request->sort == 'high_low') {
            $query->orderByDesc('price');
        }
        $products = $query->latest()->paginate(12)->withQueryString();

        return view('shop.shop', compact('products', 'categories'));
    }
}
