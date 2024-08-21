<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apd extends Model
{
    use HasFactory;

    protected $fillable = [
        'elemen_id',
        'teks',
        'fase',
        'tingkat',
        'semester'
    ];

    public function elemen()
    {
        return $this->belongsTo(Elemenp5::class, 'elemen_id', 'id');
    }
}
