<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Periode;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JurnalMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'foto_kegiatan' => 'nullable|file|mimetypes:image/*|max:10240',
            'dokumen' => 'nullable|file|mimetypes:image/*,application/pdf:max:10240',
            // 'dokumen' => 'nullable|string',
        ]);

        if ($request->file('foto_kegiatan')) {

            $image = $request->file('foto_kegiatan');
            $storeFoto = Storage::disk('s3')->putFileAs('pkgwagir/jurnal_mengajar/foto_kegiatan', $image, $image->getClientOriginalName() . '.' . $image->extension(), 'public');
            $foto_url = Storage::url($storeFoto);
        }
        if ($request->file('dokumen')) {

            $dokumen = $request->file('dokumen');
            $storeDokumen = Storage::disk('s3')->putFileAs('pkgwagir/jurnal_mengajar/dokumen', $dokumen, $dokumen->getClientOriginalName() . '.' . $dokumen->extension(), 'public');
            $dokumen_url = Storage::url($storeDokumen);
        }

        $guru = Auth::user()->userable;
        $jurnal = JurnalMengajar::create([
            'guru_id' => $guru->nip,
            'tapel' => $request->tapel ?? Periode::tapel()->kode,
            'semester' => $request->semester ?? Periode::semester()->kode,
            'rombel_id' => $request->rombel_id,
            'mapel_id' => $request->mapel_id,
            'keterangan' => $request->keterangan,
            'materi' => $request->materi,
            'tp' => $request->tp,
            'elemen' => $request->elemen,
            'foto_kegiatan' => isset($foto_url) && $storeFoto ? $foto_url : null,
            'dokumen' => isset($dokumen_url) && $dokumen_url ? $dokumen_url : null,
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
