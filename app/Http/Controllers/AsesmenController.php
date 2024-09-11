<?php

namespace App\Http\Controllers;

use App\Helpers\Periode;
use App\Http\Requests\AsesmenRequest;
use App\Models\Asesmen;
use App\Models\Jawaban;
use App\Models\ProsesAsesmen;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AsesmenController extends Controller
{
    public function home(Request $request)
    {
        try {
            $tapel = $this->tapel()->kode;
            if ($request->user()->hasRole('guru_kelas')) {
                $rombel = Rombel::where('tapel', $tapel)->first();
                $asesmens = Asesmen::where('rombel_id', $rombel->kode)
                    ->with('soals', 'rombel', 'guru', 'sekolah', 'mapel', 'semester', 'tapel')
                    ->get();
            } elseif ($request->user()->hasRole('ops')) {
                $asesmens = Asesmen::whereSekolahId($request->user()->userable->sekolahs[0]->npsn)
                    ->whereTapel($tapel)
                    ->with('soals', 'rombel', 'guru', 'sekolah', 'mapel', 'semester', 'tapel')
                    ->get();
            } else {
                $rombel = $request->query('rombel');
                // dd($rombel);
                $asesmens = Asesmen::whereGuruId($request->user()->userable->nip)->with('soals', 'rombel', 'guru', 'sekolah', 'mapel', 'semester', 'tapel')
                    ->get();
            }
            return Inertia::render(
                'Dash/Asesmen/Home',
                [
                    'asesmens' => $asesmens,
                    'canAddAsesmen' => $request->user()->can('add_asesmen'),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }



    public function store(AsesmenRequest $request)
    {
        try {
            Asesmen::updateOrCreate(
                [
                    // 'id' => $request->id ?? null,
                    'kode' => $request->kode ?? $request->rombel_id . $request->mapel_id . $request->semester . $request->jenis . Str::random(6),
                ],
                [
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi,
                    'tanggal' => $request->tanggal,
                    'mapel_id' => $request->mapel_id,
                    'mulai' => $request->mulai,
                    'selesai' => $request->selesai,
                    'jenis' => $request->jenis,
                    'rombel_id' => $request->rombel_id,
                    'sekolah_id' => $request->sekolah_id,
                    'semester' => $request->semester,
                    'tapel' => $request->tapel,
                    'guru_id' => $request->guru_id
                ]
            );
            return back()->with('message', 'Asesmen Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function attachSoal(Request $request, $id)
    {
        try {
            $asesmen = Asesmen::findOrFail($id);
            $asesmen->soals()->attach($request->soalId);

            return back()->with('message', 'Soal Ditambahkan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function detachSoal(Request $request, $id)
    {
        try {
            $asesmen = Asesmen::findOrFail($id);
            $asesmen->soals()->detach($request->soalId);

            return back()->with('message', 'Soal Dikeluarkan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy(Asesmen $asesmen, $id)
    {
        try {
            DB::table('asesmen_soal')->where('asesmen_id', $id)->delete();
            $asesmen::findOrFail($id)->delete();

            return back()->with('message', 'Asesmen dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Asesmen $asesmen, Request $request, $id)
    {
        $asesmen = $asesmen::findOrFail($id);
        $asesmen->update($request->all());
    }

    // Asesmen Siswa
    public function siswaAsesmen(Request $request)
    {
        try {
            $siswa_id = $request->query('siswaId');
            $asesmens = Asesmen::with('proses')->with([
                'jawabans' => function ($j) use ($siswa_id) {
                    $j->where('siswa_id', $siswa_id);
                }
            ])->get();
            return Inertia::render(
                'Dash/Asesmen/Siswa/Home',
                [
                    'asesmens' => $asesmens,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function kerjakanAsesmen(Request $request)
    {
        try {
            $siswa_id = $request->query('siswaId');
            $asesmen = Asesmen::whereKode($request->kode)
                ->with('soals', 'proses')
                ->with([
                    'jawabans' => function ($j) use ($siswa_id) {
                        $j->where('siswa_id', $siswa_id);
                    }
                ])
                ->first();
            // dd($asesmen);
            $tapel = Periode::tapel()->kode;
            return Inertia::render(
                'Dash/Asesmen/Siswa/LembarSoal',
                [
                    'asesmen' => $asesmen,
                    'siswa' => Siswa::whereNisn($request->siswaId)->with([
                        'rombels' => function ($r) use ($tapel) {
                            $r->whereTapel($tapel);
                        },
                        'sekolah'
                    ])->first(),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function mulaiKerjakan(Request $request, $kode)
    {
        try {
            $siswa_id = $request->query('siswaId');
            if ($request->query('prosesId')) {
                $currentProses = ProsesAsesmen::whereId($request->query('prosesId'))
                    ->with([
                        'jawabans' => function ($j) use ($siswa_id) {
                            $j->whereSiswaId($siswa_id);
                        }
                    ])->first();
            } else {
                $proses = ProsesAsesmen::create([
                    'asesmen_id' => $kode,
                    'siswa_id' => $siswa_id,
                    'mulai' => now(),
                    'status' => 'progres',
                ]);
                $currentProses = ProsesAsesmen::whereId($proses->id)
                    ->with([
                        'jawabans' => function ($j) use ($siswa_id) {
                            $j->whereSiswaId($siswa_id);
                        }
                    ])->first();
            }
            return response()->json([
                'proses' => $currentProses
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function saveTemp(Request $request)
    {
        try {
            $jawaban = Jawaban::updateOrCreate(
                [
                    'asesmen_id' => $request->asesmen_id,
                    'siswa_id' => $request->siswa_id,
                    'soal_id' => $request->soal_id,
                    'is_benar' =>  false,
                    'proses_id' => $request->proses_id,
                ],
                [
                    'teks' =>  $request->teks,
                ]
            );
            return back()->with('message', 'Jawaban disimpan sementara');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
}
