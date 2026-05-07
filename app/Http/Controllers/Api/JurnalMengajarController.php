<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Periode;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JurnalMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            // 'dokumen' => 'nullable|string',
        ]);

        if ($request->foto_kegiatan) {

            // $image = $request->file('foto_kegiatan');
            // $storeFoto = Storage::disk('s3')->putFileAs('pkgwagir/jurnal_mengajar/foto_kegiatan', $image, $image->getClientOriginalName() . '.' . $image->extension(), 'public');
            // dd($request->foto_kegiatan);
            $foto_url = $this->saveBase64File($request->foto_kegiatan, 'pkgwagir/jurnal_mengajar/foto_kegiatan', 'foto_kegiatan');
            // return response()->json(['foto' => $foto_url]);
        }
        if ($request->dokumen && $request->dokumen !== '' && $request->dokumen !== null && $request->dokumen !== 'undefined' && $request->dokumen !== 'null' ) {
            $dokumen_url = $this->saveBase64File($request->dokumen, 'pkgwagir/jurnal_mengajar/dokumen', 'dokumen');
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
            'foto_kegiatan' => isset($foto_url) && $foto_url ? $foto_url : null,
            'dokumen' => isset($dokumen_url) && $dokumen_url ? $dokumen_url : null,
        ]);

        return response()->json([
            'message' => 'Jurnal mengajar berhasil dibuat',
            'jurnal' => $jurnal,
        ], 201);
    }

    private function saveBase64File($base64String, $directory, $filename)
    {
        try {
            // Extract MIME type before splitting
            $mimeType = $this->getMimeTypeFromBase64($base64String);
            $extension = $this->getExtensionFromBase64($mimeType);

            // Remove data URL prefix if present
            $base64String = explode(',', $base64String)[1] ?? $base64String;

            $fileData = base64_decode($base64String);

            $filename = Str::uuid() . '.' . $extension;

            $path = $directory . '/' . $filename;
            Storage::disk('s3')->put($path, $fileData, 'public');

            return Storage::url($path);


        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getExtensionFromBase64($mimeType)
    {
        return match($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            default => 'bin'
        };

    }

    private function getMimeTypeFromBase64($base64String)
    {
        // Extract MIME type from data URL
        if (preg_match('/^data:([^;]+);base64,/', $base64String, $matches)) {
            return $matches[1];
        }

        // Default fallback
        return 'application/octet-stream';
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

        $updateData = $request->only([
            'rombel_id', 'mapel_id', 'keterangan', 'materi', 'tp', 'elemen'
        ]);

        if ($request->foto_kegiatan) {
            $foto_url = $this->saveBase64File($request->foto_kegiatan, 'pkgwagir/jurnal_mengajar/foto_kegiatan', 'foto_kegiatan');
            $updateData['foto_kegiatan'] = $foto_url;
        }

        if ($request->dokumen && $request->dokumen !== '' && $request->dokumen !== null && $request->dokumen !== 'undefined' && $request->dokumen !== 'null') {
            $dokumen_url = $this->saveBase64File($request->dokumen, 'pkgwagir/jurnal_mengajar/dokumen', 'dokumen');
            $updateData['dokumen'] = $dokumen_url;
        }

        $jurnal->update($updateData);

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
