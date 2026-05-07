<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Guru;
use App\Models\User;

class OperatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sekolahs = Sekolah::all();
        foreach ($sekolahs as $sekolah) {
            $guru = Guru::updateOrCreate(
                [
                'nip' => $sekolah->npsn,
                'nama' => 'OPS '.$sekolah->nama,

                ],
                [
                'jk' => 'Laki-laki',
                'alamat' => $sekolah->alamat,
                'hp' => $sekolah->telp ?? '-',
                'status' => 'p3k',
                'email' => $sekolah->npsn.'@raporsd.net',
                'agama' => 'Islam',
                'jabatan' => 'Ops'
                ]
            );

            $guru->sekolahs()->attach($sekolah->id);

            $user = User::updateOrCreate(
                [
                    'name' => $sekolah->npsn,
                    'password' => Hash::make($sekolah->npsn),
                    'email' => $guru->email,
                    'userable_type' => 'App\Models\Guru',
                    'userable_id' => $guru->id
                ]
            );

            $user->assignRole('ops');
        }
    }
}
