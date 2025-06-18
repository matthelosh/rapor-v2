<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use App\Http\Resources\SekolahResource;

class GuruService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index($request)
    {
        $user = $request->user();
        $q = $request->query("q") ? "%" . $request->query("q") . "%" : "%";
        if ($user->hasRole(["admin", "kepala_sekolah", "superadmin"])) {
            $gurus = Guru::with("sekolahs", "user")
                ->where("nama", "LIKE", $q)
                ->paginate(10);
        } elseif ($user->hasRole("ops")) {
            $gurus = Guru::whereHas("sekolahs", function ($q) use ($user) {
                $q->where("sekolahs.npsn", $user->userable->sekolahs[0]->npsn);
            })
                /* ->where("jabatan", "!=", "ops") */
                ->with("sekolahs", "user")
                /* ->get(); */
                ->paginate(10);
        } else {
            $gurus = Guru::where("nip", $user->userable->nip)
                ->with("user", "sekolahs")
                ->get();
        }

        return $gurus;
    }

    public function show($request)
    {
        if ($request->query("sekolah") !== "all") {
            $idSekolah = $request->query("sekolah");
            $gurus = Guru::wherehas("sekolahs", function ($q) use ($idSekolah) {
                $q->where("sekolahs.id", $idSekolah);
            })->get();
        } else {
            $gurus = Guru::all();
        }

        return $gurus;
    }

    private function storeFoto($file)
    {
        $foto_file = $file;
        $foto_name = $data["nip"] . "." . $foto_file->extension();
        $store = $foto_file->storeAs("public/images/guru/", $foto_name);
        $foto = $store ? /**$foto_name **/ Storage::url($store) : null;
        return $foto;
    }

    private function storeTtd($ttd, $nip)
    {
        try {
            $store_ttd = $ttd->storeAs("public/images/ttd/", $nip . ".png");
            return $store_ttd ? Storage::url($store_ttd) : null;
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function store($data, $file, $ttd)
    {
        if ($file !== null) {
            $foto = $this->storeFoto($file);
        }

        if ($ttd !== null) {
            $store_ttd = $this->storeTtd($ttd, $data["nip"]);
        }

        // dd($data);
        $guru = Guru::create([
            "nip" => $data["nip"],
            "nuptk" => $data["nuptk"] ?? null,
            "gelar_depan" =>
                $data["gelar_depan"] || $data["gelar_depan"] !== "null" ?? null,
            "nama" => $data["nama"],
            "gelar_belakang" => $data["gelar_belakang"] ?? null,
            "jk" => $data["jk"],
            "alamat" => $data["alamat"] ?? "-",
            "hp" => $data["hp"] ?? "-",
            "status" => $data["status"],
            "email" => $data["email"] ?? $data["nip"] . "@raporsd.web.id",
            "foto" => $foto ?? null,
            "agama" => $data["agama"],
            "pangkat" => $data["pangkat"] ?? null,
            "jabatan" => $data["jabatan"] ?? null,
        ]);
        if ($guru->sekolahs->count() < 1) {
            $guru->sekolahs()->attach($data["sekolahs"]);
        } else {
            $ids = explode(",", $data["sekolahs"]);
            $guru->sekolahs()->sync($ids);
        }

        return "Data Guru Disimpan";
    }

    public function addAccount($id)
    {
        $guru = Guru::findOrFail($id);
        $user = User::updateOrCreate(
            [
                "name" => $guru->nip,
            ],
            [
                "email" => $guru->email ?? $guru->nip . "@raporsd.web.id",
                "password" => Hash::make($guru->nip),
                "userable_id" => $guru->id,
                "userable_type" => "App\Models\Guru",
            ]
        );
        $user->syncRoles(strtolower(str_replace(" ", "_", $guru->jabatan)));

        return "Akun guru berhasil dibuat.";
    }

    public function impor($request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                if ($request->user()->hasRole("admin")) {
                    if (isset($data["sekolah"])) {
                        $sekolah = Sekolah::where(
                            "npsn",
                            $data["sekolah"]
                        )->first();
                        $data["sekolahs"] = $sekolah->id;
                        $guru = $this->store($data, null, null);
                    } else {
                        throw new \Exception("Kolom npsn sekolah kosong");
                    }
                } else {
                    $data["sekolahs"] = $request->sekolah;
                    $guru = $this->store($data, null, null);
                }
            }

            return [
                "success" => true,
                "message" => "Data Guru diimpor",
                "data" => null,
            ];
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }

    public function update($data, $file, $ttd)
    {
        if ($file !== null) {
            $foto = $this->storeFoto($file);
        }

        if ($ttd !== null) {
            $store_ttd = $this->storeTtd($ttd, $data["nip"]);
        }
        $guru = Guru::whereId($data["id"])->with("rombels")->first();
        $rombels = $guru->rombels->map(fn($rombel) => $rombel->kode);
        $guru->rombels()->delete();
        $guru->update([
            "nip" => $data["nip"],
            "nuptk" => $data["nuptk"] ?? null,
            "gelar_depan" =>
                $data["gelar_depan"] && $data["gelar_depan"] !== "null"
                    ? $data["gelar_depan"]
                    : null,
            "nama" => $data["nama"],
            "gelar_belakang" => $data["gelar_belakang"] ?? null,
            "jk" => $data["jk"],
            "alamat" => $data["alamat"] ?? "-",
            "hp" => $data["hp"] ?? "-",
            "status" => $data["status"],
            "email" => $data["email"] ?? $data["nip"] . "@raporsd.web.id",
            "foto" => $foto ?? null,
            "agama" => $data["agama"],
            "pangkat" => $data["pangkat"] ?? null,
            "jabatan" => $data["jabatan"] ?? null,
        ]);
        $guru->rombels()->attach($rombels);
        if ($guru->sekolahs->count() < 1) {
            if ($data["jabatan"] == "Ops") {
                $sekolah = Sekolah::where("npsn", $data["nip"])->first();
                $guru->sekolahs()->sync($sekolah->id);
            } else {
                $guru->sekolahs()->attach($data["sekolahs"]);
            }
        } else {
            $ids = explode(",", $data["sekolahs"]);
            $guru->sekolahs()->sync($ids);
        }
        return "Data guru diperbarui.";
    }

    public function destroy($id)
    {
        try {
            $guru = Guru::findOrFail($id);
            $split = explode("/", $guru->foto);
            // dd($split);
            Storage::delete("public/sekolah/guru/" . $split[count($split) - 1]);
            $guru->sekolahs()->detach();
            $guru->user()->delate();
            $delete = $guru->delete();
            return [
                "success" => true,
                "message" => "Data Guru dihapus",
                "data" => $delete,
            ];
        } catch (\Exception $e) {
            return [
                "success" => false,
                "message" => $e->getMessage(),
                "data" => "Error",
            ];
        }
    }
}
