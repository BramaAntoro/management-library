<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookReturn extends Model
{
    use SoftDeletes;

    protected $fillable = ['borrower_id', 'return_date', 'fine'];

    public function borrower(): BelongsTo
    {
        return $this->belongsTo(Borrower::class);
    }
}
