<?php
namespace App\Services;

use App\Models\Siswa;

class SiswaService 
{
    public function home($request) {
        $siswas = Siswa::all();

        return $siswas;
    }
}