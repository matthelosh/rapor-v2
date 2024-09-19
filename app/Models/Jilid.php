<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jilid extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id',
        'nama',
        'juz'
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'jilid_siswa');
    }

    public function jurnals()
    {
        return $this->hasMany(JurnalSpn::class);
    }
}
