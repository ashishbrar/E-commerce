<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductGallery;


class ProductController extends Controller
{
    public function frontend()
    {
        $products = Product::all();
        return view('welcome', compact('products'));
    }
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $main_image = $product->image; 
        $gallery_images = ProductGallery::where('product_id', $id)->pluck('image')->toArray();
        if (is_string($gallery_images)) {
            $gallery_images = $gallery_images;
        }
        return view('livewire.products.product-page', compact('product', 'main_image', 'gallery_images'));
    }
}
