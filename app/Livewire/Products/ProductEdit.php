<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductGallery;
use Illuminate\Support\Facades\Storage;

class ProductEdit extends Component
{
    use WithFileUploads;

    public $product;
    public $name, $description, $shortdescription, $status = 'Active', $price, $quantity;
    public $category_id;
    public $image;
    public $gallery_temp = [];
    public $oldImage;
    public $oldImageUrl;
    public $productGallery = []; 

    public function mount($id)
    {
        $this->product = Product::findOrFail($id);
        $this->name = $this->product->name;
        $this->description = $this->product->description;
        $this->shortdescription = $this->product->shortdescription;
        $this->price = $this->product->price;
        $this->category_id = $this->product->category_id;
        $this->status = $this->product->status;
        $this->quantity = $this->product->quantity;
        $this->oldImage = $this->product->image;
        $this->oldImageUrl = $this->oldImage ? asset('storage/' . $this->oldImage) : null;
        $this->productGallery = ProductGallery::where('product_id', $this->product->id)->get();
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'shortdescription' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:1024',
            'gallery_temp.*' => 'nullable|image|max:2048',
            'status' => 'required|in:Active,Inactive',
            'quantity' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = $this->oldImage;
        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
            $imagePath = $this->image->store('products', 'public');
        }

        $this->product->name = $this->name;
        $this->product->description = $this->description;
        $this->product->shortdescription = $this->shortdescription;
        $this->product->price = $this->price;
        $this->product->image = $imagePath;
        $this->product->status = $this->status;
        $this->product->quantity = $this->quantity;
        $this->product->category_id = $this->category_id;
        $this->product->save();

        if (is_array($this->gallery_temp) && count($this->gallery_temp)) {
            $oldGalleries = ProductGallery::where('product_id', $this->product->id)->get();
            foreach ($oldGalleries as $oldGallery) {
                if ($oldGallery->image && Storage::disk('public')->exists($oldGallery->image)) {
                    Storage::disk('public')->delete($oldGallery->image);
                }
                $oldGallery->delete();
            }
            foreach ($this->gallery_temp as $galleryImage) {
                $galleryPath = $galleryImage->store('products/gallery', 'public');
                $gallery = new ProductGallery();
                $gallery->product_id = $this->product->id;
                $gallery->image = $galleryPath;
                $gallery->save();
            }
        }

        $this->productGallery = ProductGallery::where('product_id', $this->product->id)->get();

        session()->flash('success', 'Product edited successfully.');
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
        return view('livewire.products.product-edit', [
            'categories' => Category::all(),
            'productGallery' => $this->productGallery, 
        ]);
    }
}
