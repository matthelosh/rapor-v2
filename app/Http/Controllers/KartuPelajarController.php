<?php

namespace App\Http\Controllers;

use App\Helpers\Periode;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class KartuPelajarController extends Controller
{
    public function cetak(Request $request, $npsn)
    {
        try {
            $sekolah = Sekolah::whereNpsn($npsn)->with([
                'ks',
                'siswas' => function($s) {
                    $s->whereStatus('aktif')
                    ->with([
                        'rombels' => function($r) {
                            $r->whereTapel(Periode::tapel()->kode);
                        }
                    ])->limit(3);
                },
            ])->first();

            return view(
                'cetak.kartupelajar',
                [
                    'sekolah' => $sekolah,

                ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
