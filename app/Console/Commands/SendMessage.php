<?php

namespace App\Console\Commands;

use App\Events\SiswaMulaiAsesmen;
use App\Models\User;
use Illuminate\Console\Command;
use Laravel\Prompts\text;

use function Laravel\Prompts\text;

class SendMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending a message';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $message = text("Masukkan pesan");
        $user = User::whereName('admin')->first();
        SiswaMulaiAsesmen::dispatch($user, $message);
    }
}
