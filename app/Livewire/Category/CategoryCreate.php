<?php

namespace App\Livewire\Category;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Category; 

class CategoryCreate extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $status = 'Active';
    public $image;
    public $parent_id;  

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:1024', 
            'status' => 'required|in:Active,Inactive',
        ]);

        $imagePath = $this->image ? $this->image->store('categories', 'public') : null;

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Category created successfully.');

        return redirect()->route('category.index');
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.category.category-create', compact('categories'));
    }
}
