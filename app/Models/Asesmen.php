<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode', //npsn-rombel-mapel-sem-jenis-unique
        'nama',
        'deskripsi',
        'mapel_id',
        'tanggal',
        'jenjang',
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
        return $this->belongsToMany(Soal::class, 'asesmen_soal');
    }

    public function tapel()
    {
        return $this->belongsTo(Tapel::class, 'tapel', 'kode');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester', 'kode');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
