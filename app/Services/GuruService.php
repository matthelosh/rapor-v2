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
        // return response()->json(['cek' => $request->file('file')]);
        if ($request->file('file') !== null) {
            $logo = $request->file('file');
            $logo_name = $request->npsn.'.'.$logo->extension();
            $store = $logo->storeAs('public/sekolah/', $logo_name);
            $logo = $store ? $logo_name /** Storage::url($store) **/ : null;
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

    public function impor($datas)
    {
        try {
            foreach($datas as $data)
            {
                Sekolah::updateOrCreate(
                    [
                        'npsn' => $data['npsn'],
                    ],
                    [
                        'nama' => $data['nama'],
                        'alamat' => $data['alamat'],
                        'desa' => $data['desa'],
                        'kecamatan' => $data['kecamatan'],
                        'kabupaten' => $data['kabupaten'],
                        'kode_pos' => $data['kode_pos'],
                        'telp' => $data['telp'],
                        'email' => $data['email'],
                        'website' => $data['website'],
                        'nama_ks' => $data['nama_ks'],
                        'nip_ks' => $data['nip_ks']
                    ]
                );
            }

            return ['success' => true, 'message' => 'Data Sekolah diimpor', 'data' => null ];
        }catch(\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
        }
    }

    public function destroy($id) {
        try {
            $sekolah = Sekolah::findOrFail($id);
            $split = explode("/", $sekolah->logo);
            Storage::delete("public/sekolah/".$split[(count($split) - 1)]);
            $destroy = $sekolah->delete();
            return ['success' => true, 'message' => 'Data Sekolah diimpor', 'data' => $destroy ];
        }catch(\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
        }
    }
}