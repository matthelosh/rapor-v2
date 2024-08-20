<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgendaController extends Controller
{
    public function home(Request $request)
    {
        $agendas = Agenda::whereTapel($this->periode()['tapel']->kode)->orderBy('mulai', 'ASC')->get();
        return Inertia::render(
            'Dash/Agenda',
            [
                'agendas' => $agendas,
            ]
        );
    }

    public function store(Request $request)
    {
        try {
            $data = $request->data;
            $store = Agenda::updateOrCreate(
                [
                    'id' => $data['id'] ?? \null,
                ],
                [
                    'nama' => $data['nama'],
                    'pelaksana' => $data['pelaksana'],
                    'mulai' => $data['mulai'],
                    'selesai' => $data['selesai'],
                    'deskripsi' => $data['deskripsi'],
                    'is_libur' => \true,
                    'warna' => 'red',
                    'tapel' => $this->periode()['tapel']->kode
                ]
            );

            return back()->with('message', 'Agenda Disimpan');
        } catch (\Throwable $th) {
            // return \back()->withErrors('errors', $th->getMessage());
            throw $th;
        }
    }

    private function periode()
    {

        return [
            'tapel' => Tapel::whereIsActive(1)->first(),
            'semester' => Semester::whereIsActive(1)->first()
        ];
    }
}
