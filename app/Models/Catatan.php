<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $rombel_id
 * @property string $tapel
 * @property string $semester
 * @property string $siswa_id
 * @property string $teks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Catatan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Catatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'rombel_id',
        'tapel',
        'semester',
        'siswa_id',
        'teks'
    ];
}
