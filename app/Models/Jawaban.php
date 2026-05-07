<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * 
 *
 * @property int $id
 * @property string $asesmen_id
 * @property string $siswa_id
 * @property int $soal_id
 * @property string $teks
 * @property int $is_benar
 * @property int $proses_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asesmen|null $asesmen
 * @property-read \App\Models\ProsesAsesmen|null $proses
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban query()
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereAsesmenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereIsBenar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereProsesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereSoalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereTeks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Jawaban whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Jawaban extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'siswa_id',
        'soal_id',
        'teks',
        'is_benar',
        'proses_id'
    ];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->setTimezone("Asia/Jakarta");
    }

    public function proses()
    {
        return $this->belongsTo(ProsesAsesmen::class, 'proses_id', 'id');
    }

    public function asesmen()
    {
        return $this->belongsTo(Asesmen::class, 'asesmen_id', 'kode');
    }
}
