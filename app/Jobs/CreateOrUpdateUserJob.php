<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateOrUpdateUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siswa;
    /**
     * Create a new job instance.
     */
    public function __construct(Siswa $siswa)
    {
        $this->siswa = $siswa;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user_siswa = User::updateOrCreate(
            [
                "name" => $this->siswa->nisn,
            ],
            [
                "password" => Hash::make($this->siswa->nisn),
                "email" =>
                    $this->siswa->email &&
                    ($this->siswa->email !== "null" ||
                        $this->siswa->email === null)
                        ? $this->siswa->email
                        : $this->siswa->nisn . "@e.mail",
                "userable_id" => $this->siswa->id,
                "userable_type" => Siswa::class,
            ]
        );

        $user_siswa->assignRole("siswa");
    }
}
