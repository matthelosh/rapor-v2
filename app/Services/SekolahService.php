<?php

namespace App\Services;

use App\Models\Sekolah;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
// use App\Http\Resources\SekolahResource;

class SekolahService
{

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index() {
        return Sekolah::paginate(5);
    }

    public function store($request) {

        $data = $request->all();
        // dd($data);
        // return response()->json(['cek' => $request->file('file')]);
        if ($request->file('file') !== null) {
            $logo = $request->file('file');
            $logo_name = $request->npsn.'.'.$logo->extension();
            $store = $logo->storeAs('public/sekolah', $logo_name);
            $logo = $store ? Storage::url($store) : null;
        }
            $sekolah = Sekolah::updateOrCreate([
                'id' => $data['id'] ?? null,
            ],[
                'npsn' => $data['npsn'],
                'nama' => $data['nama'],
                'logo' => $logo ?? null,
                'alamat' => $data['alamat'],
                'desa' => $data['desa'],
                'kecamatan' => $data['kecamatan'] ?? 'Wagir',
                'kabupaten' => $data['kabupaten'] ?? 'Malang',
                'kode_pos' => $data['kode_pos'] ?? '65158',
                'telp' => $data['telp'] ?? null,
                'email' => $data['email'] ?? null,
                'website' => $data['website'] ?? null,
                'nama_ks' => $data['nama_ks'],
                'nip_ks' => $data['nip_ks'] ?? '-'
            ]);


            return ['success' => true, 'message' => 'Data Sekolah disimpan', 'data' => $sekolah];
    }

}
