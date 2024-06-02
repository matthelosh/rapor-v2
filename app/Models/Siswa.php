<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nisn',
        'nis',
        'nik',
        'nama',
        'jk',
        'alamat',
        'hp',
        'email',
        'foto',
        'agama',
        'angkatan',
        'sekolah_id'
    ];

    public function sekolah() {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }
}
