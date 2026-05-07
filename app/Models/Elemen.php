<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $mapel_id
 * @property string $fase
 * @property string $nama
 * @property string|null $agama
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cp|null $cp
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen query()
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereMapelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Elemen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Elemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'fase',
        'nama',
        'agama',
    ];

    public function cp()
    {
        return $this->belongsTo(Cp::class);
    }
}
