<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nilai;
use App\NilaiTrait;
use App\Helpers\Periode;
use App\Services\NilaiService;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    use NilaiTrait;

    public function home(Request $request, NilaiService $nilaiService)
    {
        $semester = $request->semester ?? Periode::semester()->kode;
        $tapel = $request->tapel ?? Periode::tapel()->kode;
        return Inertia::render('Dash/Nilai', [
            'datas' => $nilaiService->home($semester, $tapel),
            'nilais' => $this->prosentase($request->user())
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nilais = $this->indexNilai($request);
        return response()->json($nilais);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $store = $this->simpanNilai($request);

            return back()->with('message', $store);
        } catch (\Throwable $th) {
            throw ($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Nilai $nilai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nilai $nilai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nilai $nilai)
    {
        //
    }
}
