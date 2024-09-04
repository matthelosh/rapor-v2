<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $fillable = [
        'tapel',
        'semester',
        'tanggal',
        'sekolah_id',
        'rombel_id',
        'nama',
        'deskripsi',
        'status'
    ];

    function sekolah()
    {
        return $this->BelongsTo(Sekolah::class, 'sekolah_id', 'npsn');
    }

    function rombel()
    {
        return $this->belongsTo(Rombel::class, 'rombel_id', 'kode');
    }

    function apds()
    {
        return $this->belongsToMany(Apd::class, 'dimensi_proyek', 'apd_id', 'proyek_id', 'id', 'id');
    }

    public function tapel()
    {
        return $this->belongsTo(Tapel::class, 'tapel', 'kode');
    }
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester', 'kode');
    }

    public function nilais()
    {
        return $this->hasMany(NilaiP5::class, 'proyek_id', 'id');
    }
    // function dimensis()
    // {
    //     return $this->hasManyThrough(p5::class, Apd::class, );
    // }
}
