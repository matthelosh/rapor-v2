<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string $nama
 * @property string $tanggal
 * @property string $kategori
 * @property string $url
 * @property string|null $deskripsi
 * @property string|null $lokasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\GaleriFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri query()
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Galeri whereUrl($value)
 * @mixin \Eloquent
 */
class Galeri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tanggal',
        'kategori',
        'url',
        'deskripsi',
        'lokasi'
    ];
}
