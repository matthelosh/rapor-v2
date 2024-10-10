<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\User;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Hash;
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

    public function index($request)
    {
        $user = $request->user();
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $sekolahs = Sekolah::with('ks', 'ops')->with('gurus', function ($q) {
                $q->where('gurus.jabatan', '!=', 'ops');
            })->get();
        } else {
            $sekolahs = Sekolah::where('npsn', $user->userable->sekolahs[0]->npsn)
                ->with('ks', 'ops')->with('gurus', function ($q) {
                    $q->where('gurus.jabatan', '!=', 'ops');
                })->get();
        }

        return $sekolahs;
    }

    public function store($request)
    {

        $data = $request->all();
        // return response()->json(['cek' => $request->file('file')]);
        if ($request->file('file') !== null) {
            $logo_file = $request->file('file');
            $logo_name = $request->npsn . '.' . $logo_file->extension();
            $store = $logo_file->storeAs('public/sekolah/', $logo_name);
            $logo = $store ? $logo_name
                /** Storage::url($store) **/
                : null;
        }
        $sekolah = Sekolah::updateOrCreate([
            'id' => $data['id'] ?? null,
        ], [
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
            'ks_id' => $data['ks_id'],
            'gugus_id' => $data['gugus_id'] === 'null' ? null : ($data['gugus_id'] ?? null)
        ]);


        return ['success' => true, 'message' => 'Data Sekolah disimpan', 'data' => $sekolah];
    }

    public function impor($datas)
    {
        try {
            // dd($datas);
            foreach ($datas as $data) {
                $store = Sekolah::updateOrCreate(
                    [
                        'id' => $data['id'] ?? null,
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
                        'ks_id' => $data['ks_id'] ?? null,
                    ]
                );

                // if (!$store) {
                // dd($store);
                // }
            }

            // return ['success' => true, 'message' => 'Data Sekolah diimpor', 'data' => null];
        } catch (\Exception $e) {
            // return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
            dd($e);
        }
    }

    public function addOps($id)
    {
        try {
            // dd($id);
            $sekolah = Sekolah::findOrFail($id);
            // dd($sekolah);
            $ops = Guru::create([
                'nip' => $sekolah->npsn,
                'nama' => 'OPS ' . $sekolah->nama,
                'jk' => 'Laki-laki',
                'alamat' => $sekolah->alamat,
                'hp' => '-',
                'status' => 'p3k',
                'email' => $sekolah->npsn . '@ops.net',
                'agama' => 'Islam',
                'jabatan' => 'ops'
            ]);

            $ops->sekolahs()->attach($sekolah->id);
            // dd($ops);

            $user = User::create([
                'name' => $sekolah->npsn,
                'email' => $ops->email,
                'password' => Hash::make($sekolah->npsn),
                'userable_id' => $ops->id,
                'userable_type' => 'App\Models\Guru'
            ]);

            $user->assignRole('ops');
            return ['success' => true, 'message' => 'Data Operator dibuat'];
        } catch (\Throwable $th) {
            // dd($th);
            throw $th;
        }
    }

    public function destroy($id)
    {
        try {
            $sekolah = Sekolah::findOrFail($id);
            $split = explode("/", $sekolah->logo);
            Storage::delete("public/sekolah/" . $split[(count($split) - 1)]);
            $destroy = $sekolah->delete();
            return ['success' => true, 'message' => 'Data Sekolah diimpor', 'data' => $destroy];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
        }
    }
}
