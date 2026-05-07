<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $sekolah_id
 * @property int $jilid_id
 * @property string $guru_id
 * @property string $materi
 * @property string $fotos
 * @property string|null $absensis
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn query()
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereAbsensis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereFotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereJilidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereMateri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereSekolahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|JurnalSpn whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JurnalSpn extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id',
        'jilid_id',
        'guru_id',
        'materi',
        'fotos',
        'absensis',
        'keterangan'
    ];
}
