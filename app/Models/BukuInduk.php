<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuInduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'tanggal_masuk',
        'asal_sekolah',
        'tanggal_keluar',
        'alasan_sekolah',
        'status'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id','nisn');
    }
}
