<?php

namespace App\Livewire;

use App\Models\Borrower;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class BorrowComponent extends Component
{
    use WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';

    public $id, $book_id, $user_id, $loan_date, $return_date, $status, $search;
    public $actual_return_date, $fine_days, $fine_amount;

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
        $data['books'] = Book::where('quantity', '>', \DB::raw('total_borrowed'))->get();
        $data['allBooks'] = Book::all(); // For update modal
        $data['users'] = User::where('role', '!=', 'admin')->get();

        return view('livewire.borrow-component', $data)->layoutData($layout);
    }

    public function store()
    {
        $this->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date'
        ]);

        $book = Book::find($this->book_id);

        // Check if book is available
        if ($book->quantity <= $book->total_borrowed) {
            session()->flash('error', 'Book is not available for borrowing');
            return;
        }

        // Check if user already has this book borrowed
        $existingBorrow = Borrower::where('book_id', $this->book_id)
            ->where('user_id', $this->user_id)
            ->where('status', 'borrow')
            ->first();

        if ($existingBorrow) {
            session()->flash('error', 'User has already borrowed this book');
            return;
        }

        Borrower::create([
            'book_id' => $this->book_id,
            'user_id' => $this->user_id,
            'loan_date' => $this->loan_date,
            'return_date' => $this->return_date,
            'status' => 'borrow', // Always start as borrow
            'fine_days' => 0,
            'fine_amount' => 0
        ]);

        // Update book's total borrowed
        $book->increment('total_borrowed');

        session()->flash('success', 'Borrower record has been saved successfully');
        $this->reset(['book_id', 'user_id', 'loan_date', 'return_date']);
    }

    public function update($id)
    {
        $borrower = Borrower::find($id);

        if ($borrower) {
            $this->id = $borrower->id;
            $this->book_id = $borrower->book_id;
            $this->user_id = $borrower->user_id;
            $this->loan_date = $borrower->loan_date->format('Y-m-d');
            $this->return_date = $borrower->return_date->format('Y-m-d');
            $this->status = $borrower->status;
            $this->actual_return_date = $borrower->actual_return_date ? $borrower->actual_return_date->format('Y-m-d') : '';
            $this->fine_days = $borrower->fine_days;
            $this->fine_amount = $borrower->fine_amount;
        }
    }

    public function updateBorrower()
    {
        $this->validate([
            'book_id' => 'required|exists:books,id',
            'user_id' => 'required|exists:users,id',
            'loan_date' => 'required|date',
            'return_date' => 'required|date|after:loan_date',
            'status' => 'required|in:borrow,returned',
            'actual_return_date' => 'nullable|date'
        ]);

        $borrower = Borrower::find($this->id);
        $oldBookId = $borrower->book_id;
        $oldStatus = $borrower->status;

        if ($borrower) {
            // Calculate fine if status is being changed to returned
            $fineData = ['days' => 0, 'amount' => 0];
            if ($this->status === 'returned' && $this->actual_return_date) {
                $fineCalculation = $borrower->calculateFine($this->actual_return_date);
                $fineData = $fineCalculation;
            }

            $borrower->update([
                'book_id' => $this->book_id,
                'user_id' => $this->user_id,
                'loan_date' => $this->loan_date,
                'return_date' => $this->return_date,
                'status' => $this->status,
                'actual_return_date' => $this->status === 'returned' ? $this->actual_return_date : null,
                'fine_days' => $fineData['days'],
                'fine_amount' => $fineData['amount']
            ]);

            // Handle book quantity changes
            $oldBook = Book::find($oldBookId);
            $newBook = Book::find($this->book_id);

            // If book changed
            if ($oldBookId != $this->book_id) {
                if ($oldStatus === 'borrow') {
                    $oldBook->decrement('total_borrowed');
                }
                if ($this->status === 'borrow') {
                    $newBook->increment('total_borrowed');
                }
            } else {
                // Same book, check status change
                if ($oldStatus === 'borrow' && $this->status === 'returned') {
                    $newBook->decrement('total_borrowed');
                } elseif ($oldStatus === 'returned' && $this->status === 'borrow') {
                    $newBook->increment('total_borrowed');
                }
            }

            $message = 'Borrower record has been updated successfully';
            if ($fineData['amount'] > 0) {
                $message .= '. Fine applied: Rp ' . number_format($fineData['amount']) . ' (' . $fineData['days'] . ' days late)';
            }

            session()->flash('success', $message);
            $this->reset(['id', 'book_id', 'user_id', 'loan_date', 'return_date', 'status', 'actual_return_date', 'fine_days', 'fine_amount']);
        }
    }

    public function returnBook($id)
    {
        $borrower = Borrower::find($id);

        if ($borrower && $borrower->status === 'borrow') {
            $actualReturnDate = Carbon::now()->format('Y-m-d');
            $fineData = $borrower->calculateFine($actualReturnDate);

            $borrower->update([
                'status' => 'returned',
                'actual_return_date' => $actualReturnDate,
                'fine_days' => $fineData['days'],
                'fine_amount' => $fineData['amount']
            ]);

            // Decrease book's total borrowed
            $book = Book::find($borrower->book_id);
            $book->decrement('total_borrowed');

            $message = 'Book returned successfully';
            if ($fineData['amount'] > 0) {
                $message .= '. Fine applied: Rp ' . number_format($fineData['amount']) . ' (' . $fineData['days'] . ' days late)';
            } else {
                $message .= '. No fine - returned on time';
            }

            session()->flash('success', $message);
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
            // If borrower is in borrow status, decrease book's total borrowed
            if ($borrower->status === 'borrow') {
                $book = Book::find($borrower->book_id);
                $book->decrement('total_borrowed');
            }

            $borrower->delete();
            session()->flash('success', 'Borrower record has been deleted successfully');
            $this->reset('id');
        }
    }

    public function resetForm()
    {
        $this->reset(['book_id', 'user_id', 'loan_date', 'return_date', 'status', 'actual_return_date', 'fine_days', 'fine_amount']);
    }
}