<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            $store = Mapel::updateOrCreate(
                [
                    'id' => $request->input('id') ?? null,
                ],
                [
                    'kode' => $request->input('kode'),
                    'label' => $request->input('label'),
                    'fase' => $request->input('fase'),
                    'kategori' => $request->input('kategori'),
                    'deskripsi' => $request->input('deskripsi'),
                ]
                );
            return back()->with('message', 'Mapel disimpan');
        } catch(\Exception $e)
        {
            dd($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Mapel $mapel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mapel $mapel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mapel $mapel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mapel $mapel, $id)
    {
        try {
            $mapel->findOrFail($id)->tps()->delete();
            $mapel->destroy($id);
            return back()->with('message', 'Mapel berhasil dihapus');
        } catch(\Exception $e)
        {
            dd($e->getMessage());
        }
    }
}
