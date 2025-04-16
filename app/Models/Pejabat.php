<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
