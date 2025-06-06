<?php

namespace App\Http\Controllers;

use App\Models\Jilid;
use App\Models\JurnalSpn;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SpnController extends Controller
{
    public function home(Request $request)
    {
        // dd($request->user()->userable->sekolahs[0]);
        try {
            $guruId = $request->user()->userable->nip;
            $jilids = Jilid::with([
                'jurnals' => function ($j) use ($guruId) {
                    $j->where('guru_id', $guruId);
                }
            ])
                ->with('siswas.rombels')
                ->get();
            // dd($jilids);
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
        $rombel = Rombel::whereKode($rombelId)->with(
            'siswas',
            function ($s) {
                $s->orderBy('nama', 'ASC');
                $s->with('jilids');
            }
        )->first();
        return response()->json([
            'siswas' => $rombel->siswas,
        ]);
    }

    public function attachSiswa(Request $request, $siswaId)
    {
        try {
            $siswa = Siswa::whereNisn($siswaId)->first();
            $jilid = Jilid::whereId($request->data['jilid'])->first();
            $siswa->jilids()->attach($request->data['jilid']);

            return response()->json([
                'message' => 'Siswa dimasukkan Kelas ' . $jilid->nama,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storePretes(Request $request)
    {
        try {
            $siswas = $request->siswas;
            // dd($siswas);
            foreach ($siswas as $sis) {
                $siswa = Siswa::whereDoesntHave('jilids')->whereNisn($sis['nisn'])->first();
                if ($siswa) {

                    $siswa->jilids()->attach($sis['jilid']);
                }
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

    // Jurnal SPN

    public function storeJurnal(Request $request)
    {
        try {
            $data = \json_decode($request->data);

            $fotos = $request->file('fotos');
            // dd($data);
            $urls = [];
            if ($fotos) {
                foreach ($fotos as $foto) {
                    $store = $foto->storeAs('public/images/jurnal-spn', $foto->getClientOriginalName());
                    array_push($urls, Storage::url($store));
                }
            }
            JurnalSpn::updateOrCreate(
                [
                    'id' => $data->id ?? null,
                ],
                [
                    'sekolah_id' => $request->user()->userable->sekolahs[0]->npsn,
                    'jilid_id' => $data->jilid_id,
                    'guru_id' => $request->user()->userable->nip,
                    'materi' => $data->materi,
                    'fotos' => count($urls) > 0 ?  \implode(",", $urls) : null,
                    'absensis' => $data->absensi ? \implode(",", $data->absensi) : \null,
                    'keterangan' => $data->keterangan ?? null,
                ]
            );

            return response()->json(['message' => 'Jurnal disimpan']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
