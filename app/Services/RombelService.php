<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Kktp;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Rombel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Periode;
use App\Models\Tapel;




// use App\Http\Resources\SekolahResource;

class RombelService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function home($request)
    {
        $user = $request->user();
        $kktps = [];
        if ($user->hasRole("admin") || $user->hasRole("superadmin")) {
            $rombels = Rombel::with("sekolah", "gurus", "siswas")->get();
        } elseif ($user->hasRole("ops")) {
            // dd($request->query('tapel') === null);
            $rombels = Rombel::where(
                "sekolah_id",
                $user->name
            )
                ->where('tapel', isset($request->tapel) && $request->tapel !== 'null' ? $request->tapel : Periode::tapel()->kode)
                ->with("sekolah", "gurus", "siswas", "wali_kelas")
                ->with("kktps", function ($q) {
                    $q->with("mapel");
                })
                ->orderBy("id")
                ->get();
                // dd($rombels->where('tapel', Periode::tapel()->kode));
        } elseif ($user->hasRole("guru_kelas")) {
            $nip = $user->userable->nip;
            $rombels = Rombel::where(
                "sekolah_id",
                $user->userable->sekolahs[0]->npsn
            )
                // ->whereHas("wali_kelas")
                ->where("guru_id", $user->userable->id)
                ->where('tapel', Periode::tapel()->kode)
                ->with(["sekolah", "gurus", "siswas", "wali_kelas"])
                ->with("kktps", function ($q) {
                    $q->with("mapel");
                })
                ->get();
        }

        return ["rombels" => $rombels, 'sekolahs' => $user->userable->sekolahs, 'tapels' => Tapel::get()];
    }

    // public function index($request) {
    //     $user = $request->user();
    //     if ($user->hasRole('admin')) {
    //         $sekolahs = Sekolah::with('ks', 'ops')->with('gurus', function($q) {
    //             $q->where('gurus.jabatan','!=', 'ops');
    //         })->get();
    //     } else {
    //         $sekolahs = $user->userable->sekolahs;
    //     }

    //     return $sekolahs;
    // }

    public function store($request)
    {
        try {
            $data = $request->all();
            // dd($data["guru_id"]);
            $wali = Guru::whereId($data["guru_id"])->first();
            $store = Rombel::updateOrCreate(
                [
                    "id" => $data["id"] ?? null,
                    "kode" => $data["kode"],
                ],
                [
                    "tapel" => $data["tapel"],
                    "pararel" => $data["pararel"],
                    "label" => $data["label"],
                    "fase" => $data["fase"],
                    "tingkat" => $data["tingkat"],
                    "sekolah_id" => $data["sekolah_id"],
                    "guru_id" => $wali->id,
                    "is_active" => true,
                ]
            );
            $store->gurus()->attach($wali->nip, ["status" => "wali"]);
            $store
                ->gurus()
                ->syncWithPivotValues(explode(",", $data["pengajars"]), [
                    "status" => "pengajar",
                ]);
            // ->sync(
            //     collect(explode(",", $data["pengajars"]))->mapWithKeys(
            //         fn($nip) => [$nip => ["status" => "pengajar"]]
            //     )
            // );
            return back()->with("success", true);
        } catch (\Exception $e) {
            // dd($e);
            return back()->withErrors(["errors" => $e->getMessage()]);
        }
    }

    public function attachMember($id, $siswas)
    {
        try {
            // dd($siswas);
            $rombel = Rombel::findOrFail($id);
            foreach ($siswas as $siswa) {
                $rombel->siswas()->attach($siswa["id"]);
            }
            return true;
        } catch (\Exception $e) {
            dd($e);
        }
    }
    public function detachMember($id, $siswas)
    {
        try {
            // dd($siswas);
            $rombel = Rombel::findOrFail($id);
            foreach ($siswas as $siswa) {
                $rombel->siswas()->detach($siswa["id"]);
            }
            return true;
        } catch (\Exception $e) {
            dd($e);
        }
    }
    // public function impor($datas)
    // {
    //     try {
    //         foreach($datas as $data)
    //         {
    //             Sekolah::updateOrCreate(
    //                 [
    //                     'npsn' => $data['npsn'],
    //                 ],
    //                 [
    //                     'nama' => $data['nama'],
    //                     'alamat' => $data['alamat'],
    //                     'desa' => $data['desa'],
    //                     'kecamatan' => $data['kecamatan'],
    //                     'kabupaten' => $data['kabupaten'],
    //                     'kode_pos' => $data['kode_pos'],
    //                     'telp' => $data['telp'],
    //                     'email' => $data['email'],
    //                     'website' => $data['website'],
    //                     'nama_ks' => $data['nama_ks'],
    //                     'nip_ks' => $data['nip_ks']
    //                 ]
    //             );
    //         }

    //         return ['success' => true, 'message' => 'Data Sekolah diimpor', 'data' => null ];
    //     }catch(\Exception $e)
    //     {
    //         return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
    //     }
    // }

    // public function addOps($id) {
    //     try {
    //         // dd($id);
    //         $sekolah = Sekolah::findOrFail($id);
    //         // dd($sekolah);
    //         $ops = Guru::create([
    //             'nip' => $sekolah->npsn,
    //             'nama' => 'OPS '.$sekolah->nama,
    //             'jk' => 'Laki-laki',
    //             'alamat' => $sekolah->alamat,
    //             'hp' => '-',
    //             'status' => 'p3k',
    //             'email' => $sekolah->npsn.'@ops.net',
    //             'agama' => 'Islam',
    //             'jabatan' => 'ops'
    //         ]);

    //         $ops->sekolahs()->attach($sekolah->id);
    //         // dd($ops);

    //         $user = User::create([
    //             'name' => $sekolah->npsn,
    //             'email' => $ops->email,
    //             'password' => Hash::make($sekolah->npsn),
    //             'userable_id' => $ops->id,
    //             'userable_type' => 'App\Models\Guru'
    //         ]);

    //         $user->assignRole('ops');
    //         return ['success' => true, 'message' => 'Data Operator dibuat' ];
    //     } catch (\Throwable $th) {
    //         // dd($th);
    //         throw $th;
    //     }
    // }

    // public function destroy($id) {
    //     try {
    //         $sekolah = Sekolah::findOrFail($id);
    //         $split = explode("/", $sekolah->logo);
    //         Storage::delete("public/sekolah/".$split[(count($split) - 1)]);
    //         $destroy = $sekolah->delete();
    //         return ['success' => true, 'message' => 'Data Sekolah diimpor', 'data' => $destroy ];
    //     }catch(\Exception $e)
    //     {
    //         return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
    //     }
    // }
}
