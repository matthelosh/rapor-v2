<?php

namespace App\Http\Controllers;

use App\Models\Ekskul;
use App\Models\Elemen;
use Inertia\Inertia;
use App\Models\Mapel;
use App\Models\Sekolah;
use App\PembelajaranTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;

class PembelajaranController extends Controller implements HasMiddleware
{
    use PembelajaranTrait;


    public function home(Request $request)
    {
        // dd(Mapel::with('tps')->get());
        // dd($request->user()->getDirectPermissions());
        // $mapel = [
        //     'Islam',
        //     'kristen',
        //     'Katolik',
        //     'Hindu',
        //     'Budha',
        //     'Konghuchu',
        //     'inggris',
        //     'pjok',
        //     '1',
        //     '2',
        //     '3',
        //     '4',
        //     '5'
        // ];
        if ($request->user()->hasRole('admin_tp')) {
            $permission_name = $request->user()->getPermissionNames();
            $permission = \explode("_", $permission_name[0]);
            $mapel = \end($permission);
            // dd($mapel);
            if (\strtolower($mapel) == 'islam') {
                $mapels = Mapel::where('kode', 'pabp')
                    ->with([
                        'tps' => function ($t) {
                            $t->where('agama', 'Islam');
                        }
                    ])->get();
            } elseif (\in_array($mapel, ['1', '2', '4', '5', '6'])) {
                $mapels = Mapel::whereNot('kode', 'pabp')->with('tps')->with([
                    'tps' => function ($t) use ($mapel) {
                        $t->where('tingkat', $mapel);
                    }
                ])->get();
            }
        } else {
            $mapels = Mapel::with('tps')->get();
        }
        return Inertia::render('Dash/Pembelajaran', [
            'mapels' => $mapels,
            'elemens' => Elemen::all(),
            'ekskuls' => Ekskul::all()
        ]);
    }

    public function assignMapel(Request $request)
    {
        // dd($request->sekolahId);
        try {
            $sekolah = Sekolah::findOrFail($request->sekolahId);
            // dd($request->mapels);
            $sekolah->mapels()->sync($request->mapels);

            return back()->with('message', "Mapel ditambahkan ke " . $sekolah->nama);
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function imporMapel(Request $request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                Mapel::updateOrCreate(
                    [
                        'kode' => $data['kode'],
                    ],
                    [
                        'kode' => $data['kode'],
                        'label' => $data['label'],
                        'fase' => $data['fase'],
                        'kategori' => $data['kategori'],
                        'deskripsi' => $data['deskripsi'],
                    ]
                );
            }

            return back()->with('message', 'Mapel Diimpor');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function imporEkskul(Request $request)
    {
        try {
            $datas = $request->datas;
            foreach ($datas as $data) {
                Ekskul::updateOrCreate(
                    [
                        'kode' => $data['kode'],
                    ],
                    [
                        'nama' => $data['nama'],
                        'keterangan' => $data['keterangan'],
                        'sifat' => $data['sifat'],
                        'is_active' => true,
                    ]
                );
            }

            return back()->with('message', 'Ekskul Diimpor');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function assignEkskul(Request $request)
    {
        $sekolah = Sekolah::findOrFail($request->sekolahId);
        $sekolah->ekskuls()->sync($request->ekskuls);

        return back()->with('message', "Ekskul ditambahkan ke " . $sekolah->nama);
    }

    public function indexEkskul(Request $request)
    {
        $sekolahId = $request->query('sekolahId');
        $ekskuls =  Ekskul::whereHas('sekolah', function ($q) use ($sekolahId) {
            $q->where(function ($sq) use ($sekolahId) {
                $sq->where('npsn', $sekolahId);
            });
        })->get();

        return response()->json(['ekskuls' => $ekskuls]);
    }


    public static function middleware(): array
    {
        return [
            'role:admin|ops|guru_kelas|admin_tp',
        ];
    }
}
