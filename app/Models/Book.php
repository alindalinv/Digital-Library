<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'isbn',
        'description',
        'cover_image',
        'category_id',
        'publisher_id',
        'published_year',
        'language',
        'pages',
        'price',
        'stock',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'published_year' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Book $book) {
            if (empty($book->slug)) {
                $book->slug = Str::slug($book->title) . '-' . Str::random(4);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class)->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}