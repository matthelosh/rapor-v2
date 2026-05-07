<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $asesmen_id
 * @property string $kunci
 * @property string $hasil
 * @property string $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Analisis|null $analisis
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis query()
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereAsesmenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereHasil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereKunci($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Analisis whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Analisis extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'kunci',
        'hasil',
        'keterangan'
    ];

    function analisis()
    {
        return $this->belongsTo(Analisis::class, 'asesmen_id', 'kode');
    }
}
