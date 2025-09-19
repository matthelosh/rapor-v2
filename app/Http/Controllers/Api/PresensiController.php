<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Periode;
use App\Helpers\RombelHelper;
use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|string',
            'tapel' => 'required|string',
            'semester' => 'required|string',
            'rombel_id' => 'required|string',
            'status' => 'required|in:h,s,i,a',
        ]);
        $user = $request->user();
        $guru =$user->userable;
        if (!$user->hasRole('guru_kelas')) {
            $role = $user->getRoleNames()->first();
            $mapel_id = ($role === 'guru_agama' ? 'pabp' : ($role === 'guru_pjok' ? 'pjok' : 'inggris'));
        }
        $presensi = Presensi::updateOrCreate(
            [
                'id' => $request->id ?? null,
                'siswa_id' => $request->siswa_id,
                'tapel' => $request->tapel,
                'semester' => $request->semester,
                'rombel_id' => $request->rombel_id,
            ],
            [
                'guru_id' => $guru->nip,
                'status' => $request->status,
                'mapel_id' => $mapel_id ?? null,
                'jurnal_id' => $request->jurnal_id ?? null,
            ]
        );

        return response()->json([
            'message' => 'Presensi berhasil disimpan',
            'presensi' => $presensi,
        ], 201);
    }

    public function index(Request $request)
    {
        if ($request->user()->hasRole('guru_kelas')) {
            $request->validate([
                'rombel_id' => 'required|string',
                'tapel' => 'required|string',
                'semester' => 'required|string',
            ]);

            $rekap = $this->getRekapGuruKelas($request);

        } else {
            $rekap = $this->getRekapGuruMapel($request);
        }
        return response()->json([
            'rekap' => $rekap,
        ]);
    }

    public function harian(Request $request)
    {
        try {
            $request->validate([
                'rombel_id' => 'required|string',
                'tanggal' => 'required|string',
            ]);

            $tanggal = $request->tanggal;
            $guru = $request->user()->userable;
            $rombel_id = $request->rombel_id;

            $presensis = Presensi::where('rombel_id', $rombel_id)
                        ->whereDate('created_at', $tanggal)
                        ->where('guru_id', $guru->nip)
                        ->with(['siswa:id,nisn,nama,jk', 'rombel:id,kode,label','guru:id,nip,nama,gelar_belakang'])
                        ->get();

            return response()->json([
                'presensis' => $presensis,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function getRombels()
    {
        $guru = Auth::user()->userable;
        $tapel = \App\Helpers\Periode::tapel()->kode;

        $rombels = $guru->rombels()
            ->where('tapel', $tapel)
            ->with(['siswas:id,nisn,nama,jk'])
            ->get();

        return response()->json([
            'rombels' => $rombels,
        ]);
    }

    private function getRekapGuruKelas($request)
    {
        $isGuruMapel = !$request->user()->hasRole('guru_kelas');

        $rekap = Presensi::selectRaw("
                    DATE(created_at) as tanggal,
                    COUNT(CASE WHEN status = 'h' THEN 1 END) as hadir,
                    COUNT(CASE WHEN status = 'i' THEN 1 END) as ijin,
                    COUNT(CASE WHEN status = 's' THEN 1 END) as sakit,
                    COUNT(CASE WHEN status = 'a' THEN 1 END) as alpa
                ")
                ->where('rombel_id', $request->rombel_id)
                ->where('tapel', $request->tapel)
                ->where('semester', $request->semester)
                ->where('guru_id', $request->user()->userable->nip)
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'desc')
                ->get();
        return $rekap;
    }

    private function getRekapGuruMapel($request)
    {
        $user = $request->user();
        $rombels = \App\Models\Rombel::where('sekolah_id', $user->userable->sekolahs[0]->npsn)
                ->where('tapel', Periode::tapel()->kode)
                ->get();
        $rekaps = [];
        foreach ($rombels as $rombel) {
            $request['rombel_id'] = $rombel->kode;
            $rekap = $this->getRekapGuruKelas($request);
            $rekaps[] = [
                'rombel' => $rombel,
                'rekap' => $rekap
            ];
        }
        return $rekaps;
    }

}
