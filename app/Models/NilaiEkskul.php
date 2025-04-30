<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $ekskul_id
 * @property string $tapel
 * @property string $semester
 * @property string $siswa_id
 * @property string $nilai
 * @property string $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ekskul|null $ekskul
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul query()
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereEkskulId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereNilai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|NilaiEkskul whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class NilaiEkskul extends Model
{
    use HasFactory;

    protected $fillable = [
        'ekskul_id',
        'tapel',
        'semester',
        'siswa_id',
        'nilai',
        'deskripsi'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class);
    }
}
