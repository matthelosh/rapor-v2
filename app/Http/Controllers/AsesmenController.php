<?php

namespace App\Http\Controllers;

use App\Events\SiswaMenjawab;
use App\Events\SiswaMulaiAsesmen;
use App\Helpers\Periode;
use App\Http\Requests\AsesmenRequest;
use App\Models\Asesmen;
use App\Models\Jawaban;
use App\Models\ProsesAsesmen;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                // dd($request->user()->userable->nip);
                $rombel = Rombel::where('guru_id', $request->user()->userable->id)->first();
                // dd($rombel);
                $asesmens = Asesmen::where('tingkat', 'kecamatan')
                    ->orWhere([
                        ['sekolah_id', '=', $request->user()->userable->sekolahs[0]->npsn],
                        ['rombel_id', '=', $rombel->kode]
                    ])
                    ->with('soals', 'rombel', 'guru', 'sekolah', 'mapel', 'semester', 'tapel')
                    ->get();
            } elseif ($request->user()->hasRole('ops')) {
                $asesmens = Asesmen::whereTapel($tapel)
                    ->whereIn('tingkat', ['gugus', 'kecamatan'])
                    ->with('soals', 'rombel', 'guru', 'mapel', 'semester', 'tapel')
                    ->get();
                // $canEdit = 
            } elseif ($request->user()->hasRole('admin')) {
                $asesmens = Asesmen::whereGuruId($request->user()->id)->with('soals', 'mapel', 'semester')
                    ->with([
                        'proses' => function ($p) {
                            $p->with('siswa.user');
                            $p->with('jawabans');
                        }
                    ])
                    ->with('pesertas')
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

    public function reloadASesmen(Request $request)
    {
        try {
            $asesmen = Asesmen::whereId($request->query('asesmenId'))
                ->with([
                    'proses' => function ($p) {
                        $p->with('jawabans', 'siswa.user');
                    }
                ])
                ->first();

            return response()->json([
                'asesmen' => $asesmen,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
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
                    'agama' => $request->agama ?? null,
                    'kelas' => $request->kelas,
                    'tingkat' => $request->tingkat,
                    'rombel_id' => $request->rombel_id,
                    'sekolah_id' => $request->sekolah_id,
                    'semester' => $request->semester,
                    'tapel' => $request->tapel,
                    'guru_id' => $request->guru_id ?? $request->user()->id
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
            $siswa_id = $request->user()->userable->nisn;
            $asesmens = Asesmen::whereSekolahId($request->user()->userable->sekolah_id)
                ->orWhere('tingkat', 'kecamatan')
                ->with([
                    'proses_siswa' => function ($p) use ($siswa_id) {
                        $p->where('siswa_id', $siswa_id);
                        $p->with('jawabans', function ($j) use ($siswa_id) {
                            $j->where('siswa_id', $siswa_id);
                        });
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
            $proses_id = $request->query('prosesId');
            $asesmen = Asesmen::whereKode($request->kode)
                ->with('soals')
                ->with([
                    'proses' => function ($j) use ($proses_id) {
                        $j->where('id', $proses_id);
                        $j->with('jawabans');
                    }
                ])
                ->first();
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
            $asesmen = Asesmen::whereKode($kode)->first();
            $admin = User::whereName('admin')->first();
            // dd($ev);
            SiswaMulaiAsesmen::dispatch($admin, "Peserta mulai mengerjakan asesmen: " . $asesmen->nama);
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
            $proses = ProsesAsesmen::findOrFail($request->proses_id);
            $proses->update(['updated_at' => now(), 'selesai' => now()]);
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

            $admin = User::whereName('admin')->first();
            $siswa = Siswa::whereNisn($jawaban->siswa_id)->first();

            SiswaMenjawab::dispatch($admin, $siswa->nama . " siswa menjawab soal");
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
