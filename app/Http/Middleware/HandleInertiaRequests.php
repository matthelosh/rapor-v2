<?php

namespace App\Http\Middleware;

use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $datas = [
            ...parent::share($request),
            'auth' => [
                'user' => $user ?? null,
                'roles' => $user ? $user->getRoleNames() : null,
                'can' => $user ? $user->getAllPermissions()->pluck('name') : null,


            ],
            'flash' => [
                "message" => fn() => $request->session()->get('message'),
            ],
            'periode' => $this->periode(),
            'app_env' => env('APP_ENV')
        ];
        if ($user) { {
                $guru_mapels = ['guru_agama', 'guru_pjok', 'guru_inggris'];
                $datas['sekolahs'] = $this->sekolahs($user);
                $mapels = $this->sekolahs($user)[0]->mapels;
                // $data['mapels'] = $mapels;
                // dd($this->sekolahs($user)[0]->mapels->filter(fn($mapel) => $mapel->kode == 'pabp'));
                if ($user->hasRole('guru_kelas')) {
                    $data['mapels'] = $mapels->filter(fn($mapel) => !\in_array($mapel->kode, ['pabp', 'pjok', 'bing']));
                    $datas['rombels'] = Rombel::where('guru_id', $user->userable->id)->with('siswas.ortus', 'sekolah')->get();
                } elseif (in_array($user->getRoleNames()[0], $guru_mapels)) {
                    if ($user->hasRole('guru_agama')) {
                        // $data['mapels'] = $mapels->filter(fn($mapel) => $mapel->kode == 'pabp');
                        $data['mapels'] = $mapels;
                    } elseif ($user->hasRole('guru_pjok')) {
                        $data['mapels'] = $mapels->filter(fn($mapel) => $mapel->kode == 'pjok');
                    } elseif ($user->hasRole('guru_inggris')) {
                        $data['mapels'] = $mapels->filter(fn($mapel) => $mapel->kode == 'bing');
                    }
                }

                if ($user->hasRole('ops')) {
                    $datas['rombels'] = Rombel::where('sekolah_id', $this->sekolahs($user)[0]->npsn)
                        ->where('tapel', $this->periode()['tapel']['kode'])
                        ->get();
                }
            }
        }

        return $datas;
    }

    private function sekolahs($user)
    {
        // if ($user->hasRole('admin') || $user->hasRole('superadmin') ) {
        $role = $user->getRoleNames()[0];
        $tapel = $this->periode()['tapel']->kode;
        if (\in_array($role, ['superadmin', 'admin', 'admin_tp'])) {
            return Sekolah::with('mapels.tps', 'ks', 'ekskuls')->get();
        } elseif ($role == 'ops') {
            return Sekolah::where('id', $user->userable->sekolahs[0]->id)
                ->with('mapels', function ($q) {
                    $q->orderBy('id', 'ASC');
                    $q->with('tps');
                })
                ->with([
                    'rombels' => function ($r) use ($tapel) {
                        $r->whereTapel($tapel);
                    }
                ])
                ->with('ks', 'ekskuls')->get();
        } else {
            return Sekolah::where('id', $user->userable->sekolahs[0]->id)
                ->with('ks', 'ekskuls')
                ->with([
                    'mapels' => function ($m) use ($role) {
                        if ($role == 'guru_kelas') {
                            $m->whereNotIn('kode', ['pabp', 'pjok', 'bing']);
                        } elseif ($role == 'guru_agama') {
                            $m->where('kode', 'pabp');
                        } elseif ($role == 'guru_pjok') {
                            $m->where('kode', 'pjok');
                        } elseif ($role == 'guru_inggris') {
                            $m->where('kode', 'bing');
                        }
                    },
                    'rombels' => function ($r) use ($tapel) {
                        $r->where('tapel', $tapel);
                    }
                ])
                ->get() ??
                null;
        }
    }

    private function user($user)
    {
        $account = User::where('id', $user->id)->with('roles.permissions', 'userable')->first();
        return $account;
    }

    private function periode()
    {
        $tapel = Tapel::whereIsActive(true)->first();
        $semester = Semester::whereisActive(true)->first();
        $periode = [
            "tapel" => $tapel,
            "semester" => $semester
        ];
        return $tapel !== null ? $periode : null;
    }
}
