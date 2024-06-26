<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup Database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = "backup-guru-" . Carbon::now()->format('Y-m-d_His') . ".gz";
        $command = "mysqldump --user=" . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " siswas --where=\"sekolah_id='20518848'\"" . " | gzip > " . \storage_path() . "/app/backup/" . $filename;

        $returnVar = "Testing";
        $output = NULL;

        exec($command, $output, $returnVar);
    }
}
