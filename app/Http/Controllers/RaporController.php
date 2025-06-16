<?php

namespace App\Http\Controllers;

use App\Models\TanggalRapor;
use App\Models\Tapel;
use App\Models\Siswa;
use App\Services\RaporService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Helpers\RombelHelper;
use App\Helpers\SekolahHelper;

class RaporController extends Controller
{
    public function home(Request $request)
    {
        return Inertia::render("Dash/Rapor", [
            "rombels" => RombelHelper::data($request->user()),
            "sekolahs" => SekolahHelper::data($request->user()),
            "tapels" => Tapel::all(),
        ]);
    }

    public function periodik(Request $request)
    {
        return Inertia::render("Dash/Periodik", [
            "rombels" => RombelHelper::data($request->user()),
            "sekolahs" => SekolahHelper::data($request->user()),
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
            return Inertia::render("Dash/TanggalRapor", [
                "tanggals" => TanggalRapor::with("tahun", "sem")->get(),
                "tapels" => Tapel::all(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeTanggal(Request $request)
    {
        try {
            $sekolahId = $request->query("sekolahId");
            $data = $request->data;
            TanggalRapor::updateOrCreate(
                [
                    "sekolah_id" => null,
                    "tapel" => $data["tapel"],
                    "semester" => $data["semester"],
                    "tipe" => $data["tipe"],
                ],
                [
                    "tanggal" => $data["tanggal"],
                ]
            );
            return back()->with("message", "Tanggal Rapor Disimpan");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Cetak via blade
    public function cetakRapor(Request $request, $page, $siswaId)
    {
        try {
            switch ($page) {
                default:
                    $view = "coverrapor";
                    break;
                case "biodata":
                    $view = "biodatarapor";
                    break;
            }
            return view("cetak." . $view, [
                "page" => $page,
                "siswa" => Siswa::where("nisn", $siswaId)
                    ->with("sekolah")
                    ->first(),
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
