<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'cover',
        'category',
        'type',
        'slug',
        'title',
        'content',
        'user_id'
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($cover) => url('/storage/post/' . $cover),
        );
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
