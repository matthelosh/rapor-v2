<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $rombel_id
 * @property string $tapel
 * @property string $semester
 * @property string $siswa_id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip query()
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereRombelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereTapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Arsip whereUrl($value)
 * @mixin \Eloquent
 */
class Arsip extends Model
{
    use HasFactory;
}
