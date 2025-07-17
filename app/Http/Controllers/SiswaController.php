<?php

namespace App\Http\Controllers;

use App\Helpers\DapodikHelper;
use App\Http\Requests\SiswaRequest;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\User;
use App\Services\SiswaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class SiswaController extends Controller
{
    public function home(Request $request, SiswaService $siswaService)
    {
        $siswas = $siswaService->home($request);
        // $siswas = Siswa::paginate(20);

        return Inertia::render("Dash/Siswa", [
            "siswas" => $siswas,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function luluskan(Request $request, SiswaService $siswaService)
    {
        try {
            $lulus = $siswaService->luluskan($request->siswas);
            return back()->with('message', 'Siswa diluluskan');
        } catch(\Exception $e)
        {
            return back()->withErrors($e->getMessage());
        }
    }

    public function impor(Request $request, SiswaService $siswaService)
    {
        try {
            $siswaService->impor($request);
            return back()->with("status", "Data Siswa diimpor");
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    public function imporDapodik(Request $request)
    {
        try {
            $siswas = DapodikHelper::siswas($request->all());
            return response()->json([
                "siswas" => $siswas,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiswaRequest $request, SiswaService $siswaService)
    {
        try {
            $store = $siswaService->store(
                $request->all(),
                $request->file("file") ?? null
            );

            return back()->with("data", $store);
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function nonMember(Request $request)
    {
        $rombelId = $request->query("rombelId");
        $targetRombel = $request->query("targetRombel");
        $tapel = $this->tapel()->kode;
        try {
            if (!$targetRombel) {
                $siswas = Siswa::whereDoesntHave("rombels", function ($r) use (
                    $tapel
                ) {
                    $r->whereTapel($tapel);
                })
                    // ->orWhereHas(
                    //     'rombels',
                    //     function ($q) use ($rombelId) {
                    //         $q->whereNot('rombels.id', $rombelId);
                    //     }
                    // )
                    ->where(
                        "sekolah_id",
                        $request->user()->userable->sekolahs[0]->npsn
                    )
                    ->with("sekolah")
                    ->whereStatus("aktif")
                    ->get();
            } else {
                $rombel = Rombel::where("id", $targetRombel)
                    ->with("siswas")
                    ->first();
                $siswas = $rombel->siswas;
            }
            return response()->json(["siswas" => $siswas]);
        } catch (\Exception $e) {
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    /**
     * Create accounts for siswas
     */

    public function addBulkAccount(Request $request, SiswaService $siswaService)
    {
        try {
            $res = $siswaService->bulkAccount(
                $request->sekolah_id,
                $request->rombel_id
            );
            return back()->with("message", $res["message"]);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    /**
     * Add account for siswa
     */
    public function addAccount(Request $request)
    {
        try {
            $siswa = Siswa::findorFail($request->id);
            // dd($siswa->email !== null);
            $user = User::updateOrCreate(
                [
                    "name" => $siswa->nisn,
                ],
                [
                    "password" => Hash::make($siswa->nisn),
                    "email" =>
                        $siswa->email &&
                        ($siswa->email !== "null" || $siswa->email === null)
                            ? $siswa->email
                            : $siswa->nisn . "@e.mail",
                    "userable_id" => $siswa->id,
                    "userable_type" => "App\Models\Siswa",
                ]
            );
            $user->assignRole("siswa");
            return back()->with("message", "Akun Siswa dibuat");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SiswaService $siswaService)
    {
        $update = $siswaService->store(
            $request->all(),
            $request->file("file") ?? null
        );
        // dd($store);
        return back()->with("message", "Data Siswa diperbarui");
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

    private function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
}
