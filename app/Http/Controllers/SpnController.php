<?php

namespace App\Http\Controllers;

use App\Models\Jilid;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SpnController extends Controller
{
    public function home(Request $request)
    {
        try {
            $jilids = Jilid::whereSekolahId($request->user()->userable->sekolahs[0]->npsn)->get();
            return Inertia::render(
                'Dash/Spn/Home',
                [
                    'jilids' => $jilids,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSiswa(Request $request)
    {
        $rombelId = $request->query('rombelId');
        $rombel = Rombel::whereKode($rombelId)->with('siswas.jilids')->first();
        return response()->json([
            'siswas' => $rombel->siswas,
        ]);
    }

    public function storePretes(Request $request)
    {
        try {
            $siswas = $request->siswas;
            // dd($siswas);
            foreach ($siswas as $sis) {
                $siswa = Siswa::whereNisn($sis['nisn'])->first();
                $siswa->jilids()->attach($sis['jilid']);
                // DB::table('jilid_siswa')->insert([
                //     'siswa_id' => $siswa->id,
                //     'jilid_id' => 
                // ])
            }
            return response()->json([
                'message' => 'Siswa dimasukkan Kelas SPN'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
