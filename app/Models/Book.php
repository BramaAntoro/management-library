<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'category_id',
        'writer',
        'publisher',
        'isbn',
        'published_at',
        'quantity'
    ];

    public function categories(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
