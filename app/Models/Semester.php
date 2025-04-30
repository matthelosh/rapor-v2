<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $kode
 * @property string $label
 * @property string|null $deskripsi
 * @property int $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Semester newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Semester newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Semester query()
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereKode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Semester whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Semester extends Model
{
    use HasFactory;
    protected $fillable = [
        'kode',
        'label',
        'deskripsi',
        'is_active'
    ];
}
