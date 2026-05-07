<?php

namespace App\Http\Controllers;

use App\Models\Elemen;
use Illuminate\Http\Request;

class ElemenController extends Controller
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
            foreach($request->elemens as $elemen)
            {
                Elemen::create([
                    'mapel_id' => $elemen['mapel_id'],
                    'fase' => $elemen['fase'],
                    'nama' => $elemen['nama'],
                    'agama' => $elemen['agama'] ?? null
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
    public function show(Elemen $elemen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Elemen $elemen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Elemen $elemen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Elemen $elemen)
    {
        //
    }
}
