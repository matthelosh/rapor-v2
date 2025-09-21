<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BukuIndukGenerateService;
use App\Models\Sekolah;

class GenerateBukuIndukCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bukuinduk:generate
                            {--sekolah= : NPSN Sekolah (opsional)}
                            {--tanggal-masuk= : Tanggal masuk default (Y-m-d)}
                            {--asal-sekolah= : Asal sekolah default}
                            {--status=aktif : Status default}
                            {--custom-no-induk : Gunakan nomor induk custom}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate buku induk otomatis dari data siswa yang ada';

    public function handle()
    {
        $this->info('🚀 Memulai generate buku induk otomatis...');

        $generateService = new BukuIndukGenerateService();

        // Get options
        $sekolahNpsn = $this->option('sekolah');
        $options = [
            'tanggal_masuk' => $this->option('tanggal-masuk'),
            'asal_sekolah' => $this->option('asal-sekolah'),
            'status' => $this->option('status'),
            'use_custom_no_induk' => $this->option('custom-no-induk'),
            'keterangan' => 'Generated via artisan command'
        ];

        if ($sekolahNpsn) {
            // Generate untuk sekolah tertentu
            $this->generateForSekolah($generateService, $sekolahNpsn, $options);
        } else {
            // Generate untuk semua sekolah
            $this->generateForAllSekolah($generateService, $options);
        }

        $this->info('✅ Generate buku induk selesai!');
    }

    private function generateForSekolah($service, $npsn, $options)
    {
        $sekolah = Sekolah::where('npsn', $npsn)->first();

        if (!$sekolah) {
            $this->error("❌ Sekolah dengan NPSN {$npsn} tidak ditemukan");
            return;
        }

        $this->info("📚 Generate untuk: {$sekolah->nama}");

        try {
            $results = $service->generateForSekolah($npsn, $options);

            $this->table(
                ['Metric', 'Count'],
                [
                    ['Berhasil', $results['success']],
                    ['Dilewati', $results['skipped']],
                    ['Error', $results['errors']]
                ]
            );

            if ($results['errors'] > 0) {
                $this->warn("⚠️  Ada {$results['errors']} error saat generate");

                if ($this->confirm('Tampilkan detail error?')) {
                    foreach ($results['details'] as $detail) {
                        if ($detail['status'] === 'error') {
                            $this->error("❌ {$detail['siswa']} ({$detail['nisn']}): {$detail['message']}");
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
        }
    }

    private function generateForAllSekolah($service, $options)
    {
        $sekolahs = Sekolah::all();

        if ($sekolahs->isEmpty()) {
            $this->error('❌ Tidak ada sekolah yang ditemukan');
            return;
        }

        $this->info("📚 Generate untuk {$sekolahs->count()} sekolah");

        $totalSuccess = 0;
        $totalErrors = 0;

        $progress = $this->output->createProgressBar($sekolahs->count());
        $progress->start();

        foreach ($sekolahs as $sekolah) {
            try {
                $results = $service->generateForSekolah($sekolah->npsn, $options);
                $totalSuccess += $results['success'];
                $totalErrors += $results['errors'];
            } catch (\Exception $e) {
                $this->error("\n❌ Error pada {$sekolah->nama}: " . $e->getMessage());
                $totalErrors++;
            }

            $progress->advance();
        }

        $progress->finish();

        $this->info("\n\n📊 RINGKASAN HASIL:");
        $this->table(
            ['Total Sekolah', 'Buku Induk Dibuat', 'Total Error'],
            [[$sekolahs->count(), $totalSuccess, $totalErrors]]
        );
    }
}
