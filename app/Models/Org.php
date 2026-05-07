<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property string|null $deskripsi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guru> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder|Org newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Org newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Org query()
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Org whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Org extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'deskripsi'
    ];

    public function members()
    {
        return $this->belongsToMany(Guru::class, 'guru_org')->withPivot('jabatan');
    }
}
