<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'semester',
        'sekolah_id',
        'rombel_id',
        'nama',
        'deskripsi',
        'status'
    ];

    function sekolah()
    {
        return $this->BelongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }
}
