<?php

namespace App\Livewire;

use App\Models\Borrower;
use App\Models\Book;
use App\Models\User;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class BorrowComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $id, $book_id, $user_id, $loan_date, $return_date, $status, $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $layout['title'] = 'Manage Borrowers';

        $query = Borrower::with(['book', 'user']);

        if (!empty($this->search)) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        $data['borrowers'] = $query->paginate(5);
        $data['books'] = Book::all();
        $data['users'] = User::where('role', '!=', 'admin')->get();

        return view('livewire.borrow-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
            'status' => 'required|in:borrow,returned'
        ]);

        Borrower::create([
            'book_id' => $this->book_id,
            'user_id' => $this->user_id,
            'loan_date' => $this->loan_date,
            'return_date' => $this->return_date,
            'status' => $this->status
        ]);

        session()->flash('success', 'Borrower record has been saved successfully');
        $this->reset(['book_id', 'user_id', 'loan_date', 'return_date', 'status']);
    }

    public function update($id)
    {
        $borrower = Borrower::find($id);

        if ($borrower) {
            $this->id = $borrower->id;
            $this->book_id = $borrower->book_id;
            $this->user_id = $borrower->user_id;
            $this->loan_date = $borrower->loan_date;
            $this->return_date = $borrower->return_date;
            $this->status = $borrower->status;
        }
    }

    public function updateBorrower()
    {
        $this->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
            'status' => 'required|in:borrow,returned'
        ]);

        $borrower = Borrower::find($this->id);

        if ($borrower) {
            $borrower->update([
                'book_id' => $this->book_id,
                'user_id' => $this->user_id,
                'loan_date' => $this->loan_date,
                'return_date' => $this->return_date,
                'status' => $this->status
            ]);

            session()->flash('success', 'Borrower record has been updated successfully');
            $this->reset(['id', 'book_id', 'user_id', 'loan_date', 'return_date', 'status']);
        }
    }

    public function confirm($id)
    {
        $this->id = $id;
    }

    public function destroy()
    {
        $borrower = Borrower::find($this->id);

        if ($borrower) {
            $borrower->delete();
            session()->flash('success', 'Borrower record has been deleted successfully');
            $this->reset('id');
        }
    }
}