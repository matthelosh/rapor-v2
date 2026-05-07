<?php

namespace App\Services;

use App\Models\BukuInduk;
use App\Models\Siswa;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuIndukGenerateService
{
    public function generateForSekolah($sekolah_npsn, $options = [])
    {
        $results = [
            'success' => 0,
            'skipped' => 0,
            'errors' => 0,
            'details' => []
        ];

        try {
            DB::beginTransaction();

            // Ambil siswa yang belum memiliki buku induk
            $siswas = Siswa::where('sekolah_id', $sekolah_npsn)
                ->whereDoesntHave('bukuInduk')
                ->where('status', 'aktif')
                ->get();

            $sekolah = Sekolah::where('npsn', $sekolah_npsn)->first();

            foreach ($siswas as $siswa) {
                try {
                    $bukuIndukData = $this->prepareBukuIndukData($siswa, $sekolah, $options);

                    BukuInduk::create($bukuIndukData);

                    $results['success']++;
                    $results['details'][] = [
                        'siswa' => $siswa->nama,
                        'nisn' => $siswa->nisn,
                        'status' => 'success',
                        'message' => 'Buku induk berhasil dibuat'
                    ];

                } catch (\Exception $e) {
                    $results['errors']++;
                    $results['details'][] = [
                        'siswa' => $siswa->nama,
                        'nisn' => $siswa->nisn,
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];

                    Log::error('Error generating buku induk for siswa: ' . $siswa->nisn, [
                        'error' => $e->getMessage(),
                        'siswa' => $siswa->toArray()
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $results;
    }

    public function generateForSiswa($siswa_nisn, $options = [])
    {
        $siswa = Siswa::where('nisn', $siswa_nisn)->first();

        if (!$siswa) {
            throw new \Exception('Siswa tidak ditemukan');
        }

        if ($siswa->bukuInduk) {
            throw new \Exception('Siswa sudah memiliki buku induk');
        }

        $sekolah = Sekolah::where('npsn', $siswa->sekolah_id)->first();
        $bukuIndukData = $this->prepareBukuIndukData($siswa, $sekolah, $options);

        return BukuInduk::create($bukuIndukData);
    }

    private function prepareBukuIndukData($siswa, $sekolah, $options = [])
    {
        // Estimasi tanggal masuk berdasarkan angkatan atau tahun ajaran
        $tanggalMasuk = $this->estimateTanggalMasuk($siswa);

        return [
            'siswa_id' => $siswa->nisn,
            'no_induk' => $options['use_custom_no_induk'] ?? false
                ? $this->generateNoInduk($siswa, $sekolah)
                : $siswa->nis,
            'tanggal_masuk' => $options['tanggal_masuk'] ?? $tanggalMasuk,
            'asal_sekolah' => $options['asal_sekolah'] ?? $this->guessAsalSekolah($siswa),
            'status' => $options['status'] ?? 'aktif',
            'keterangan' => $options['keterangan'] ?? 'Generated otomatis dari data siswa',
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    private function estimateTanggalMasuk($siswa)
    {
        // Jika ada data angkatan, gunakan itu
        if ($siswa->angkatan) {
            return Carbon::createFromFormat('Y', $siswa->angkatan)->startOfYear()->addMonths(6); // Juli
        }

        // Estimasi berdasarkan umur (asumsi masuk SD umur 6-7 tahun)
        $tanggalLahir = Carbon::parse($siswa->tanggal_lahir);
        $estimasiMasuk = $tanggalLahir->copy()->addYears(6)->startOfYear()->addMonths(6);

        // Jika terlalu lama atau belum waktunya, gunakan tahun ajaran saat ini
        $sekarang = Carbon::now();
        if ($estimasiMasuk->year < ($sekarang->year - 6) || $estimasiMasuk->year > $sekarang->year) {
            $estimasiMasuk = $sekarang->month >= 7
                ? Carbon::create($sekarang->year, 7, 1)
                : Carbon::create($sekarang->year - 1, 7, 1);
        }

        return $estimasiMasuk->format('Y-m-d');
    }

    private function guessAsalSekolah($siswa)
    {
        // Logic untuk menebak asal sekolah berdasarkan data yang ada
        // Bisa dari pola nama, alamat, atau data lainnya

        $kecamatan = $siswa->kecamatan;
        $kabupaten = $siswa->kabupaten;

        // Contoh pattern untuk TK/PAUD
        return "TK/PAUD {$kecamatan}, {$kabupaten}";
    }

    private function generateNoInduk($siswa, $sekolah)
    {
        // Generate nomor induk custom jika NIS kosong
        $tahun = Carbon::now()->format('y');
        $counter = BukuInduk::whereYear('created_at', Carbon::now()->year)->count() + 1;

        return $tahun . str_pad($counter, 4, '0', STR_PAD_LEFT);
    }

    public function getPreview($sekolah_npsn, $limit = 10)
    {
        $siswas = Siswa::where('sekolah_id', $sekolah_npsn)
            ->whereDoesntHave('bukuInduk')
            ->where('status', 'aktif')
            ->limit($limit)
            ->get();

        $sekolah = Sekolah::where('npsn', $sekolah_npsn)->first();
        $preview = [];

        foreach ($siswas as $siswa) {
            $preview[] = [
                'siswa' => $siswa,
                'preview_data' => $this->prepareBukuIndukData($siswa, $sekolah)
            ];
        }

        return $preview;
    }
}
