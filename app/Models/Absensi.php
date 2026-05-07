<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $rombel_id
 * @property string $siswa_id
 * @property string $tapel
 * @property string $semester
 * @property int $ijin
 * @property int $sakit
 * @property int $alpa
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi query()
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereAlpa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereIjin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereSakit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Absensi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'siswa_id',
        'tapel',
        'semester',
        'ijin',
        'sakit',
        'alpa'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
