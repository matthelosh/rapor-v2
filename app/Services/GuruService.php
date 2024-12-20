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

    public function index($request)
    {
        $user = $request->user();
        $q = $request->query("q") ? "%" . $request->query('q') . "%" : "%";
        if ($user->hasRole(['admin', 'kepala_sekolah', 'superadmin'])) {
            $gurus = Guru::with('sekolahs', 'user')
                ->where('nama', 'LIKE', $q)
                ->paginate(10);
        } elseif ($user->hasRole('ops')) {
            $gurus = Guru::whereHas('sekolahs', function ($q) use ($user) {
                $q->where('sekolahs.npsn', $user->userable->sekolahs[0]->npsn);
            })->where('jabatan', '!=', 'ops')->with('sekolahs', 'user')->paginate(10);
        } else {
            $gurus = Guru::where('nip', $user->userable->nip)->with('user', 'sekolahs')->get();
        }


        return $gurus;
    }

    public function show($request)
    {
        if ($request->query('sekolah') !== 'all') {
            $idSekolah = $request->query('sekolah');
            $gurus = Guru::wherehas('sekolahs', function ($q) use ($idSekolah) {
                $q->where('sekolahs.id', $idSekolah);
            })->get();
        } else {
            $gurus = Guru::all();
        }

        return $gurus;
    }

    public function store($data, $file, $ttd)
    {
        // dd($data, $file);
        // $data = $request->all();
        // return response()->json(['cek' => $request->file('file')]);
        if ($file !== null) {
            $foto_file = $file;
            $foto_name = $data['nip'] . '.' . $foto_file->extension();
            $store = $foto_file->storeAs('public/images/guru/', $foto_name);
            $foto = $store ?
                /**$foto_name **/
                Storage::url($store) : null;
        }

        if ($ttd !== null) {
            $store_ttd = $ttd->storeAs('public/images/ttd/', $data['nip'] . '.png');
        }

        $guru = Guru::updateOrCreate(
            [
                'id' => $data['id'] ?? null,
                'nip' => $data['nip'],
            ],
            [
                'nuptk' => $data['nuptk'] ?? null,
                'gelar_depan' => $data['gelar_depan'] !== 'null' ?  $data['gelar_depan'] : null,
                'nama' => $data['nama'],
                'gelar_belakang' => $data['gelar_belakang'] !== 'null' ? $data['gelar_belakang'] : null,
                'jk' => $data['jk'],
                'alamat' => $data['alamat'] ?? null,
                'hp' => $data['hp'] ?? '-',
                'status' => $data['status'],
                'email' => $data['email'] ?? $data['nip'] . '@raporsd.web.id',
                'foto' => $foto ?? null,
                'agama' => $data['agama'],
                'pangkat' => $data['pangkat'] ?? null,
                'jabatan' => $data['jabatan'] ?? null,
            ]
        );
        if ($guru->sekolahs->count() < 1) {
            $guru->sekolahs()->attach($data['sekolahs']);
        } else {
            $ids = explode(",", $data['sekolahs']);
            foreach ($guru->sekolahs->pluck('id') as $sekolah) {
                for ($i = 0; $i < count($ids); $i++) {
                    if ((int) $ids[$i] !== (int) $sekolah) {
                        $guru->sekolahs()->attach($ids[$i]);
                    }
                }
            }
        }


        return !isset($data['id']) ? 'Data Guru Disimpan' : 'Data Guru diperbarui';
    }

    public function addAccount($id)
    {
        $guru = Guru::findOrFail($id);
        if (!$guru->user) {
            $user = User::create([
                'name' => $guru->nip,
                'email' => $guru->email ?? $guru->nip . '@raporsd.web.id',
                'password' => Hash::make($guru->nip),
                'userable_id' => $guru->id,
                'userable_type' => 'App\Models\Guru'
            ]);
        } else {
            $user = $guru->user;
            $guru->update(['password' => Hash::make($guru->nip)]);
        }
        // if (->user()->hasRole('admin')) {
        $user->syncRoles(strtolower(str_replace(" ", "_", $guru->jabatan)));
        // }

        // $guru->user()->attach($user->id);

        return "Akun guru berhasil dibuat.";
    }

    public function impor($request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                if ($request->user()->hasRole('admin')) {
                    if (isset($data['sekolah'])) {
                        $sekolah = Sekolah::where('npsn', $data['sekolah'])->first();
                        $data['sekolahs'] = $sekolah->id;
                        $guru = $this->store($data, null, null);
                    } else {
                        throw new \Exception("Kolom npsn sekolah kosong");
                    }
                } else {
                    $data['sekolahs'] = $request->sekolah;
                    $guru = $this->store($data, null, null);
                }
            }

            return ['success' => true, 'message' => 'Data Guru diimpor', 'data' => null];
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->withErrors($e->getMessage());
        }
    }

    public function update($request)
    {

        $guru = Guru::find($request->id);
        // dd($guru->isDirty());
        dd($request->all());
    }

    public function destroy($id)
    {
        try {
            $guru = Guru::findOrFail($id);
            $split = explode("/", $guru->foto);
            // dd($split);
            Storage::delete("public/sekolah/guru/" . $split[(count($split) - 1)]);
            $guru->sekolahs()->detach();
            $delete = $guru->delete();
            return ['success' => true, 'message' => 'Data Guru dihapus', 'data' => $delete];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage(), 'data' => 'Error'];
        }
    }
}
