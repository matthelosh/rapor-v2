<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property string|null $korwil
 * @property string|null $pangkat_korwil
 * @property string|null $nip_korwil
 * @property string|null $pengawas
 * @property string|null $pangkat_pengawas
 * @property string|null $nip_pengawas
 * @property string|null $ppai
 * @property string|null $pangkat_ppai
 * @property string|null $nip_ppai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereKorwil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereNipKorwil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereNipPengawas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereNipPpai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat wherePangkatKorwil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat wherePangkatPengawas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat wherePangkatPpai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat wherePengawas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat wherePpai($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pejabat whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pejabat extends Model
{
    use HasFactory;

    protected $fillable = [
    	'korwil',
    	'nip_korwil',
    	'pangkat_korwil',
    	'pengawas',
    	'pangkat_pengawas',
    	'nip_pengawas',
    	'ppai',
    	'pangkat_ppai',
    	'nip_ppai'
    ];
}
