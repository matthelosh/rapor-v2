<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apd extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'elemen_id',
        'sub_elemen',
        'teks',
        'fase',
        'tingkat',
        'semester'
    ];

    public function elemen()
    {
        return $this->belongsTo(Elemenp5::class, 'elemen_id', 'id');
    }

    public function dimensi()
    {
        return $this->belongsToThrough(p5::class, Elemenp5::class);
    }
}
