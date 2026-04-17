<?php

namespace App\Livewire\Products;

use Livewire\Component;
use App\Models\Product;

class ProductIndex extends Component
{
    protected $listeners = ['deleteProduct' => 'delete'];

    public function render()
    {
        $products = Product::orderBy('id', 'desc')->get();
        return view('livewire.products.product-index', compact('products'));
    }

    public function delete($id)
    {   
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            session()->flash("success", "Product deleted successfully");

            $this->dispatch('livewire:productDeleted', ['productName' => $product->name]);

            return redirect()->route('products.index');
        }

        session()->flash("error", "Product not found.");
        return redirect()->route('products.index');
    }
}
