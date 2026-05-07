<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $dimensi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Elemenp5> $elemens
 * @property-read int|null $elemens_count
 * @method static \Illuminate\Database\Eloquent\Builder|p5 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|p5 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|p5 query()
 * @method static \Illuminate\Database\Eloquent\Builder|p5 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|p5 whereDimensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|p5 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|p5 whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|p5 whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class p5 extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'dimensi'
    ];

    public function elemens()
    {
        return $this->hasMany(Elemenp5::class, 'dimensi_id', 'kode');
    }
}
