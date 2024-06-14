<?php

namespace App\Http\Controllers;

use App\Models\Tp;
use Illuminate\Http\Request;

class TpController extends Controller
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
    public function impor(Request $request)
    {
        try {
            foreach($request->tps as $tp)
            {
                Tp::create([
                    'mapel_id' => $tp['mapel_id'],
                    'kode' => $tp['kode'],
                    'teks' => $tp['teks'],
                    'elemen' => $tp['elemen'],
                    'fase' => $tp['fase'],
                    'tingkat' => $tp['tingkat'],
                    'semester' => $tp['semester'],
                    'agama' => $tp['agama'] ?? null,
                ]);
            }
            return back()->with("message", "Elemen diimpor");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Tp $tp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tp $tp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tp $tp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tp $tp, $id)
    {
        try {
           $tp->destroy($id);

           return back()->with('message', 'Tp Dihapus');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
