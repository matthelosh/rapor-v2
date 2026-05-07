<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kokurikuler extends Model
{
    protected $fillable = [
        'siswa_id',
        'tapel',
        'semester',
        'rombel_id',
        'deskripsi',
        'guru_id'
    ];


    public function siswa () {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function guru () {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    public function rombel ()  {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

}
