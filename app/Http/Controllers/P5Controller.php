<?php

namespace App\Http\Controllers;

use App\Models\Elemenp5;
use App\Models\NilaiP5;
use App\Models\p5;
use App\Models\Proyek;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Tapel;
use App\P5trait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class P5Controller extends Controller
{
    use P5trait;

    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Dash/P5',
                [
                    "p5s" => p5::with('elemens.apds')->get(),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function proyek(Request $request)
    {
        try {
            if ($request->user()->hasRole('ops')) {
                $proyeks = Proyek::whereTapel($this->tapel()->kode)
                    ->where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                    ->with('rombel.sekolah')
                    ->with('apds.elemen.dimensi')
                    // ->with('dimensis')
                    ->get();
            } else {
                $rombel = Rombel::where('guru_id', $request->user()->userable->id)
                    ->where('tapel', $this->tapel()->kode)
                    ->first();
                $proyeks = Proyek::whereTapel($this->tapel()->kode)
                    ->where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                    ->whereRombelId($rombel->kode)
                    ->with('rombel.sekolah', 'tapel', 'semester')
                    ->with('apds.elemen.dimensi')
                    // ->with('dimensis')
                    ->get();
            }
            return Inertia::render(
                'Dash/P5/Proyek',
                [
                    'proyeks' => $proyeks,
                    'dimensis' => p5::with('elemens.apds')->get()
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeProyek(Request $request)
    {
        try {
            $proyek = Proyek::updateOrCreate(
                [
                    'id' => $request->id ?? null,
                ],
                [
                    'tapel' => $this->tapel()->kode,
                    'semester' => $this->semester()->kode,
                    'sekolah_id' => $request->query('sekolah'),
                    'rombel_id' => $request->rombel_id,
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi,
                    'status' => 'rencana'
                ]
            );

            $sync_apd = $proyek->apds()->sync($request->apd_ids);

            return back()->with('message', 'Proyek Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function indexNilaiP5(Request $request)
    {
        try {
            $nilais = $this->getNilai($request->rombel, $request->proyek_id);
            return \response()->json([
                'nilais' => $nilais
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nilai(Request $request)
    {
        try {
            $rombel = Rombel::where('guru_id', $request->user()->userable->id)
                ->where('tapel', $this->tapel()->kode)
                ->with('siswas')
                ->first();
            $proyeks = Proyek::where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                ->where('tapel', $this->tapel()->kode)
                ->where('semester', $this->semester()->kode)
                ->whereNot('status', 'selesai')
                ->get();
            return Inertia::render(
                'Dash/P5Nilai',
                [
                    "p5s" => p5::with('elemens.apds')->get(),
                    'proyeks' => $proyeks,
                    'rombel' => $rombel
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeNilai(Request $request)
    {
        try {
            $datas = $request->datas;
            $store = $this->store($datas);

            return \back()->with('message', $store);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
