<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiClient;
use Illuminate\Support\Str;

class ApiClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = [
            [
                'client_id' => 'sekolah_test_app',
                'client_secret' => Str::random(64),
            ],
            [
                'client_id' => 'presensi_siswa_app',
                'client_secret' => Str::random(64),
            ],
            [
                'client_id' => 'asesmen_app',
                'client_secret' => Str::random(64),
            ],
        ];

        $createdClients = [];
        foreach ($clients as $client) {
            $createdClient = ApiClient::create($client);
            $createdClients[] = $createdClient;
        }

        $this->command->info('API Clients seeded successfully!');
        $this->command->info('Client Credentials:');
        foreach ($createdClients as $client) {
            $this->command->info("Client ID: {$client->client_id}");
            $this->command->info("Client Secret: {$client->client_secret}");
            $this->command->info('----------------------------------------');
        }
    }
}
