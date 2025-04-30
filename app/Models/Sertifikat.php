<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $nomor
 * @property int $workshop_id
 * @property string $guru_id
 * @property string|null $jp
 * @property string|null $deskripsi
 * @property string|null $template
 * @property string|null $arsip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Guru|null $penerima
 * @property-read \App\Models\Workshop|null $workshop
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereArsip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereJp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sertifikat whereWorkshopId($value)
 * @mixin \Eloquent
 */
class Sertifikat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'workshop_id',
        'guru_id',
        'jp',
        'deskripsi',
        'template',
        'arsip'
    ];



    function penerima()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'nip');
    }

    function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
