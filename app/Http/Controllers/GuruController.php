<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\GuruService;
use App\Http\Requests\GuruRequest;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GuruService $guruService)
    {
        $gurus = $guruService->index($request);
        return Inertia::render("Dash/Guru", [
            "sekolahs" => \sekolahs($request->user()),
            "gurus" => $gurus,
        ]);
    }

    public function show(Request $request, GuruService $guruService)
    {
        $gurus = $guruService->show($request);
        return response()->json(["gurus" => $gurus]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addAccount(Request $request, GuruService $guruService)
    {
        try {
            $account = $guruService->addAccount($request->id);
            return back()->with("message", $account);
        } catch (\Throwable $th) {
            return back()->withErrors(["errors" => $th->getMessage()]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GuruRequest $request, GuruService $guruService)
    {
        try {
            $store = $guruService->store(
                $request->all(),
                $request->file("file") ?? null,
                $request->file("file_ttd") ?? null
            );

            return back()->with("message", $store);
        } catch (\ValidationException $e) {
            return back()
                ->withErros($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    public function impor(Request $request, GuruService $guruService)
    {
        try {
            $guruService->impor($request);
            return back()->with("message", "Data Guru diimpor");
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GuruService $guruService)
    {
        try {
            $store = $guruService->update(
                $request->all(),
                $request->file("file") ?? null,
                $request->file("file_ttd")
            );
            return back()->with("message", $store);
        } catch (\ValidationException $e) {
            dd($e);
            // return back()
            //     ->withErros($e->validator->errors())
            //     ->withInput();
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GuruService $guruService, $id)
    {
        try {
            $guruService->destroy($id);
            return back()->with("message", "Guru dihapus.");
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }
}
