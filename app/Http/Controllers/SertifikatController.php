<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


class SertifikatController extends Controller
{
    public function home(Request $request)
    {
        try {
            $data = null;
            if ($request->query('nama') && $request->query('sekolah_id')) {
                $sekolahId = $request->query('sekolah_id');
                $nama = $request->query('nama');
                $sekolah = Sekolah::whereNpsn($sekolahId)->with('gurus', function ($g) use ($nama) {
                    $g->where('nama', 'LIKE', "%{$nama}%");
                    $g->first();
                })->first();
                // $gurus = $sekolah->gurus->filter(fn($guru) => \str_contains(\strtolower($guru->nama), \strtolower($request->query('nama'))));
                // $guru = Guru::where(['nama', 'LIKE', "%{$request->query('nama')}%"])
                //     ->whereHas('sekolahs', function ($s) use ($sekolahId) {
                //         $s->whereNpsn($sekolahId);
                //         $s->first();
                //     })->with('sekolahs')->first();
                // dd($sekolah->gurus[0]);

                $sertifikats = Sertifikat::where('guru_id', $sekolah->gurus[0]->nip)
                    ->with('workshop', 'penerima.sekolahs')
                    ->get();
                $data = $sertifikats;
            }
            return Inertia::render('Front/Sertifikat', [
                'sekolahs' => Sekolah::all(),
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function verify(Request $request)
    {
        try {
            // return 'halo';
            return Inertia::render(
                'Front/VerifySertifikat',
                [
                    'sertifikat' => ($request->query('nomor')) ? Sertifikat::whereNomor($request->query('nomor'))->with('penerima', 'workshop')->first() : null,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // public function cetak(Request $request)
    // {
    //     try {
    //         $id = $request->id;
    //         $sertifikat = Sertifikat::whereId($id)->with('penerima', 'workshop')->first();
    //         // $pdf = Pdf::loadView("sertifikat", ['data' => $sertifikat])->setPaper('a4', 'landscape');
    //         // // $pdf->setOption([])

    //         // return $pdf->stream();
    //         return Inertia::render('Front/')
    //     } catch (\Throwable $th) {
    //         throw $th;
    //     }
    // }

}
