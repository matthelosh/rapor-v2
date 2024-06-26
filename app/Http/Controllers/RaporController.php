<?php

namespace App\Http\Controllers;

use App\Models\TanggalRapor;
use App\Models\Tapel;
use App\Services\RaporService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RaporController extends Controller
{
    public function home(Request $request)
    {

        return Inertia::render('Dash/Rapor', []);
    }

    public function periodik(Request $request)
    {

        return Inertia::render('Dash/Periodik', []);
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
        $absensis = $raporService->absensi($queries);
        $ekskuls = $raporService->ekskul($queries);
        $nilaiPas = $raporService->nilaiPAS($queries);
        $catatan = $raporService->catatan($queries);
        return response()->json([
            'absensi' => $absensis,
            'ekskuls' => $ekskuls,
            'pas' => $nilaiPas,
            'catatan' => $catatan,
            'tanggal' => TanggalRapor::where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                ->where('semester', $queries['semester'])
                ->where('tapel', $queries['tapel'])
                ->where('tipe', 'pas')
                ->first(),
        ]);
    }

    public function tanggal(Request $request)
    {
        try {
            return Inertia::render('Dash/TanggalRapor', [
                'tanggals' => TanggalRapor::where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)->with('tahun', 'sem')->get(),
                'tapels' => Tapel::all(),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeTanggal(Request $request)
    {
        try {
            $sekolahId = $request->query('sekolahId');
            $data = $request->data;
            TanggalRapor::updateOrCreate(
                [
                    'sekolah_id' => $sekolahId,
                    'tapel' => $data['tapel'],
                    'semester' => $data['semester'],
                    'tipe' => $data['tipe']
                ],
                [
                    'tanggal' => $data['tanggal']
                ]
            );
            return back()->with('message', 'Tanggal Rapor Disimpan');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroyTanggal($id)
    {
        try {
            $tanggal = TanggalRapor::findOrFail($id);
            $tanggal->delete();
            return back()->with('message', 'Tanggal Rapor dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
