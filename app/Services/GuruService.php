<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

    public function index($request) {
        $user = $request->user();
        if ($user->hasRole('admin')) {
            $gurus = Guru::all();
        } else {
            $gurus = Guru::whereHas('sekolahs', function($q) use($user) {
                $q->where('sekolahs.npsn', $user->userable->sekolahs[0]->npsn);
            })->get();
        }


        return $gurus;
    }

    public function store($data, $file) {

        // $data = $request->all();
        // return response()->json(['cek' => $request->file('file')]);
        if ($file !== null) {
            $foto_file = $file;
            $foto_name = $data['nip'].'.'.$foto_file->extension();
            $store = $foto_file->storeAs('public/sekolah/guru/', $foto_name);
            $foto = $store ? /**$foto_name **/ Storage::url($store) : null;
        }
            $guru = Guru::updateOrCreate(
                [
                'id' => $data['id'] ?? null,
                'nip' => $data['nip'],
            ],[
                'nuptk' => $data['nuptk'] ?? null,
                'gelar_depan' => $data['gelar_depan'] ?? null,
                'nama' => $data['nama'],
                'gelar_belakang' => $data['gelar_belakang'] ?? null,
                'jk' => $data['jk'],
                'alamat' => $data['alamat'] ?? null,
                'hp' => $data['hp'] ?? '-',
                'status' => $data['status'],
                'email' => $data['email'] ?? $data['nip'].'@raporsd.web.id',
                'foto' => $foto ?? null,
                'agama' => $data['agama'],
                'pangkat' => $data['pangkat'] ?? null,
                'jabatan' => $data['jabatan'] ?? null,
            ]
        );

        $guru->sekolahs()->attach($data['sekolahs']);


        return $guru;
    }

    public function addAccount($id) {
        $guru = Guru::findOrFail($id);
        $user = User::create([
            'name' => $guru->nip,
            'email' => $guru->email ?? $guru->nip.'@raporsd.web.id',
            'password' => Hash::make($guru->nip),
            'userable_id' => $guru->id,
            'userable_type' => 'App\Models\Guru'
        ]);

        // $guru->user()->attach($user->id);

        return ['success' => true, 'message' => 'Akun dibuat', 'data' => $user ];
    }

    public function impor($request)
    {
        try {
            $datas = $request->datas;
            foreach($datas as $data)
            {
                if ($request->user()->hasRole('admin')) {
                    if (isset($data['sekolah'])) {
                        $sekolah = Sekolah::where('npsn', $data['sekolah'])->first();
                        $data['sekolahs'] = $sekolah->id;
                        $guru = $this->store($data, null);
                    } else {
                        throw new \Exception("Kolom npsn sekolah kosong");
                    }
                } else {
                    $data['sekolahs'] = $request->sekolah;
                    $guru = $this->store($data, null);
                }
                // $guru = Guru::updateOrCreate(
                //     [
                //         'id' => $data['id'] ?? null,
                //         'nip' => $data['nip'],
                //     ],[
                //         'nuptk' => $data['nuptk'] ?? null,
                //         'gelar_depan' => $data['gelar_depan'] ?? null,
                //         'nama' => $data['nama'],
                //         'gelar_belakang' => $data['gelar_belakang'] ?? null,
                //         'jk' => $data['jk'],
                //         'alamat' => $data['alamat'] ?? '-',
                //         'hp' => $data['hp'] ?? '-',
                //         'status' => $data['status'],
                //         'email' => $data['email'] ?? $data['nip'].'@raporsd.web.id',
                //         'agama' => $data['agama'],
                //         'pangkat' => $data['pangkat'] ?? null,
                //         'jabatan' => $data['jabatan'] ?? null,
                //     ]
                // );
            }

            return ['success' => true, 'message' => 'Data Guru diimpor', 'data' => null ];
        }catch(\Exception $e)
        {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $guru = Guru::findOrFail($id);
            $split = explode("/", $guru->foto);
            // dd($split);
            Storage::delete("public/sekolah/guru/".$split[(count($split) - 1)]);
            $guru->sekolahs()->detach();
            $delete = $guru->delete();
            return ['success' => true, 'message' => 'Data Guru dihapus', 'data' => $delete ];
        }catch(\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
        }
    }
}
