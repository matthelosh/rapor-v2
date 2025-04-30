<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $dimensi_id
 * @property string $teks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Apd> $apds
 * @property-read int|null $apds_count
 * @property-read \App\Models\p5|null $dimensi
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 query()
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 whereDimensiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 whereTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemenp5 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Elemenp5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimensi_id',
        'teks',
    ];

    public function dimensi()
    {
        return $this->belongsTo(p5::class, 'dimensi_id', 'kode');
    }

    public function apds()
    {
        return $this->hasMany(Apd::class, 'elemen_id', 'id');
    }
}
