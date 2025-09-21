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

    public function rekapPresensiSiswa(Request $request)
    {
        try {
            $request->validate([
                'rombel_id' => 'required|string',
                'tapel' => 'required|string',
                'semester' => 'required|string',
                'bulan' => 'nullable|integer|min:1|max:12',
                'tahun' => 'nullable|integer|min:2020|max:2030',
            ]);

            $user = $request->user();
            $guru = $user->userable;
            $rombel_id = $request->rombel_id;
            $tapel = $request->tapel;
            $semester = $request->semester;
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $userRole = $user->getRoleNames()->first();

            // Get students from the rombel
            $rombel = \App\Models\Rombel::with(['siswas:id,nisn,nama'])
                    ->where('kode', $rombel_id)
                    ->first();

            if (!$rombel) {
                return response()->json([
                    'error' => 'Rombel tidak ditemukan',
                    'debug' => ['rombel_id_searched' => $rombel_id]
                ], 404);
            }

            $siswas = $rombel->siswas;
            $result = [];

            foreach ($siswas as $siswa) {
                // Start with basic query
                $query = Presensi::where('siswa_id', $siswa->nisn)
                    ->where('rombel_id', $rombel_id);

                // Apply tapel and semester filtering
                if ($tapel) {
                    // Handle tapel conversion: "2025/2026" -> "2526"
                    $convertedTapel = $tapel;
                    if ($tapel === '2025/2026') {
                        $convertedTapel = '2526';
                    }
                    // Add more conversions if needed
                    // else if ($tapel === '2024/2025') {
                    //     $convertedTapel = '2425';
                    // }

                    $query->where(function($q) use ($tapel, $convertedTapel) {
                        $q->where('tapel', $tapel)
                          ->orWhere('tapel', $convertedTapel);
                    });
                }
                if ($semester) {
                    $query->where('semester', $semester);
                }

                // Filter by role
                if ($user->hasRole('guru_kelas')) {
                    $query->where('guru_id', $guru->nip);
                } else {
                    $role = $user->getRoleNames()->first();
                    $mapel_id = ($role === 'guru_agama' ? 'pabp' : ($role === 'guru_pjok' ? 'pjok' : 'inggris'));
                    $query->where('mapel_id', $mapel_id);
                }

                // Filter by bulan if provided
                if ($bulan) {
                    $query->whereMonth('created_at', $bulan);
                }

                // Filter by tahun if provided
                if ($tahun) {
                    $query->whereYear('created_at', $tahun);
                }

                $presensis = $query->get();

                $hadir = $presensis->where('status', 'h')->count();
                $sakit = $presensis->where('status', 's')->count();
                $izin = $presensis->where('status', 'i')->count();
                $alpa = $presensis->where('status', 'a')->count();

                $result[] = [
                    'nisn' => $siswa->nisn,
                    'nama' => $siswa->nama,
                    'rombel' => $rombel->label,
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin' => $izin,
                    'alpa' => $alpa,
                ];
            }

            return response()->json([
                'data' => $result,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString()
            ], 500);
        }
    }

    public function rekapBulan(Request $request)
    {
        try {
            $request->validate([
                'rombel_id' => 'required|string',
                'tapel' => 'required|string',
                'semester' => 'required|string',
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2020|max:2030',
            ]);

            $user = $request->user();
            $guru = $user->userable;
            $rombel_id = $request->rombel_id;
            $tapel = $request->tapel;
            $semester = $request->semester;
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            // Get students from the rombel
            $rombel = \App\Models\Rombel::with(['siswas:id,nisn,nama'])
                    ->where('kode', $rombel_id)
                    ->first();

            if (!$rombel) {
                return response()->json([
                    'error' => 'Rombel tidak ditemukan',
                ], 404);
            }

            $siswas = $rombel->siswas;
            $result = [];

            foreach ($siswas as $siswa) {
                $query = Presensi::where('siswa_id', $siswa->nisn)
                    ->where('rombel_id', $rombel_id)
                    ->where('semester', $semester)
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);

                // Handle tapel conversion: "2025/2026" -> "2526"
                if ($tapel) {
                    $convertedTapel = $tapel;
                    if ($tapel === '2025/2026') {
                        $convertedTapel = '2526';
                    }

                    $query->where(function($q) use ($tapel, $convertedTapel) {
                        $q->where('tapel', $tapel)
                          ->orWhere('tapel', $convertedTapel);
                    });
                }

                // Filter by role
                if ($user->hasRole('guru_kelas')) {
                    $query->where('guru_id', $guru->nip);
                } else {
                    $role = $user->getRoleNames()->first();
                    $mapel_id = ($role === 'guru_agama' ? 'pabp' : ($role === 'guru_pjok' ? 'pjok' : 'inggris'));
                    $query->where('mapel_id', $mapel_id);
                }

                $presensis = $query->get();

                // Create daily status array
                $dailyStatus = [];
                $jml_h = 0;
                $jml_s = 0;
                $jml_i = 0;
                $jml_a = 0;

                foreach ($presensis as $presensi) {
                    $tanggal = date('d', strtotime($presensi->created_at));
                    $dailyStatus[$tanggal] = $presensi->status;

                    switch ($presensi->status) {
                        case 'h':
                            $jml_h++;
                            break;
                        case 's':
                            $jml_s++;
                            break;
                        case 'i':
                            $jml_i++;
                            break;
                        case 'a':
                            $jml_a++;
                            break;
                    }
                }

                $result[] = [
                    'nisn' => $siswa->nisn,
                    'nama' => $siswa->nama,
                    'rombel' => $rombel->label,
                    'daily_status' => $dailyStatus,
                    'jml_h' => $jml_h,
                    'jml_s' => $jml_s,
                    'jml_i' => $jml_i,
                    'jml_a' => $jml_a,
                ];
            }

            return response()->json([
                'data' => $result,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }

}
