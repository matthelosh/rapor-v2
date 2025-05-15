<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transkrip extends Model
{
    use HasFactory;

    protected $fillable = [
        "kode",
        "siswa_id",
        "sekolah_id",
        "tapel",
        "nilai_rapor",
        "nilai_psaj",
        "nilai_akhir",
        "no_surat",
        "no_ijazah",
        "tanggal_lulus",
        "tanggal_terbit",
        "status",
        "penerbit",
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, "sekolah_id", "npsn");
    }
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, "siswa_id", "nisn");
    }

    public function diterbitkan_oleh()
    {
        return $this->belongsTo(Guru::class, "penerbit", "nip");
    }
}
