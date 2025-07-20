<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrower;
use Carbon\Carbon;

class HomeComponent extends Component
{
    public $totalMembers;
    public $activeMembers;
    public $totalBooks;
    public $availableBooks;
    public $activeLoans;
    public $totalOverdue;

    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        // Total Members (assuming you have a User model for library members)
        $this->totalMembers = User::count();

        // Active Members (users who have borrowed books in the last 6 months)
        $activeUserIds = Borrower::where('loan_date', '>=', Carbon::now()->subMonths(6))
            ->distinct()
            ->pluck('user_id');
        $this->activeMembers = $activeUserIds->count();

        // Total Books
        $this->totalBooks = Book::sum('quantity');

        // Available Books (total books minus currently borrowed books)
        $borrowedBooks = Borrower::where('status', 'borrow')->count();
        $this->availableBooks = $this->totalBooks - $borrowedBooks;

        // Active Loans (currently borrowed books)
        $this->activeLoans = Borrower::where('status', 'borrow')->count();

        // Overdue Books
        $this->totalOverdue = Borrower::where('status', 'borrow')
            ->where('return_date', '<', Carbon::now()->toDateString())
            ->count();
    }

    public function render()
    {
        $title['title'] = 'Home library';
        return view('livewire.home-component')->layoutData($title);
    }
}