<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id', 
        'loan_date',
        'return_date',
        'actual_return_date',
        'status',
        'fine_days',
        'fine_amount'
    ];

    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
        'actual_return_date' => 'date',
        'fine_amount' => 'decimal:2'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the book is overdue
     */
    public function isOverdue()
    {
        if ($this->status === 'returned') {
            return false;
        }
        
        return now() > $this->return_date;
    }

    /**
     * Calculate fine amount
     */
    public function calculateFine($actualReturnDate = null)
    {
        $actualDate = $actualReturnDate ? \Carbon\Carbon::parse($actualReturnDate) : now();
        $returnDate = \Carbon\Carbon::parse($this->return_date);
        
        if ($actualDate->gt($returnDate)) {
            $fineDays = $actualDate->diffInDays($returnDate);
            return [
                'days' => $fineDays,
                'amount' => $fineDays * 5000 // 5000 per day
            ];
        }
        
        return [
            'days' => 0,
            'amount' => 0
        ];
    }
}