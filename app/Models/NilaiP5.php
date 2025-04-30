<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $proyek_id
 * @property string $siswa_id
 * @property string $rombel_id
 * @property string $tapel
 * @property string $semester
 * @property int $apd_id
 * @property string $nilai
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Apd|null $apd
 * @property-read \App\Models\Proyek|null $proyek
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 query()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereApdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereProyekId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiP5 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NilaiP5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'siswa_id',
        'rombel_id',
        'tapel',
        'semester',
        'apd_id',
        'nilai',
        'keterangan'
    ];

    function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id', 'id');
    }
    function apd()
    {
        return $this->belongsTo(Apd::class, 'apd_id', 'id');
    }
}
