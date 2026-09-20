<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'email', 'phone', 'address',
        'logo', 'website', 'status',
    ];

    protected $casts = ['status' => 'boolean'];

    public function books()
    {
        return $this->hasMany(Book::class);
    }
}