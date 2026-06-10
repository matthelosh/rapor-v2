<?php

namespace App\Http\Controllers;

use App\Helpers\Periode;
use App\Models\TanggalRapor;
use App\Models\Tapel;
use App\Models\Siswa;
use App\Services\RaporService;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Rombel;
use App\Models\Sekolah;
use App\Helpers\RombelHelper;
use App\Helpers\SekolahHelper;
use App\Models\Kokurikuler;
use Carbon\Carbon;

class RaporController extends Controller
{
    public function home(Request $request)
    {
        return Inertia::render("Dash/Rapor", [
            "rombels" => RombelHelper::data($request->user()),
            "sekolahs" => \sekolahs($request->user()),
            "tapels" => Tapel::all(),
        ]);
    }

    public function labelNama(Request $request)
    {
        $nip = $request->user()->userable->nip;
        $rombels = Rombel::whereHas("wali_kelas", function ($query) use ($nip) {
            $query->where("nip", $nip);
        })
            ->where('tapel', Periode::tapel()->kode)
            ->with("siswas", fn($s) => $s->select("nama", "nis", "nisn"))
            ->get();
        return Inertia::render("Dash/Rapor/Label", [
            "rombels" => $rombels,
        ]);
    }

    public function periodik(Request $request)
    {
        // dd(RombelHelper::data($request->user()));
        return Inertia::render("Dash/Periodik", [
            "rombels" => RombelHelper::data($request->user()),
            "sekolahs" => \sekolahs($request->user()),
        ]);
    }

    public function raporPTS(Request $request, RaporService $raporService)
    {
        $queries = $request->query();
        $nilaiPTS = $raporService->nilaiPTS($queries);
        // dd($nilaiPTS);
        return response()->json($nilaiPTS);
    }

    public function raporPAS(Request $request, RaporService $raporService)
    {
        $queries = $request->query();
        /* dd($queries); */
        $absensis = $raporService->absensi($queries);
        $ekskuls = $raporService->ekskul($queries);
        $nilaiPas = $raporService->nilaiPAS($queries);
        $catatan = $raporService->catatan($queries);
        return response()->json([
            "absensi" => $absensis,
            "ekskuls" => $ekskuls,
            "pas" => $nilaiPas,
            "catatan" => $catatan,
            "sekolahs" => \sekolahs($request->user()),
            "tanggal" => TanggalRapor::where("semester", $queries["semester"])
                ->where("tapel", $queries["tapel"])
                ->where("tipe", "pas")
                ->first(),
        ]);
    }

    public function makePermanent(Request $request, RaporService $raporService)
    {
        try {
            $rombelId = $request->rombelId;
            $result = $raporService->simpanPermanen(
                $rombelId,
                $request->tapel,
                $request->semester
            );
            return back()->with("message", $result["message"]);
        } catch (\Throwable $th) {
            return back()->withErrors($th->getMessage());
        }
    }

    public function tanggal(Request $request)
    {
        try {
            $rombelId = null;
            $guru = $request->user()->userable;
            $dataRombels = Rombel::where('guru_id', $guru->id)
                ->where('tapel', Periode::tapel()->kode)
                ->select("id","kode","label","tingkat","fase")
                ->orderBy("rombels.tingkat", "ASC")
                ->get();
            $tangalQuery = TanggalRapor::query();
            if ($request->user()->hasRole('guru_kelas')) {
                $rombelIds=$dataRombels->map(fn($rombel) => $rombel->kode);
                $tangalQuery->whereIn('rombel_id', $rombelIds);
            }
            $tanggals = $tangalQuery->with("tahun", "sem")->get();
            return Inertia::render("Dash/TanggalRapor", [
                "tanggals" => $tanggals,
                "tapels" => Tapel::all(),
                "rombels" => $dataRombels
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getTanggalRapor($user, $semester=null, $tapel=null, $tipe=null, $rombelId) {
        try {
            $tangalQuery = TanggalRapor::query();
            // $tangalQuery = TanggalRapor::query();
            // if ($user->hasRole('guru_kelas')) {
            //     $guru = $user->userable;
            //     $rombel = Rombel::where("kode", $rombelId)->first();
            //     // $rombelId=$rombel->kode;
                
            //     if ($rombel->tingkat === '6') {
            //         $tangalQuery->where('rombel_id', $rombelId);
            //     } else {
            //         $tangalQuery->where('rombel_id', null);
            //     }
            // }
            $rombel = Rombel::whereKode($rombelId)->first();
            if ($rombel->tingkat == '6') {
                $tangalQuery->where('rombel_id', $rombelId);
            } 
            // dd($tangalQuery->get());
            if ($semester) {
                $tangalQuery->where('semester', $semester);
            }
            if ($tapel) {
                $tangalQuery->where('tapel', $tapel);
            }
            if($tipe) {
                $tangalQuery->where("tipe", $tipe);
            }
            $tanggal = $tangalQuery->first();
            // dd($user->hasRole('guru_kelas'));
            return $tanggal;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeTanggal(Request $request)
    {
        try {
            $sekolahId = $request->query("sekolahId");
            $data = $request->data;
            // $rombelId = $request->rombelId ?? null;
            // $guru = $request->user()->userable;
            // if ($request->user()->hasRole('guru_kelas')) {
            //     $rombel = Rombel::where('guru_id', $guru->id)->where('tapel', Periode::tapel()->kode)->first();
            //     $rombelId=$rombel->tingkat === '6' ? $rombel->kode : null;
            // }
            $tanggal = TanggalRapor::updateOrCreate(
                [
                    "rombel_id" => $data['rombel_id'] ?? null,
                    "tapel" => $data["tapel"],
                    "semester" => $data["semester"],
                    "tipe" => $data["tipe"],
                ],
                [
                    "tanggal" => $data["tanggal"],
                ]
            );
            // dd($tanggal);
            return back()->with("message", "Tanggal Rapor Disimpan");
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    // Cetak via blade
    public function cetakRapor(Request $request, $page, $siswaId, RaporService $raporService)
    {
        // dd($page);
        try {
            $siswa = Siswa::where("nisn", $siswaId)
                    ->with("sekolah.ks")
                    ->first();
            switch ($page) {
                default:
                    $view = "coverrapor";
                    break;
                case "biodata":
                    $view = "biodatarapor";
                    break;
                case "pas":
                    $view = "pas";
                    $query = $request->query();
                    $query["siswaId"] = $siswaId;
                    $query["rombelId"] = $request->rombelId;
                    $query['sekolahId'] = $siswa->sekolah_id;
                    $nilais = $raporService->nilaiPAS($query);
                    $absensis = $raporService->absensi($query);
                    $ekskuls = $raporService->ekskul($query);
                    $catatan = $raporService->catatan($query);
                    $kokurikuler = Kokurikuler::where('siswa_id', $siswaId)->where('tapel', Periode::tapel()->kode)->where('semester', Periode::semester()->kode)->first();
                    break;
            }

            $tanggalRapor = $this->getTanggalRapor($request->user(), $request->query("semester"), $request->query("tapel"), "pas", $request->rombelId);
            return view("cetak.rapor." . $page, [
                "page" => $page,
                "siswa" => $siswa,
                "nilais" => $nilais ?? [],
                "tapel" => Tapel::where("is_active", true)->first(),
                // "tanggal" => TanggalRapor::where("semester", $request->query("semester"))
                //     ->where("tapel", $request->query("tapel"))
                //     ->where("tipe", "pas")
                //     ->value('tanggal') ?? date('Y-m-d'),
                "tanggal" => $tanggalRapor ? $tanggalRapor->tanggal : Carbon::now("Asia/Jakarta")->format('d-M-Y'),
                // "sekolah" => Sekolah::where('npsn', $siswa->sekolah_id)->with('ks')->first(),
                "rombel" => Rombel::where('kode', $request->rombelId)->with('wali_kelas')->first(),
                // "tapel" => Tapel::where('kode', $request->query('tapel'))->first(),
                "semester" => $request->query('semester'),
                "absensi" => $absensis ?? [],
                "ekskuls" => $ekskuls ?? [],
                "catatan" => $catatan ?? [],
                "kokurikuler" => (isset($kokurikuler) && $kokurikuler && ($page == 'pas')) ? $kokurikuler->deskripsi : '',
            ]);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function destroyTanggal($id)
    {
        try {
            $tanggal = TanggalRapor::findOrFail($id);
            $tanggal->delete();
            return back()->with("message", "Tanggal Rapor dihapus");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
