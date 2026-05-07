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
            $rombel = $request->query('rombel') ?? null;
            $sekolah = Sekolah::whereNpsn($npsn)->with([
                'ks',
                'siswas' => function($s) use($rombel) {
                    $s->whereStatus('aktif')
                    ->when($rombel, function($q) use($rombel) {
                        $q->whereHas('rombels', function($r) use($rombel) {
                            $r->whereKode($rombel);
                        });
                    })
                    ->with([
                        'rombels' => function($r) {
                            $r->whereTapel(Periode::tapel()->kode);
                        }
                    ]);
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
