<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int|null $agenda_id
 * @property string $nama
 * @property string|null $lokasi
 * @property string|null $deskripsi
 * @property string $pelaksana
 * @property string $nara_sumber
 * @property string $mulai
 * @property string $selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guru> $pesertas
 * @property-read int|null $pesertas_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sertifikat> $sertifikats
 * @property-read int|null $sertifikats_count
 * @method static \Database\Factories\WorkshopFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop query()
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereAgendaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereNaraSumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop wherePelaksana($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Workshop whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Workshop extends Model
{
    use HasFactory;
    protected $fillable = [
        'agenda_id',
        'nama',
        'lokasi',
        'deskripsi',
        'pelaksana',
        'nara_sumber',
        'mulai',
        'selesai'
    ];

    public function pesertas()
    {
        return $this->belongsToMany(Guru::class, 'guru_workshop');
    }

    public function sertifikats()
    {
        return $this->hasMany(Sertifikat::class);
    }
}
