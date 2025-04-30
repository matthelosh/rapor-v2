<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $elemen_id
 * @property string $fase
 * @property string $mapel_id
 * @property string $teks
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Mapel|null $mapel
 * @method static \Illuminate\Database\Eloquent\Builder|Cp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cp query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereElemenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cp extends Model
{
    use HasFactory;

    protected $fillable = [
        'elemen_id',
        'fase',
        'mapel_id',
        'teks'
    ];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'mapel_id', 'kode');
    }
}
