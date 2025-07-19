<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrower extends Model
{
    use SoftDeletes;

    protected $fillable = ['book_id', 'user_id', 'loan_date', 'return_date', 'status'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function books(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
