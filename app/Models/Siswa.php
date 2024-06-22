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
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'alamat',
        'hp',
        'email',
        'foto',
        'agama',
        'angkatan',
        'sekolah_id',
        'status'
    ];



    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    public function rombels()
    {
        return $this->belongsToMany(Rombel::class, 'rombel_siswa');
    }

    public function ortus()
    {
        return $this->hasMany(Ortu::class, 'siswa_id', 'nisn');
    }
}
