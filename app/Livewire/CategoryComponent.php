<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class CategoryComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $id, $name, $description, $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout['title'] = 'Manage Category';

        $query = Category::query();

        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $data['categories'] = $query->paginate(5);

        return view('livewire.category-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        session()->flash('success', 'Category has been saved successfully');
        $this->reset(['name', 'description']);
    }

    public function update($id)
    {
        $category = Category::find($id);

        if ($category) {
            $this->id = $category->id;
            $this->name = $category->name;
            $this->description = $category->description;
        }
    }

    public function updateCategory()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = Category::find($this->id);

        if ($category) {
            $category->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            session()->flash('success', 'Category has been updated successfully');
            $this->reset(['id', 'name', 'description']);
        }
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $category = Category::find($this->id);

        if ($category) {
            $category->delete();
            session()->flash('success', 'Category has been deleted successfully');
            $this->reset('id');
        }
    }
}
