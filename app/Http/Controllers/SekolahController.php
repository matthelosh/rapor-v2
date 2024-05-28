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
    public function index(Request $request) {
        $sekolahs = Sekolah::all();
        // dd($sekolahs);
        return Inertia::render('Dash/Sekolah', [
            'sekolahs' => $sekolahs,
        ]);
    }
    public function store(SekolahRequest $request, SekolahService $sekolahService) {
        $sekolahService->store($request);
        return back()->with('status', 'Data sekolah disimpan');
    }

    public function impor(Request $request, SekolahService $sekolahService) {
        $sekolahService->impor($request->datas);
        return back()->with('status', 'Data sekolah diimpor');
    }

    public function update(Request $request, SekolahService $sekolahService) {
        $sekolahService->store($request);
        return back()->with('status', 'Data sekolah diperbarui');
    }

    public function destroy(Request $request,  SekolahService $sekolahService, $id) {
        $sekolahService->destroy($id);
        return back()->with('status', 'Data sekolah dihapus');
    }
}
