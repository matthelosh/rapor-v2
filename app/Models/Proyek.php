<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property \App\Models\Tapel|null $tapel
 * @property \App\Models\Semester|null $semester
 * @property string $sekolah_id
 * @property string $rombel_id
 * @property string|null $tanggal
 * @property string $nama
 * @property string $deskripsi
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProsesP5> $Keterangan
 * @property-read int|null $keterangan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Apd> $apds
 * @property-read int|null $apds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\NilaiP5> $nilais
 * @property-read int|null $nilais_count
 * @property-read \App\Models\Rombel|null $rombel
 * @property-read \App\Models\Sekolah|null $sekolah
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proyek whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function Keterangan()
    {
        return $this->hasMany(ProsesP5::class);
    }
    // function dimensis()
    // {
    //     return $this->hasManyThrough(p5::class, Apd::class, );
    // }
}
