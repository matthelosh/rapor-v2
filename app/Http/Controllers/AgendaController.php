<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Org;
use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgendaController extends Controller
{
    public function home(Request $request)
    {
        $agendas = Agenda::whereTapel($this->periode()['tapel']->kode)->with('pesertas')->orderBy('mulai', 'ASC')->get();
        // dd($agendas);
        $tapel = explode("/",$this->periode()['tapel']->label);

        return Inertia::render(
            'Dash/Agenda',
            [
                'agendas' => $agendas,
                'orgs' => Org::all(),
                'min_date' => $tapel[0].'-07-01',
                'max_date' => $tapel[1].'-06-30',
            ]
        );
    }

    public function daftar(Request $request, $id)
    {
        try {
            $agenda = Agenda::whereId($id)->first();
            return Inertia::render(
                'Front/DaftarAgenda',
                [
                    'agenda' => $agenda,
                    'appName' => \config('app.name'),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
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
                    'pelaksana' => $data['pelaksana'] ?? 'admin',
                    'mulai' => $data['mulai'],
                    'tipe' => $data['tipe'],
                    'selesai' => $data['selesai'],
                    'deskripsi' => $data['deskripsi'],
                    'is_libur' => $data['tipe'] == 'libur',
                    'warna' => $data['tipe'] == 'libur' ? 'red' : 'blue',
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
