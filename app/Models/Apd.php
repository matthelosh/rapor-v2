<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $elemen_id
 * @property string $sub_elemen
 * @property string $teks
 * @property string $fase
 * @property string|null $tingkat
 * @property string|null $semester
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Elemenp5|null $elemen
 * @property-read \App\Models\p5|null $dimensi
 * @method static \Illuminate\Database\Eloquent\Builder|Apd newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apd newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Apd query()
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereElemenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereFase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereSubElemen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Apd whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Apd extends Model
{
    use HasFactory;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'elemen_id',
        'sub_elemen',
        'teks',
        'fase',
        'tingkat',
        'semester'
    ];

    public function elemen()
    {
        return $this->belongsTo(Elemenp5::class, 'elemen_id', 'id');
    }

    public function dimensi()
    {
        return $this->belongsToThrough(p5::class, Elemenp5::class);
    }
}
