<?php

namespace App\Http\Middleware;

use App\Helpers\Periode;
use App\Models\Gugus;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Pejabat;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
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
    protected $rootView = "app";

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
        $datas = [
            ...parent::share($request),
            "auth" => [
                "user" => fn() => $request
                    ->user()
                    ?->only(["id", "name", "email"]),
                "roles" => fn() => $request->user()?->roles->pluck("name"),
                "can" => fn() => $request
                    ->user()
                    ?->getAllPermissions()
                    ->pluck("name"),
                "userable" => fn() => $request->user()?->userable
            ],
            "flash" => [
                "message" => fn() => $request->session()->get("message"),
                "error" => fn() => $request->session()->get("error"),
            ],
            "pejabat" => Pejabat::first(),
            "periode" => [
                "tapel" => Periode::tapel(),
                "semester" => Periode::semester(),
            ],
            "app_env" => env("APP_ENV"),
            "appUrl" => env("APP_URL"),
            "appName" => env("APP_NAME"),
        ];

        return $datas;
    }

}
