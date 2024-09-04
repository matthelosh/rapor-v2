<?php

namespace App\Http\Controllers;

use App\Http\Requests\AsesmenRequest;
use App\Models\Asesmen;
use App\Models\Rombel;
use App\Models\Tapel;
use Illuminate\Http\Request;
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
                    ->with('soals', 'rombel', 'guru', 'sekolah', 'mapel')
                    ->get();
            } elseif ($request->user()->hasRole('ops')) {
                $asesmens = Asesmen::whereSekolahId($request->user()->userable->sekolahs[0]->npsn)
                    ->whereTapel($tapel)
                    ->with('soals', 'rombel', 'guru', 'sekolah', 'mapel')
                    ->get();
            } else {
                $rombel = $request->query('rombel');
                $asesmens = Asesmen::where('rombel_id', $rombel)
                    ->with('soals', 'rombel', 'guru', 'sekolah', 'mapel')
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
                    'id' => $request->id ?? null
                ],
                [
                    'kode' => $request->kode,
                    'nama' => $request->nama,
                    'deskripsi' => $request->deskripsi,
                    'tanggal' => $request->tanggal,
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

    private function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
}
