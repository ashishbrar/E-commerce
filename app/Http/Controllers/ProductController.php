<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductGallery;

class ProductController extends Controller
{
    /** Number of related products shown on the product detail page. */
    private const RELATED_LIMIT = 4;

    /** Number of category cards shown on the home page. */
    private const HOME_CATEGORY_LIMIT = 4;

    /**
     * Home / shop landing — active products + a few featured categories.
     */
    public function frontend()
    {
        $products = Product::with('category')
            ->where('status', 'Active')
            ->latest()
            ->get();

        $categories = Category::withCount('products')
            ->where('status', 'Active')
            ->latest()
            ->take(self::HOME_CATEGORY_LIMIT)
            ->get();

        return view('welcome', compact('products', 'categories'));
    }

    /**
     * Product detail page (route: products/{id}). Loads gallery + related products.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $main_image = $product->image;

        $gallery_images = ProductGallery::where('product_id', $id)
            ->pluck('image')
            ->toArray();

        $related_products = Product::with('category')
            ->where('status', 'Active')
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest()
            ->take(self::RELATED_LIMIT)
            ->get();

        return view('livewire.products.product-page', compact(
            'product',
            'main_image',
            'gallery_images',
            'related_products'
        ));
    }
}
