<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiswaRequest;
use App\Models\Siswa;
use Illuminate\Http\Request;
use App\Services\SiswaService;
use Inertia\Inertia;

class SiswaController extends Controller
{
    public function home(Request $request, SiswaService $siswaService)
    {
        $siswas = $siswaService->home($request);

        return Inertia::render('Dash/Siswa', [
            'siswas' => $siswas,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function impor(Request $request, SiswaService $siswaService)
    {
        try {
            $siswaService->impor($request);
            return back()->with('status', 'Data Siswa diimpor');
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiswaRequest $request, SiswaService $siswaService)
    {
        try {

            $store = $siswaService->store($request->all(), $request->file('file') ?? null);

            return back()->with('data', $store);
        } catch (\Exception $e) {
            return back()->withErrors("errors", $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function nonMember(Request $request)
    {
        try {
            $siswas = Siswa::whereDoesntHave('rombels')
                ->where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                ->with('sekolah')->whereStatus('aktif')->get();
            return response()->json(['siswas' => $siswas]);
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiswaService $siswaService)
    {
        $update = $siswaService->store($request->all(), $request->file('file') ?? null);
        // dd($store);
        return back()->with('message', 'Data Siswa diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa, $id)
    {
        try {
            $siswa = $siswa->findOrFail($id);
            $detachRombel = $siswa->rombels()->detach();
            $siswa->delete();

            return back()->with("success", true);
        } catch (\Exception $e) {
            return back()->withErrors(["errors", $e->getMessage()]);
        }
    }
}
