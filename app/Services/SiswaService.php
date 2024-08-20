<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\Tapel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaService
{
    public function home($request)
    {
        try {
            $user = $request->user();
            $tapel = $this->tapel()->kode;
            $q = $request->query('q') ? '%' . $request->query('q') . '%' : '%';
            if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
                $siswas = Siswa::where('nama', 'LIKE', $q)
                    ->with('sekolah', 'rombels', 'ortus')
                    ->paginate(15);
            } elseif ($user->hasRole('ops')) {
                $siswas = Siswa::where('sekolah_id', $user->name)
                    ->where('nama', 'LIKE', $q)
                    ->with('sekolah', 'ortus')
                    ->with('rombels', fn($r) => $r->where('tapel', $tapel))
                    ->get();
                // $siswas = Siswa::all();
            } elseif ($user->hasRole('guru_kelas')) {
                $siswas = Siswa::where('nama', 'LIKE', $q)
                    ->whereHas(
                        'rombels',
                        function ($q) use ($user) {
                            $q->where('rombels.guru_id', $user->userable->id);
                            $q->where('rombels.is_active', '1');
                        }
                    )->with('rombels', 'ortus')->get();
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
            $foto_name = $data['nisn'] . '.' . $foto_file->extension();
            $store = $foto_file->storeAs('public/sekolah/siswa/', $foto_name);
            $foto = $store ?
                /**$foto_name **/
                Storage::url($store) : null;
        }
        $siswa = Siswa::updateOrCreate(
            [
                'id' => $data['id'] ?? null,
            ],
            [
                'nisn' => $data['nisn'],
                'nis' => $data['nis'] ?? null,
                'nik' => $data['nik'] ?? null,
                'nama' => $data['nama'],
                'jk' => $data['jk'],
                'alamat' => $data['alamat'],
                'hp' => $data['hp'] ?? null,
                'email' => $data['email'] ?? null,
                'foto' => $foto ?? null,
                'agama' => $data['agama'],
                'angkatan' => $data['angkatan'] ?? null,
                'sekolah_id' => $data['sekolah_id'],
                'status' => $data['status'] ?? 'aktif'
            ]
        );

        return $siswa;
    }

    public function impor($request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                $data['sekolah_id'] = ($request->user()->hasRole('admin') && $data['sekolah_id']) ? $data['sekolah_id'] : $request->user()->userable->sekolahs[0]->npsn;
                $store = $this->store($data, null);
            }

            return true;
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }

    public function tapel()
    {
        return Tapel::whereIsActive(true)->first();
    }
}
