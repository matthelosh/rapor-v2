<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Tapel;
use App\Models\User;
use App\Models\Ortu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Jobs\CreateOrUpdateUserJob;
use App\Helpers\Periode;
use DB;

class SiswaService
{
    public function home($request)
    {
        try {
            $user = $request->user();
            $tapel = $this->tapel()->kode;
            $q = $request->query("q") ? "%" . $request->query("q") . "%" : "%";
            if ($user->hasRole("admin") || $user->hasRole("superadmin")) {
                $siswas = Siswa::where("nama", "LIKE", $q)
                    ->with(["sekolah:id,npsn,nama", "rombels", "ortus:id,siswa_id,nama,relasi,alamat,hp,pekerjaan", "user:id,name,email,userable_id,userable_type"])
                    ->orderBy('sekolah_id', 'ASC')
                    ->orderBy('nama', 'ASC')
                    ->paginate(15);
            } elseif ($user->hasRole("ops")) {
                $siswas = Siswa::where("sekolah_id", $user->name)
                    ->where("nama", "LIKE", $q)
                    // ->where('status', 'aktif')
                    ->aktif()
                    ->with(["sekolah:id,npsn,nama", "rombels", "ortus:id,siswa_id,nama,relasi,alamat,hp,pekerjaan", "user:id,name,email,userable_id,userable_type"])
                    ->with("rombels", fn($r) => $r->where("tapel", $tapel))
                    ->orderBy('nama', 'ASC')
                    ->paginate(15);
                // $siswas = Siswa::all();
            } elseif ($user->hasRole("guru_kelas")) {
                $siswas = Siswa::where("nama", "LIKE", $q)
                    ->whereHas("rombels", function ($q) use ($user, $tapel) {
                        $q->where("rombels.guru_id", $user->userable->id);
                        $q->where("rombels.tapel", $tapel);
                    })
                    ->with([
                        "rombels" => function ($q) use($tapel) {
                            $q->where('tapel', $tapel);
                        },
                        "ortus:id,siswa_id,nama,relasi,alamat,hp,pekerjaan",
                        "user"
                        ])
                    ->orderBy('nama', 'ASC')
                    ->paginate(15);
            }
            return $siswas;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function store($data, $file)
    {
        if ($file !== null) {
            $foto_file = $file;
            $foto_name = $data["nisn"] . "." . $foto_file->extension();
            $store = $foto_file->storeAs("public/images/siswa/", $foto_name);
            $foto = $store ? /**$foto_name **/ Storage::url($store) : null;
        }
        $jk = ["L", "P"];
        $siswa = Siswa::updateOrCreate(
            [
                "nisn" => $data["nisn"],
            ],
            [
                "nis" => $data["npd"] ?? null,
                "nik" => $data["nik"] ?? null,
                "nama" => $data["nama"],
                "jk" => !in_array($data["jk"], $jk)
                    ? $data["jk"]
                    : ($data["jk"] == "L"
                        ? "Laki-laki"
                        : "Perempuan"),
                "tempat_lahir" => $data["tempat_lahir"] ?? null,
                "tanggal_lahir" => $data["tanggal_lahir"] ?? null,
                "alamat" => $data["alamat"],
                "rt" => $data["rt"] ?? null,
                "rw" => $data["rw"] ?? null,
                "desa" => $data["desa"] ?? $data["kelurahan"],
                "kecamatan" => $data["kecamatan"] ?? null,
                "kode_pos" => $data["kode_pos"] ?? "65158",
                "kabupaten" => $data["kabupaten"] ?? "Malang",
                "provinsi" => $data["provinsi"] ?? "Jawa Timur",
                "hp" => $data["hp"] ?? "-",
                "email" => $data["email"] ?? null,
                "foto" => $foto ?? null,
                "agama" => $data["agama"],
                "angkatan" => $data["angkatan"] ?? null,
                "sekolah_id" => $data["sekolah_id"],
                "status" => $data["status"] ?? "aktif",
            ]
        );

        return $siswa;
    }

    public function impor($request)
    {
        try {
            $datas = $request->datas;
            // dd($datas);
            $items = [];
            $ortus = [];
            foreach ($datas as $data) {
                // $data["sekolah_id"] =
                //     $request->user()->hasRole("admin") && $data["sekolah_id"]
                //         ? $data["sekolah_id"]
                //         : $request->user()->userable->sekolahs[0]->npsn;
                // $store = $this->store($data, null);
                if (!$data['nisn'] ||$data['nisn'] == null) {
                    // return back()->withErrors("NISN tidak boleh kosong");
                    continue;
                }
                $ortus[] = [
                    // 'siswa_id' => $data['nisn'],
                    [
                        "siswa_id" => $data["nisn"],
                        "nama" => $data["nama_ayah"],
                        "relasi" => "Ayah",
                        "alamat" => $data["alamat"],
                        "hp" => $data["hp"],
                        "pekerjaan" => $data["pekerjaan_ayah"],
                    ],
                    [
                        "siswa_id" => $data["nisn"],
                        "nama" => $data["nama_ibu"],
                        "relasi" => "Ibu",
                        "alamat" => $data["alamat"],
                        "hp" => $data["hp"],
                        "pekerjaan" => $data["pekerjaan_ibu"],
                    ],
                    [
                        "siswa_id" => $data["nisn"],
                        "nama" => $data["nama_wali"],
                        "relasi" => "Wali",
                        "alamat" => $data["alamat"],
                        "hp" => $data["hp"],
                        "pekerjaan" => $data["pekerjaan_wali"],
                    ],
                ];
                $items[] = [
                    "nisn" => $data["nisn"] ?? null,
                    "nis" => $data["nipd"] ?? null,
                    "nik" => $data["nik"] ?? null,
                    "nama" => $data["nama"],
                    "jk" => $data["jk"] == "L" ? "Laki-laki" : "Perempuan",
                    "tempat_lahir" => $data["tempat_lahir"] ?? null,
                    "tanggal_lahir" => $data["tanggal_lahir"] ?? null,
                    "alamat" => $data["alamat"],
                    "rt" => $data["rt"] ?? null,
                    "rw" => $data["rw"] ?? null,
                    "desa" => $data["desa"] ?? $data["kelurahan"],
                    "kecamatan" => $data["kecamatan"] ?? null,
                    "kode_pos" => $data["kode_pos"] ?? "65158",
                    "kabupaten" => $data["kabupaten"] ?? "Malang",
                    "provinsi" => $data["provinsi"] ?? "Jawa Timur",
                    "hp" => $data["hp"] ?? "-",
                    "email" => $data["email"] ?? null,
                    "foto" => null,
                    "agama" => $data["agama"],
                    "angkatan" => $data["angkatan"] ?? null,
                    "sekolah_id" =>
                        $request->user()->hasRole("admin") &&
                        $data["sekolah_id"]
                            ? $data["sekolah_id"]
                            : $request->user()->userable->sekolahs[0]->npsn,
                    "status" => $data["status"] ?? "aktif",
                ];
            }
            /* dd($items[0]); */
            /* /* $fails = []; */
            /* foreach($items as $item) { */
            /*     if (!$item['agama']) { */
            /*         array_push($fails, $item); */
            /*     } */
            /* } */
            /**/
            /* dd($fails); */
            DB::table("siswas")->upsert(
                $items,
                ["nisn"],
                [
                    "nisn",
                    "nis",
                    "nik",
                    "nama",
                    "tempat_lahir",
                    "tanggal_lahir",
                    "jk",
                    "alamat",
                    "rt",
                    "rw",
                    "desa",
                    "kecamatan",
                    "kode_pos",
                    "kabupaten",
                    "provinsi",
                    "hp",
                    "email",
                    "foto",
                    "agama",
                    "angkatan",
                    "sekolah_id",
                    "status",
                ]
            );

            foreach ($ortus as $dataOrtu) {
                foreach ($dataOrtu as $ortu) {
                    Ortu::updateOrCreate(
                        [
                            "siswa_id" => $ortu["siswa_id"],
                            "relasi" => $ortu["relasi"],
                        ],
                        [
                            "nama" => $ortu["nama"] ?? "-",
                            "alamat" => $ortu["alamat"] ?? "-",
                            "hp" => $ortu["hp"] ?? "-",
                            "pekerjaan" => $ortu["pekerjaan"] ?? "-",
                        ]
                    );
                }
            }

            return true;
        } catch (\Exception $e) {
            dd($e);
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Bulk create account for siswa
     *
     * @param int $sekolah_id
     * @param int $rombel_id
     * @return bool
     */
    public function bulkAccount($sekolah_id, $rombelKode)
    {
        try {
            if ($rombelKode) {
                $siswas = Siswa::where("sekolah_id", $sekolah_id)
                    ->whereHas("rombels", function ($q) use ($rombelKode) {
                        $q->where("rombels.kode", $rombelKode);
                    })
                    ->with('user') // Eager load user to avoid N+1 queries in the loop.
                    ->where("status", "aktif")
                    ->whereDoesntHave("user")
                    ->get();
            } else {
                $siswas = Siswa::where("sekolah_id", $sekolah_id)
                    ->where("status", "aktif")
                    ->whereDoesntHave("user")
                    ->get();
            }
            /* dd($siswas); */
            $count = 0;
            foreach ($siswas as $siswa) {
                if (!$siswa->user) {
                    CreateOrUpdateUserJob::dispatch($siswa)->delay(
                    now()->addSEconds(1)
                    );
                    $count++;
                }
            }
            return [
                "message" =>
                    "{$count} siswa aktif yang belum memiliki akun akan diberikan akun dalam beberapa saat. " .
                    "Username: NISN siswa, password awal: NISN (disarankan segera mengubah password setelah login).",
            ];
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function luluskan($siswas)
    {
        $periode = Periode::tapel()['kode'];
        $tahun = '20'. substr($periode, 2, 2);
        foreach ($siswas as $siswa) {
            $murid = Siswa::findorFail($siswa['id']);
            $murid->update(['status' => "lulus", 'tahun_lulus' => $tahun]);
            $murid->user()->delete();
        }
        return true;
    }

    public function tapel()
    {
        return Tapel::whereIsActive(true)->first();
    }
}
