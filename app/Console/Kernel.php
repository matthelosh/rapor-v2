<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [Commands\GenerateBukuIndukCommand::class];
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command("backup:run --only-db")->daily()->at("04:30");
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . "/Commands");
        if (file_exists(base_path("routes/console.php"))) {
            require base_path("routes/console.php");
        }
    }
}
