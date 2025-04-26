<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analisis extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'kunci',
        'hasil',
        'keterangan'
    ];

    function analisis()
    {
        return $this->belongsTo(Analisis::class, 'asesmen_id', 'kode');
    }
}
