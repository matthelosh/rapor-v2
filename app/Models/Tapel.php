<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $label
 * @property string|null $deskripsi
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rombel> $rombels
 * @property-read int|null $rombels_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tapel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tapel extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'label',
        'deskripsi',
        'is_active'
    ];

    public function rombels()
    {
        return $this->hasMany(Rombel::class, 'tapel', 'kode');
    }

    public function ijazahs()
    {
        return $this->hasMany(ArsipIjazah::class, 'tapel', 'kode');
    }
}
