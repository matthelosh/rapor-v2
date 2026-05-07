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
 * @property string $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Proyek|null $proyek
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 whereProyekId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesP5 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProsesP5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'proyek_id',
        'siswa_id',
        'keterangan'
    ];

    function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    function proyek()
    {
        return $this->belongsTo(Proyek::class, 'proyek_id');
    }
}
