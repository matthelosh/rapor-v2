<?php

namespace App\Http\Controllers;

use App\Events\JawabanReceived;
use App\Models\Guru;
use App\Models\Sekolah;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function tesReverb()
    {
        \event(new JawabanReceived("Ini Hanya Tes"));
    }

    public function index(Request $request)
    {
        // \event(new JawabanReceived('Ini Jawaban'));
        $user = $request->user();
        $tapel = Tapel::whereIsActive(true)->pluck('kode')->first();
        $data = [];

        // Tes Dapodik
        // $response = Http::withOptions([
        //     'verify' => false,
        // ])
        //     ->withHeaders([
        //         'Authorization' => 'Bearer QteRgcGaC8TGojF',
        //         'Content-Type' => 'application/json'
        //     ])
        //     ->get('http://192.168.1.14:5774/WebService/getPengguna', ['npsn' => '20518848']);

        // dd($response);

        if (in_array($request->user()->getRoleNames()[0], ['superadmin', 'admin', 'admin_tp'])) {
            $data['sekolahs'] = Sekolah::with('ks', 'gurus')
                ->with([
                    'rombels' => function ($q) use ($tapel) {
                        $q->whereTapel($tapel);
                    },
                    'siswas' => fn($s) => $s->whereStatus('aktif'),
                ])->get();
        } elseif ($user->hasRole('ops')) {
            $data['sekolah'] = Sekolah::where('npsn', $user->userable->sekolahs[0]->npsn)
                ->with('gurus', 'ks')
                ->with('rombels', function ($q) use ($tapel) {
                    $q->where('tapel', $tapel);
                    $q->with('guru');
                    $q->with('siswas', fn($q) => $q->where('status', 'aktif'));
                })
                ->with('mapels')
                ->first();
        } elseif ($user->hasRole('guru_kelas')) {
            $data['sekolah'] = Sekolah::where('npsn', $user->userable->sekolahs[0]->npsn)
                ->with('mapels')
                ->first();
        } elseif ($user->hasRole(['guru_agama', 'guru_pjok', 'guru_inggris'])) {
            $guruId = $user->userable->id;
            $data['sekolahs'] = Sekolah::whereHas('gurus', function ($q) use ($guruId) {
                $q->where('gurus.id', $guruId);
            })
                ->with('rombels.siswas')
                ->get();
        }
        return Inertia::render('Dashboard', [
            'data' => $data,
        ]);
    }

    public function operator(Request $request)
    {
        $user = $request->user();
        $ops = [];
        if ($user->hasRole('admin') || $user->hasRole('superadmin')) {
            $ops = Guru::whereHas('user.roles', function ($q) {
                $q->where('name', 'ops');
            })->with('user.roles')->get();
        } else {
            $sekolah = Sekolah::where('npsn', $user->userable->sekolahs[0]->npsn)->first();
            $ops = Guru::whereHas('user.roles', function ($q) {
                $q->where('name', 'ops');
            })->where('nip', $sekolah->npsn)->with('user.roles')->get();
        }

        return Inertia::render('Dash/Ops', [
            'ops' => $ops,
        ]);
    }
}
