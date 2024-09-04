<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'mapel_id',
        'tanggal',
        'mulai',
        'selesai',
        'jenis',
        'rombel_id',
        'sekolah_id',
        'semester',
        'tapel',
        'guru_id'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
    public function rombel()
    {
        return $this->belongsTo(ROmbel::class, 'rombel_id', 'kode');
    }

    public function  soals()
    {
        return $this->belongsToMany(Soal::class, 'asesmen_soal', 'id', 'id');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
