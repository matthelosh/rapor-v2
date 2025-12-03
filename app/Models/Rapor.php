<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory;
    protected $fillable = [
        "kode",
        "siswa_id",
        "sekolah",
        "fase",
        "tingkat",
        "kelas",
        "semester",
        "tapel",
        "nilai_akademik",
        "nilai_akhir",
        "ekskul",
        "catatan",
        "keputusan",
        "absensi",
        "ttd",
        "tanggal_rapor",
        "is_tuntas",
        "status",
    ];

    protected $primaryKey = "kode";

    public $incrementing = false;

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, "siswa_id", "nisn");
    }

    // public function rombel()
    // {
    //     return $this->belongsTo(Rombel::class, "rombel_id", "kode");
    // }

    // public function wali_kelas()
    // {
    //     return $this->belongsTo(Guru::class, "guru_id", "nip");
    // }

    // public function sekolah()
    // {
    //     return $this->belongsTo(Sekolah::class, "sekolah_id", "npsn");
    // }

    // public function kepsek()
    // {
    //     return $this->belongsTo(Guru::class, "ks", "nip");
    // }

    // public function details()
    // {
    //     return $this->hasMany(RaporDetail::class, "rapor_id", "kode");
    // }
}
