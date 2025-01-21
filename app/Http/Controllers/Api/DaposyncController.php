<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Sekolah;
use App\Models\Siswa;
use App\Models\Tapel;
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

    public function syncGuru(Request $request)
    {
        try {
            $datas = $request->rows;
            $npsn = $request->npsn;
            $sekolah = Sekolah::whereNpsn($npsn)->first();
            foreach ($datas as $data) {
                $guru = Guru::updateOrCreate(
                    [
                        'nip' => $data['nip'],
                    ],
                    [
                        'dapo_id' => $data['ptk_id'],
                        'nuptk' => $data['nuptk'],
                        'gelar_depan' => '',
                        'nama' => $data['nama'],
                        'gelar_belakang' => '',
                        'jk' => $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
                        'alamat' => '',
                        'hp' => '-',
                        'status' => !in_array($data['status_kepegawaian_id_str'], ['PNS', 'PPPK']) ? 'gtt' : ($data['status_kepegawaian_id_str'] == 'PNS' ? 'pns' : 'p3k'),
                        'email' => '-',
                        'foto' => null,
                        'agama' => $data['agama_id_str'],
                        'pangkat' => $data['pangkat_golongan_terakhir'],
                        'jabatan' => $data['jabatan_ptk_id_str'] ?? null
                    ]
                );

                $guru->sekolahs()->attach($sekolah->id);
            }
            return response()->json(['success' => true, 'message' => 'Data Guru telah disinkronkan', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function syncRombel(Request $request)
    {
        try {
            $datas = $request->rows;
            $npsn = $request->npsn;
            $sekolah = Sekolah::whereNpsn($npsn)->first();
            $dapo = '';

            foreach ($datas as $data) {
                $ruang = substr($data['id_ruang_str'], -2);
                $kode = $npsn . '-' . $this->tapel()->kode . '-' . $ruang;
                $fase = $data['tingkat_pendidikan_id'] < 3 ? 'A' : ($data['tingkat_pendidikan_id'] < 5 ? 'B' : 'C');
                $guru = Guru::where('dapo_id', $data['ptk_id'])->first();
                $rombel = Rombel::updateOrCreate(
                    [
                        'dapo_id' => $data['rombongan_belajar_id']
                    ],
                    [
                        'tapel' => $this->tapel()->kode,
                        'kode' => $kode,
                        'label' => $data['nama'],
                        'fase' => $fase,
                        'tingkat' => $data['tingkat_pendidikan_id'],
                        'sekolah_id' => $npsn,
                        'guru_id' => $guru->id,
                        'is_active' => true
                    ]
                );
                $dapo = $data['rombongan_belajar_id'];
            }

            return response()->json([
                'status' => 'Ok',
                'message' => 'Rombel disimpan',
                'data' => $dapo
            ], 200);
        } catch (\Throwable $th) {

            return \response()->json([
                'status' => 'Failed',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function syncSiswa(Request $request)
    {
        try {
            $datas = $request->rows;
            $npsn = $request->npsn;
            $sekolah = Sekolah::whereNpsn($npsn)->first();

            foreach ($datas as $data) {
                $rombel = Rombel::where('dapo_id', $data['rombongan_belajar_id'])->first();
                $siswa = Siswa::updateOrCreate(
                    [
                        'dapo_id' => $data['peserta_didik_id'],
                    ],
                    [
                        'nisn' => $data['nisn'] ?? $npsn . rand(10, 99),
                        'nis' => $data['nipd'],
                        'nik' => $data['nik'],
                        'nama' => $data['nama'],
                        'tempat_lahir' => $data['tempat_lahir'],
                        'tanggal_lahir' => $data['tanggal_lahir'],
                        'jk' => $data['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
                        'alamat' => '',
                        'rt' => '-',
                        'rw' => '-',
                        'desa' => '-',
                        'kecamatan' => '-',
                        'kode_pos' => '-',
                        'kabupaten' => '-',
                        'provinsi' => '-',
                        'hp' => $data['nomor_telepon_seluler'] ?? '-',
                        'email' => $data['email'] ?? $data['nisn'] . '@raporsd.net',
                        'foto' => null,
                        'agama' => $data['agama_id_str'],
                        'angkatan' => \substr($data['tanggal_masuk_sekolah'], 0, 4),
                        'sekolah_id' => $npsn,
                        'status' => 'aktif'
                    ]
                );

                $siswa->rombels()->attach($rombel->id);
            }

            return response()->json([
                'status' => 'Ok',
                'message' => 'Siswa disimpan',

            ], 200);
        } catch (\Throwable $th) {

            return \response()->json([
                'status' => 'Failed',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function tapel()
    {
        return Tapel::whereIsActive('1')->first();
    }
}
