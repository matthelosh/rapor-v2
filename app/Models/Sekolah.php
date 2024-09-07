<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'npsn',
        'nama',
        'logo',
        'alamat',
        'desa',
        'kecamatan',
        'kabupaten',
        'kode_pos',
        'telp',
        'email',
        'website',
        'ks_id',
        'gugus_id'
    ];


    protected function logo(): Attribute
    {
        return Attribute::make(
            get: fn($logo) => url('/storage/sekolah/' . $logo),
        );
    }

    function ops()
    {
        return $this->belongsTo(Guru::class, 'npsn', 'nip');
    }

    function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_sekolah');
    }

    function ks(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'ks_id', 'id');
    }


    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'sekolah_id', 'npsn');
    }

    function rombels()
    {
        return $this->hasMany(Rombel::class, 'sekolah_id', 'npsn');
    }

    function mapels()
    {
        return $this->belongsToMany(Mapel::class, 'mapel_sekolah');
    }
    function ekskuls()
    {
        return $this->belongsToMany(Ekskul::class, 'ekskul_sekolah');
    }

    function gugus()
    {
        return $this->belongsTo(Gugus::class, 'gugus_id', 'id');
    }
}
