<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProsesP5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'siswa_id',
        'keterangan'
    ];

    function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
