<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EbookFile extends Model
{
    protected $fillable = ['book_id', 'file_path', 'file_type', 'file_size', 'is_primary'];
    protected $casts    = ['is_primary' => 'boolean'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
     /**
     * Public URL for the file.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    /**
     * Optional: nice name for the file link.
     */
    public function getNameAttribute(): string
    {
        return basename($this->file_path);
    }
}