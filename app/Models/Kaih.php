<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaih extends Model
{
    use HasFactory;

    protected $fillable = [
        "rombel_id",
        "siswa_id",
        "semester",
        "kebiasaan",
        "is_done",
        "waktu",
        "keterangan",
    ];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, "rombel_id", "kode");
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, "siswa_id", "nisn");
    }
}
