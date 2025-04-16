<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pejabat;

class PejabatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pejabat::create([
        	'korwil' => 'SITI CHOLIMAH, S.Pd., M.M.',
        	'pangkat_korwil' => 'Pembina Tk. I',
        	'nip_korwil' => '196605051991032011',
        	'pengawas' => 'SUBARIYANTI, S.Pd.,M.Si.',
        	'pangkat_pengawas' => 'Pembina Utama Muda',
        	'nip_pengawas' => '196702061993042001',
        	'ppai' => 'M. KHOIRUL HUDA, S.Pd.,M.Pd.I',
        	'pangkat_ppai' => '',
        	'nip_ppai' => ''
        ]);
    }
}
