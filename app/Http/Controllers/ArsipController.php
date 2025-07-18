<?php

namespace App\Http\Controllers;

use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Helpers\SekolahHelper;
use App\Models\ArsipIjazah;
use Storage;

class ArsipController extends Controller
{
    public function homeRapor(Request $request)
    {
        try {
            $sekolahId = $request->user()->userable->sekolahs[0]->npsn;
            $tapels = Tapel::with([
                "rombels" => function ($q) use ($sekolahId) {
                    $q->where("rombels.sekolah_id", $sekolahId);
                    $q->whereHas("wali_kelas");
                    $q->with("siswas", "wali_kelas", "sekolah.ks");
                },
            ])->get();
            return Inertia::render("Dash/Arsip", [
                "tapels" => $tapels,
                "sekolahs" => \sekolahs($request->user()),
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function homeIjazah(Request $request)
    {
        try {
            $sekolahId = $request->user()->userable->sekolahs[0]->npsn;
            return Inertia::render("Dash/Arsip/Ijazah", [
                "tapels" => Tapel::whereHas('rombels')->with([
                    'rombels' => function ($q) use ($sekolahId) {
                        $q->where("rombels.sekolah_id", $sekolahId);
                        $q->where('tingkat', '6');
                        $q->with(['siswas' => function ($s) {
                            $s->with('ijazah');
                        }]);
                    },
                ])->get(),
            ]);
        }catch(\Exception $e)
        {

            dd($e);
        }
    }

    public function storeIjazah(Request $request)
    {
        try {
            // dd($request->all());
            $fileIjazah = $request->file("file_ijazah");
            $fileTranskrip = $request->file("file_transkrip");
            $fileSkl = $request->file("file_skl");
            // $nisn = $request->input("nisn");
            $sekolahId = $request->user()->userable->sekolahs[0]->npsn;
            // dd($sekolahId);
            $arsipPath = "public/arsip/{$sekolahId}/{$request->tapel}";
            if ($request->hasFile('file_ijazah')) {
                $fileIjazah = $request->file('file_ijazah');

                // Pastikan sekolahId dan tapel adalah string biasa
                $sekolahId = (string) $sekolahId;
                $tapel = (string) $request->tapel;
                $siswaId = (string) $request->siswa_id;

                // Nama file yang diinginkan
                $ijazahName = "ijazah_{$siswaId}." . $fileIjazah->getClientOriginalExtension();


                // Upload dengan nama file yang jelas dan visibilitas public
                $storeIjazah = Storage::disk('s3')->putFileAs(
                    $arsipPath,       // folder tujuan
                    $fileIjazah,       // file
                    $ijazahName,         // nama file
                    'public'           // visibilitas
                );
            }

            if ($fileTranskrip) {
                $fileTranskrip = $request->file('file_transkrip');

                // Pastikan sekolahId dan tapel adalah string biasa
                $sekolahId = (string) $sekolahId;
                $tapel = (string) $request->tapel;
                $siswaId = (string) $request->siswa_id;

                // Nama file yang diinginkan
                $transkripName = "transkrip_{$siswaId}." . $fileTranskrip->getClientOriginalExtension();

                // Path folder dalam bucket

                // Upload dengan nama file yang jelas dan visibilitas public
                $storeTranskrip = Storage::disk('s3')->putFileAs(
                    $arsipPath,       // folder tujuan
                    $fileTranskrip,       // file
                    $transkripName,         // nama file
                    'public'           // visibilitas
                );
            }
            if ($fileSkl) {
                $fileSkl = $request->file('file_ijazah');

                // Pastikan sekolahId dan tapel adalah string biasa
                $sekolahId = (string) $sekolahId;
                $tapel = (string) $request->tapel;
                $siswaId = (string) $request->siswa_id;

                // Nama file yang diinginkan
                $sklName = "skl_{$siswaId}." . $fileSkl->getClientOriginalExtension();

                // Path folder dalam bucket

                // Upload dengan nama file yang jelas dan visibilitas public
                $storeSkl =Storage::disk('s3')->putFileAs(
                    $arsipPath,       // folder tujuan
                    $fileSkl,       // file
                    $sklName,         // nama file
                    'public'           // visibilitas
                );
            }

            $arsip = ArsipIjazah::updateOrCreate(
                [
                    'id' => $request->input('id') ?? null,
                ],
                [
                    'no_seri' => $request->input('no_seri'),
                    'tapel' => $request->input('tapel'),
                    'siswa_id' => $request->input('siswa_id'),
                    'sekolah_id' => $sekolahId,
                    'url' => $storeIjazah ? $arsipPath . '/' . $ijazahName : null,
                    'url_transkrip' => isset($storeTranskrip) ? ($arsipPath . '/' . $transkripName) : null,
                    'url_skl' => isset($storeSkl) ? ($arsipPath . '/' . $sklName) : null,
                    'keterangan' => $request->input('keterangan') ?? null,
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Arsip disimpan' 
                ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
