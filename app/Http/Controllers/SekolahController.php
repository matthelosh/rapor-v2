<?php

namespace App\Http\Controllers;

use App\Http\Requests\SekolahRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Resources\SekolahResource;
use App\Models\Sekolah;
use App\Services\SekolahService;

class SekolahController extends Controller
{
    public function home(Request $request, SekolahService $sekolahService)
    {
        $sekolahs = $sekolahService->index($request);
        // dd($sekolahs);
        return Inertia::render('Dash/Sekolah', [
            'sekolahs' => $sekolahs,
        ]);
    }

    public function index(Request $request, SekolahService $sekolahService)
    {
        return response()->json(['sekolahs' => $sekolahService->index($request)]);
    }

    public function store(SekolahRequest $request, SekolahService $sekolahService)
    {
        $sekolahService->store($request);
        return back()->with('status', 'Data sekolah disimpan');
    }

    public function impor(Request $request, SekolahService $sekolahService)
    {
        try {
            $impor = $sekolahService->impor($request->datas);
            return back()->with('mesage', 'Kenapa?');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Request $request, SekolahService $sekolahService)
    {
        $sekolahService->store($request);
        return back()->with('status', 'Data sekolah diperbarui');
    }

    public function addOps(Request $request, SekolahService $sekolahService, $id)
    {
        // dd($id);
        try {
            $sekolahService->addOps($id);
            return back()->with('status', 'Data OPS ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request,  SekolahService $sekolahService, $id)
    {
        $sekolahService->destroy($id);
        return back()->with('status', 'Data sekolah dihapus');
    }
}
