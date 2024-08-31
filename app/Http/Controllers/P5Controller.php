<?php

namespace App\Http\Controllers;

use App\Models\p5;
use App\Models\Proyek;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class P5Controller extends Controller
{
    public function home(Request $request)
    {
        try {
            return Inertia::render(
                'Dash/P5',
                [
                    "p5s" => p5::with('elemens.apds')->get(),
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function proyek(Request $request)
    {
        try {
            if ($request->user()->hasRole('ops')) {
                $proyeks = Proyek::whereTapel($this->tapel()->kode)->with('rombel')
                    ->where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                    ->get();
            } else {
                $rombel = Rombel::where('guru_id', $request->user()->userable->id)
                    ->where('tapel', $this->tapel()->kode)
                    ->first();
                $proyeks = Proyek::whereTapel($this->tapel()->kode)->with('rombel')
                    ->whereRombelId($rombel->kode)
                    ->get();
            }
            return Inertia::render(
                'Dash/P5/Proyek',
                [
                    'proyeks' => $proyeks,
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function nilai(Request $request)
    {
        try {
            $rombel = Rombel::where('guru_id', $request->user()->userable->id)
                ->where('tapel', $this->tapel()->kode)
                ->with('siswas')
                ->first();
            $proyeks = Proyek::where('sekolah_id', $request->user()->userable->sekolahs[0]->npsn)
                ->where('tapel', $this->tapel()->kode)
                ->where('semester', $this->semester()->kode)
                ->whereNot('status', 'selesai')
                ->get();
            return Inertia::render(
                'Dash/P5Nilai',
                [
                    "p5s" => p5::with('elemens.apds')->get(),
                    'proyeks' => $proyeks,
                    'rombel' => $rombel
                ]
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function tapel()
    {
        return Tapel::whereIsActive(1)->first();
    }
    private function semester()
    {
        return Semester::whereIsActive(1)->first();
    }
}
