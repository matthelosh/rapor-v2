<?php

namespace Database\Seeders;

use App\Models\Semester;
use App\Models\Tapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($s = 0; $s < 2; $s++) {
            Semester::create([
                'kode' => $s + 1,
                'label' => $s + 1 == '1' ? 'Ganjil' : 'Genap',
                'deskripsi' => $s + 1 == '1' ? 'Semester Ganjil' : 'Semester Genap',
                'is_active' => false
            ]);
        }

        $sem = Semester::find(1);
        $sem->update(['is_active' => true]);

        $year = date('Y');
        for ($y = 0; $y < 5; $y++) {
            Tapel::create([
                'kode' => (substr($year, 2, 2) . substr($year + 1, 2, 2)),
                'label' => $year . '/' . $year + 1,
                'deskripsi' => 'Tahun Pelajaran ' . $year . '/' . $year + 1,
                'is_active' => false
            ]);
            $year++;
        }

        $tapel = Tapel::find(1);
        $tapel->update(['is_active' => true]);
    }
}
