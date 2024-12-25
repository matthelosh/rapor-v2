<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Rombel;

class RombelController extends Controller
{
    public function index(Request $request)
    {
        try {
            $npsn = $request->query('npsn') ?? null;
            $rombels = Rombel::with('siswas')->get();
            $results = null;
            if ($npsn !== null) {
                $results = $rombels->filter(
                    function ($rombel) use ($npsn) {
                        return $rombel->sekolah_id == $npsn;
                    }
                );
            } else {
                $results = $rombels;
            }
            return response()->json(['rombels' => $results], 200);

        } catch(\Exception $e) {
            return response()->json(
                [
                    'error' => $e->getMessage()
                ],
                $e->getCode()
            );
        }
    }
}
