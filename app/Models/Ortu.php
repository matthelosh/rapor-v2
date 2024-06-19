<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ortu extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'nik',
        'nama',
        'relasi',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kode_pos',
        'kecamatan',
        'kabupaten',
        'hp'
    ];

    public function siswa() {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
