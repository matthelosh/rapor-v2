<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'sifat',
        'is_active'
    ];
    function sekolah()
    {
        return $this->belongsToMany(Sekolah::class, 'ekskul_sekolah');
    }
}
