<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $fillable = ['siswa_id', 'tapel', 'semester', 'rombel_id', 'guru_id', 'status', 'mapel_id', 'jurnal_id'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }
}
