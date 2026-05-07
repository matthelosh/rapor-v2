<?php

namespace App\Helpers;

use App\Models\Mapel;

class MapelHelper
{
    public static function MyMapel($user)
    {
        if ($user->hasRole('guru_kelas')) {
            $sekolahId = $user->userable->sekolahs[0]->id;
            $mapels =  Mapel::whereHas('sekolah', function ($s) use ($sekolahId) {
                $s->where('sekolah_id', $sekolahId);
            })->get();

            return $mapels;
        } else {
            $sekolahId = $user->userable->sekolahs[0]->id;
            $mapels = Mapel::whereHas('sekolah', function ($s) use ($sekolahId) {
                $s->where('sekolah_id', $sekolahId);
            })->get();

            return $mapels;
        }
    }
}
