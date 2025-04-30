<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property \App\Models\Sekolah|null $sekretariat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sekolah> $sekolahs
 * @property-read int|null $sekolahs_count
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus whereSekretariat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gugus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Gugus extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi',
        'sekretariat'
    ];

    public function sekolahs()
    {
        return $this->hasMany(Sekolah::class, 'gugus_id', 'id');
    }

    public function sekretariat()
    {
        return $this->belongsTo(Sekolah::class, 'sekretariat', 'npsn');
    }
}
