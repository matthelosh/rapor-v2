<?php

namespace App\Services;

use App\Models\Mapel;
use App\Models\Rombel;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class NilaiService
{


    /** @var \App\Models\User */
    // private $user = auth()->user();

    public function home()
    {
        /** @var \App\Models\User */
        $user = auth()->user();
        $mapels = [
            'guru_agama',
            'guru_pjok',
            'guru_inggris'
        ];
        if ($user->hasRole('guru_kelas')) {
            $rombel = Rombel::where('guru_id', $user->userable->id)->first();
            $sekolahId = $user->userable->sekolahs[0]->id;
            $datas = Mapel::where('fase', 'LIKE', '%' . $rombel->fase . '%')->whereHas('sekolah', function ($q) use ($sekolahId) {
                $q->where('sekolahs.id', $sekolahId);
            })->get();
        } elseif ($user->hasRole('guru_agama')) {
            $guruId = $user->userable->id;
            $agama = $user->userable->agama;
            $datas = Sekolah::whereHas('gurus', function ($q) use ($guruId) {
                $q->where('gurus.id', $guruId);
            })->with('ks')->with('rombels.siswas', function ($q) use ($agama) {
                $q->where('siswas.agama', $agama);
            })->get();
            // foreach($user->userable->sekolahs as $sekolah) {
            //     $rombels = Rombel::where('sekolah_id', $sekolah->npsn)->with('siswas')->get();
            //     foreach($rombels as $rombel) {
            //         array_push($datas, $rombel);
            //     }
            // }

        } elseif ($user->hasRole('ops')) {
            $datas = Sekolah::where('id', $user->userable->sekolahs[0]->id)->with('rombels.siswas', 'rombels.guru')->first();
        } elseif ($user->hasRole('admin')) {
            $datas = Sekolah::all();
        } else {
            $guruId = $user->userable->id;
            $datas = Sekolah::whereHas('gurus', function ($q) use ($guruId) {
                $q->where('gurus.id', $guruId);
            })->with('ks')->with('rombels.siswas')->get();
        }

        return $datas;
    }
}
