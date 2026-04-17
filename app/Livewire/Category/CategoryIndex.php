<?php

namespace App\Livewire\Category;

use Livewire\Component;
use App\Models\Category;  

class CategoryIndex extends Component
{
    protected $listeners = ['deleteCategory' => 'delete'];

    public function render()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        
        return view('livewire.category.category-index', compact('categories'));
    }

    public function delete($id)
    {   
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            session()->flash("success", "Category deleted successfully");

            $this->dispatch('categoryDeleted', ['categoryName' => $category->name]);

            return redirect()->route('category.index');
        }

        session()->flash("error", "Category not found.");
        return redirect()->route('category.index');
    }
    
}
