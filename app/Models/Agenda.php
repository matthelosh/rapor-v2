<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property string $pelaksana
 * @property string $mulai
 * @property string $selesai
 * @property string|null $deskripsi
 * @property int $is_libur
 * @property string $warna
 * @property string $tapel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $tipe
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guru> $pesertas
 * @property-read int|null $pesertas_count
 * @method static \Database\Factories\AgendaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda query()
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereIsLibur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda wherePelaksana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Agenda whereWarna($value)
 * @mixin \Eloquent
 */
class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'pelaksana',
        'mulai',
        'selesai',
        'deskripsi',
        'is_libur',
        'warna',
        'tapel',
        'tipe'
    ];

    public function pesertas()
    {
        return $this->belongsToMany(Guru::class, 'agenda_guru');
    }
}
