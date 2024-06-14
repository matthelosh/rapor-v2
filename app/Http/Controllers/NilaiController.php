<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Nilai;
use App\Services\NilaiService;
use Illuminate\Http\Request;

class NilaiController extends Controller 
{
    

    public function home(Request $request, NilaiService $nilaiService) {
        
        return Inertia::render('Dash/Nilai', [
            'datas' => $nilaiService->home(),
        ]);
    }
    
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
        //
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
