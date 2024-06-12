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
                'can' => $user ? $user->getPermissionsViaRoles()->pluck('name') : null,

                
            ],
            'flash' => [
                "message" => fn() => $request->session()->get('message'),
            ],
            'periode' => $this->periode(),
        ];
        if ($user) {{
            $mapels = ['guru_agama','guru_pjok', 'guru_inggris'];
            $datas['sekolahs'] = $this->sekolahs($user);
            
            if ($user->hasRole('guru_kelas')) {

                $datas['rombels'] = Rombel::where('guru_id', $user->userable->id)->with('siswas')->get();
            } elseif(in_array($user->getRoleNames()[0], $mapels)) {
                
            }
        }}

        return $datas;
    }

    private function sekolahs($user) {
        if ($user->hasRole('admin')) {
            return Sekolah::all();
        } else {
            return $user->userable->sekolahs ?? null;
        }
    }

    private function user($user) {
        $account = User::where('id', $user->id)->with('roles.permissions','userable')->first();
        return $account;
    }

    private function periode() {
        $tapel = Tapel::whereIsActive(true)->first();
        $semester = Semester::whereisActive(true)->first();
        $periode = [
            "tapel" => $tapel,
            "semester" => $semester
        ];
        return $tapel !== null ? $periode : null;
    }
}
