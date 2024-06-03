<?php
namespace App\Services;

use App\Models\Siswa;
use Illuminate\Support\Facades\Storage;

class SiswaService 
{
    public function home($request) {
        $user = $request->user();
        if($user->hasRole('admin')) {
            $siswas = Siswa::with('sekolah')->get();
        } elseif ($user->hasRole('ops')) {
            $siswas = Siswa::where('sekolah_id', $user->name)->with('sekolah')->get();
        }
        return $siswas;
    }

    public function store($data, $file) {
        if ($file !== null) {
            $foto_file = $file;
            $foto_name = $data['nisn'].'.'.$foto_file->extension();
            $store = $foto_file->storeAs('public/sekolah/siswa/', $foto_name);
            $foto = $store ? /**$foto_name **/ Storage::url($store) : null;
        }
            $siswa = Siswa::updateOrCreate(
                [
                'id' => $data['id'] ?? null,
                'nisn' => $data['nisn'],
            ],[
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
                'sekolah_id' => $data['sekolah_id']
            ]
        );

        return $siswa;
    }

    public function impor($request)
    {
        try {
            $datas = $request->datas;
            foreach($datas as $data)
            {
                $store = $this->store($data, null);
            }

            return true;
        }catch(\Exception $e)
        {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }

}