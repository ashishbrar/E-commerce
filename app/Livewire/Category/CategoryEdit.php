<?php

namespace App\Livewire\Category;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryEdit extends Component
{
    use WithFileUploads;

    public $name;
    public $status = 'Active';
    public $image;      
    public $oldImage;   
    public $category;

    public function mount($id)
    {
        $this->category = Category::findOrFail($id);
        $this->name = $this->category->name;
        $this->status = $this->category->status;
        $this->oldImage = $this->category->image; 
    }

   public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
            'image' => 'nullable|image|max:1024',
        ]);

        $this->category->name = $this->name;
        $this->category->status = $this->status;

        if ($this->image) {
            if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                Storage::disk('public')->delete($this->oldImage);
            }
        
            $imageName = $this->image->store('categories', 'public');
            $this->category->image = $imageName;
        } else {
            $this->category->image = $this->oldImage;
        }

        $this->category->save();

        session()->flash('success', 'Category edited successfully.');

        return redirect()->route('category.index');
    }

    public function render()
    {
        return view('livewire.category.category-edit');
    }
}
