<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EbookFile extends Model
{
    protected $fillable = ['book_id', 'file_path', 'file_type', 'file_size', 'is_primary'];
    protected $casts    = ['is_primary' => 'boolean'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}