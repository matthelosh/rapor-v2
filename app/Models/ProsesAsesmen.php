<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $asesmen_id
 * @property string $siswa_id
 * @property string $mulai
 * @property string|null $selesai
 * @property string $status
 * @property string|null $skor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Asesmen|null $asesmen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jawaban> $jawabans
 * @property-read int|null $jawabans_count
 * @property-read \App\Models\Siswa|null $siswa
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereAsesmenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereSkor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProsesAsesmen whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProsesAsesmen extends Model
{
    use HasFactory;

    protected $fillable = [
        'asesmen_id',
        'siswa_id',
        'mulai',
        'selesai',
        'status',
        'skor'
    ];

    public function asesmen()
    {
        return $this->belongsTo(Asesmen::class, 'asesmen_id', 'kode');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'nisn');
    }

    public function jawabans()
    {
        return $this->hasMany(Jawaban::class, 'proses_id', 'id');
    }
}
