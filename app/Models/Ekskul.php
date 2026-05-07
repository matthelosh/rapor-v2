<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $nama
 * @property string|null $keterangan
 * @property string $sifat
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolah
 * @property-read int|null $sekolah_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereSifat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ekskul whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ekskul extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'keterangan',
        'sifat',
        'is_active'
    ];
    function sekolah()
    {
        return $this->belongsToMany(Sekolah::class, 'ekskul_sekolah');
    }
}
