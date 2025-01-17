<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class DaposyncController extends Controller
{
    public function syncSekolah(Request $request)
    {
        try {
            $data = $request->rows;
            $sekolah = Sekolah::where('npsn', $data['npsn'])->first();
            $sekolah->update([
                'npsn' => $data['npsn'],
                'nama' => $data['nama'],
                'alamat' => $data['alamat_jalan'] . ' ' . $data['dusun'] . ' RT. ' . $data['rt'] . ' RW. ' . $data['rw'],
                'desa' => $data['desa_kelurahan'],
                'kecamatan' => $data['kecamatan'],
                'kabupaten' => $data['kabupaten_kota'],
                'kode_pos' => $data['kode_pos'],
                'telp' => $data['nomor_telepon'],
                'email' => $data['email'],
                'website' => $data['website'],
            ]);
            return response()->json(['success' => true, 'message' => 'Data Sekolah telah disinkronkan', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
