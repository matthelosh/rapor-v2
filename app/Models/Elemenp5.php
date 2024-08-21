<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elemenp5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimensi_id',
        'teks',
    ];

    public function dimensi()
    {
        return $this->belongsTo(p5::class, 'dimensi_id', 'kode');
    }

    public function apds()
    {
        return $this->hasMany(Apd::class, 'elemen_id', 'id');
    }
}
