<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductGallery;

class ProductCreate extends Component
{
    use WithFileUploads;
    public $name, $description, $shortdescription, $status = 'Active', $price, $quantity;
    public $category_id;
    public $image;
    public $gallery_temp = []; 

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'shortdescription' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:1024', 
            'gallery_temp.*' => 'image|max:2048',
            'quantity' => 'required',
            'status' => 'required|in:Active,Inactive',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        $product = new Product();
        $product->name = $this->name;
        $product->description = $this->description;
        $product->shortdescription = $this->shortdescription;
        $product->price = $this->price;
        $product->image = $imagePath;
        $product->status = $this->status;
        $product->quantity = $this->quantity;
        $product->category_id = $this->category_id;
        $product->save();

        if (is_array($this->gallery_temp) && count($this->gallery_temp)) {
            foreach ($this->gallery_temp as $galleryImage) {
                $galleryPath = $galleryImage->store('products/gallery', 'public');
                $gallery = new ProductGallery();
                $gallery->product_id = $product->id;
                $gallery->image = $galleryPath;
                $gallery->save();
            }
        }
        session()->flash('success', 'Product created successfully.');
        return redirect()->route('products.index');
    }
    public function removeGalleryImage($index)
    {
        if (isset($this->gallery_temp[$index])) {
            unset($this->gallery_temp[$index]);
            $this->gallery_temp = array_values($this->gallery_temp);
        }
    }

    public function render()
    {
        return view('livewire.products.product-create', [
            'categories' => Category::all(),
        ]);
    }
}
