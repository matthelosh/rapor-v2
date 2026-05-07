<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Rombel;

class RefactorGuruRombel extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rombels = Rombel::all();
        foreach ($rombels as $rombel) {
            $wali = Guru::whereId($rombel->guru_id)->first();
            DB::table("guru_rombel")->insert([
                "rombel_id" => $rombel->kode,
                "guru_id" => $wali->nip,
                "status" => "wali",
            ]);
        }
    }
}
