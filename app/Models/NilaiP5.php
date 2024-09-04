<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiP5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'siswa_id',
        'rombel_id',
        'tapel',
        'semester',
        'apd_id',
        'nilai',
        'keterangan'
    ];

    function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id');
    }
    function apd()
    {
        return $this->belongsTo(Apd::class, 'apd_id', 'id');
    }
}
