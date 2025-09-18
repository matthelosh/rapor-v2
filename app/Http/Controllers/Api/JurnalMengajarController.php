<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Periode;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JurnalMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JurnalMengajarController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->userable;
        $jurnals = JurnalMengajar::where('guru_id', $guru->nip)
            ->with(['rombel', 'mapel'])
            ->get();

        return response()->json([
            'jurnals' => $jurnals,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'tapel' => 'nullable|string',
            'semester' => 'nullable|string',
            'rombel_id' => 'required|string',
            'mapel_id' => 'required|string',
            'keterangan' => 'nullable|string',
            'materi' => 'nullable|string',
            'tp' => 'nullable|string',
            'elemen' => 'nullable|string',
            'foto_kegiatan' => 'nullable|string',
            'dokumen' => 'nullable|string',
        ]);

        $jurnal = JurnalMengajar::create([
            'guru_id' => $request->guru_id,
            'tapel' => $request->tapel ?? Periode::tapel()->kode,
            'semester' => $request->semester ?? Periode::semester()->kode,
            'rombel_id' => $request->rombel_id,
            'mapel_id' => $request->mapel_id,
            'keterangan' => $request->keterangan,
            'materi' => $request->materi,
            'tp' => $request->tp,
            'elemen' => $request->elemen,
            'foto_kegiatan' => $request->foto_kegiatan,
            'dokumen' => $request->dokumen,
        ]);

        return response()->json([
            'message' => 'Jurnal mengajar berhasil dibuat',
            'jurnal' => $jurnal,
        ], 201);
    }

    public function show($id)
    {
        $guru = Auth::user()->userable;
        $jurnal = JurnalMengajar::where('guru_id', $guru->nip)
            ->where('id', $id)
            ->with(['rombel', 'mapel'])
            ->firstOrFail();

        return response()->json([
            'jurnal' => $jurnal,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tapel' => 'required|string',
            'semester' => 'required|string',
            'rombel_id' => 'required|string',
            'mapel_id' => 'required|string',
            'keterangan' => 'nullable|string',
            'materi' => 'nullable|string',
            'tp' => 'nullable|string',
            'elemen' => 'nullable|string',
            'foto_kegiatan' => 'nullable|string',
            'dokumen' => 'nullable|string',
        ]);

        $guru = Auth::user()->userable;
        $jurnal = JurnalMengajar::where('guru_id', $guru->nip)
            ->where('id', $id)
            ->firstOrFail();

        $jurnal->update($request->only([
            'tapel', 'semester', 'rombel_id', 'mapel_id', 'keterangan', 'materi', 'tp', 'elemen', 'foto_kegiatan', 'dokumen'
        ]));

        return response()->json([
            'message' => 'Jurnal mengajar berhasil diperbarui',
            'jurnal' => $jurnal,
        ]);
    }

    public function destroy($id)
    {
        $guru = Auth::user()->userable;
        $jurnal = JurnalMengajar::where('guru_id', $guru->nip)
            ->where('id', $id)
            ->firstOrFail();

        $jurnal->delete();

        return response()->json([
            'message' => 'Jurnal mengajar berhasil dihapus',
        ]);
    }
}
