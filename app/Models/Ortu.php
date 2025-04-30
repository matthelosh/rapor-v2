<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $siswa_id
 * @property string $nama
 * @property string $relasi
 * @property string $alamat
 * @property string|null $hp
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $pekerjaan
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereRelasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ortu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ortu extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_id',
        'nama',
        'relasi',
        'alamat',
        'hp',
        'pekerjaan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }
}
