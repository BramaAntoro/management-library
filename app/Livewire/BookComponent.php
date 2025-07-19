<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class BookComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $id, $title, $category_id, $writer, $publisher, $isbn, $published_at, $quantity, $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout['title'] = 'Manage books';

        $query = Book::with('category');

        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $data['books'] = $query->paginate(5);
        $data['categories'] = Category::all();

        return view('livewire.book-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'published_at' => 'required|date',
            'quantity' => 'required|integer|min:1'
        ]);

        Book::create([
            'title' => $this->title,
            'category_id' => $this->category_id,
            'writer' => $this->writer,
            'publisher' => $this->publisher,
            'isbn' => $this->isbn,
            'published_at' => $this->published_at,
            'quantity' => $this->quantity
        ]);

        session()->flash('success', 'Book has been saved successfully');
        $this->reset(['title', 'category_id', 'writer', 'publisher', 'isbn', 'published_at', 'quantity']);
    }

    public function update($id)
    {
        $book = Book::find($id);

        if ($book) {
            $this->id = $book->id;
            $this->title = $book->title;
            $this->category_id = $book->category_id;
            $this->writer = $book->writer;
            $this->publisher = $book->publisher;
            $this->isbn = $book->isbn;
            $this->published_at = $book->published_at;
            $this->quantity = $book->quantity;
        }
    }

    public function updateBook()
    {
        $this->validate([
            'title' => 'string|max:255',
            'category_id' => 'exists:categories,id',
            'published_at' => 'date',
            'quantity' => 'integer|min:1'
        ]);

        $book = Book::find($this->id);

        if ($book) {
            $book->update([
                'title' => $this->title,
                'category_id' => $this->category_id,
                'writer' => $this->writer,
                'publisher' => $this->publisher,
                'isbn' => $this->isbn,
                'published_at' => $this->published_at,
                'quantity' => $this->quantity
            ]);

            session()->flash('success', 'Book has been updated successfully');
            $this->reset(['id', 'title', 'category_id', 'writer', 'publisher', 'isbn', 'published_at', 'quantity']);
        }
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $book = Book::find($this->id);

        if ($book) {
            $book->delete();
            session()->flash('success', 'Book has been deleted successfully');
            $this->reset('id');
        }
    }
}
