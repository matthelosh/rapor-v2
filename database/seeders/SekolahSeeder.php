<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::unprepared(\storage_path().'/backup/)
        $sekolahs = [
            ['npsn' => '20518710', 'nama' => 'SD I SUNAN AMPEL', 'logo' => null, 'alamat' => 'JL. KATU NO 58 MENDALANKULON',                   'desa' => 'MENDALANWANGI', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '881026674264',     'email' => 'sunanampelsdi@gmail.com',               'website' => 'http://sdislamsunanampel.mysch.id',   'ks_id' => null,],
            ['npsn' => '20518848', 'nama' => 'SD NEGERI 1 BEDALISODO', 'logo' => null, 'alamat' => 'JL. RAYA SENGON NO. 293',                  'desa' => 'DALISODO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdn.bedali01@gmail.com',                'website' => 'https://sdn1-bedalisodo.sch.id',      'ks_id' => null,],
            ['npsn' => '20518454', 'nama' => 'SD NEGERI 1 GONDOWANGI', 'logo' => null, 'alamat' => 'JL. RAYA GONDOWANGI NO. 125',              'desa' => 'GONDOWANGI',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdngondowangi1@gmail.com',              'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518384', 'nama' => 'SD NEGERI 1 JEDONG', 'logo' => null, 'alamat' => 'JL. RAYA JEDONG NO. 146',                      'desa' => 'JEDONG',        'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '0341-890809',      'email' => 'sdn1jedong@gmail.com',                  'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517168', 'nama' => 'SD NEGERI 1 MENDALANWANGI', 'logo' => null, 'alamat' => 'JL. RAYA MENDALANWANGI 85, SEKARPUTIH', 'desa' => 'MENDALANWANGI', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '341805698',        'email' => 'sdnmendalanwangi01.wagir@gmail.com',    'website' => 'http://sdnmendalanwangi01.sch.id',    'ks_id' => null,],
            ['npsn' => '20517114', 'nama' => 'SD NEGERI 1 PANDANLANDUNG', 'logo' => null, 'alamat' => 'JL. KALIJOGO NO 55',                    'desa' => 'PANDANLANDUNG', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '341536966',        'email' => 'sdnpandansatu@gmail.com',               'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517118', 'nama' => 'SD NEGERI 1 PANDANREJO', 'logo' => null, 'alamat' => 'JL. RAYA PANDANSARI NO 75',                'desa' => 'PANDANREJO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnpandanrejo01_wagir@yahoo.co.id',     'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517295', 'nama' => 'SD NEGERI 1 PARANGARGO', 'logo' => null, 'alamat' => 'JL. RAYA PARANGARGO GG. FLAMBOYAN NO 59',  'desa' => 'PARANGARGO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnparangargosatu@yahoo.co.id',         'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517277', 'nama' => 'SD NEGERI 1 PETUNGSEWU', 'logo' => null, 'alamat' => 'JL. RAYA PETUNGSEWU NO. 58',               'desa' => 'PETUNGSEWU',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'petungsewu1@gmail.com',                 'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516949', 'nama' => 'SD NEGERI 1 SIDORAHAYU', 'logo' => null, 'alamat' => 'JL. RAYA SIDORAHAYU',                      'desa' => 'SIDORAHAYU',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdrh01@yahoo.co.id',                    'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516858', 'nama' => 'SD NEGERI 1 SITIREJO', 'logo' => null, 'alamat' => 'DUSUN BUWEK',                                'desa' => 'SITIREJO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnsitirejodua@yahoo.co.id',            'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516859', 'nama' => 'SD NEGERI 1 SUKODADI', 'logo' => null, 'alamat' => 'JL. RAYA GENDERAN',                          'desa' => 'SUKODADI',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnsukodadi01@gmail.com',               'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '69772577', 'nama' => 'SD NEGERI 1 SUMBERSUKO', 'logo' => null, 'alamat' => 'JL. RAYA KENONGO NO 01',                   'desa' => 'SUMBERSUKO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnegerisumbersuko1@gmail.com',         'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518471', 'nama' => 'SD NEGERI 2 GONDOWANGI', 'logo' => null, 'alamat' => 'DUSUN WILOSO',                             'desa' => 'GONDOWANGI',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdn_gondowangi02wagirmalang@yahoo.co.id', 'website' => '-',                                 'ks_id' => null,],
            ['npsn' => '20518385', 'nama' => 'SD NEGERI 2 JEDONG', 'logo' => null, 'alamat' => 'JL. RAYA JEDONG NO 270',                       'desa' => 'JEDONG',        'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnjedong02@yahoo.co.id',               'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517169', 'nama' => 'SD NEGERI 2 MENDALANWANGI', 'logo' => null, 'alamat' => 'DUSUN MENDALAN WETAN',                  'desa' => 'MENDALANWANGI', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnmendalanwangi02@yahoo.com',          'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517115', 'nama' => 'SD NEGERI 2 PANDANLANDUNG', 'logo' => null, 'alamat' => 'JL. GAPURA NO 121',                     'desa' => 'PANDANLANDUNG', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '341589200',        'email' => 'sdnpandanlandung02@yahoo.com',          'website' => 'http://sdnpandanlandung02.com',       'ks_id' => null,],
            ['npsn' => '20517121', 'nama' => 'SD NEGERI 2 PANDANREJO', 'logo' => null, 'alamat' => 'JL. RAYA NGRAGI',                          'desa' => 'PANDANREJO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdn2pandanrejowgr@gmail.com',           'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518723', 'nama' => 'SD NEGERI 2 PARANGARGO', 'logo' => null, 'alamat' => 'JL. JUWETMANTING NO 2',                    'desa' => 'PARANGARGO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '6281315372374',    'email' => 'sdnparangargo2@gmail.com',              'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517278', 'nama' => 'SD NEGERI 2 PETUNGSEWU', 'logo' => null, 'alamat' => 'JL. RAYA CODO',                            'desa' => 'PETUNGSEWU',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnduapetungsewu@gmail.com',            'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516950', 'nama' => 'SD NEGERI 2 SIDORAHAYU', 'logo' => null, 'alamat' => 'JL. GATOTKACA NO 32',                      'desa' => 'SIDORAHAYU',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sidorahayu02wagir@yahoo.co.id',         'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516845', 'nama' => 'SD NEGERI 2 SITIREJO', 'logo' => null, 'alamat' => 'DUSUN MBUWEK',                               'desa' => 'SITIREJO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnsitirejodua@yahoo.co.id',            'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516860', 'nama' => 'SD NEGERI 2 SUKODADI', 'logo' => null, 'alamat' => 'JL. RAYA JAMURAN NO 46',                     'desa' => 'SUKODADI',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdn_sukodadi02@yahoo.co.id',            'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516971', 'nama' => 'SD NEGERI 2 SUMBERSUKO', 'logo' => null, 'alamat' => 'DUSUN SUMBERPANG LOR',                     'desa' => 'SUMBERSUKO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnsumbersuko02@ymail.com',             'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518850', 'nama' => 'SD NEGERI 3 BEDALISODO', 'logo' => null, 'alamat' => 'JL. COBAN GLOTAK',                         'desa' => 'DALISODO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdn_bedali03@yahoo.com',                'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518472', 'nama' => 'SD NEGERI 3 GONDOWANGI', 'logo' => null, 'alamat' => 'JL. DAWUHAN NO 9',                         'desa' => 'GONDOWANGI',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '341525676',        'email' => 'sdngondowangi3@gmail.com',              'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518386', 'nama' => 'SD NEGERI 3 JEDONG', 'logo' => null, 'alamat' => 'DUKUH JATEN',                                  'desa' => 'JEDONG',        'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnjedong03_wagir@yahoo.co.id',         'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20517170', 'nama' => 'SD NEGERI 3 MENDALANWANGI', 'logo' => null, 'alamat' => 'DUSUN DARUNGAN',                        'desa' => 'MENDALANWANGI', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnmendalanwangi03wagirmalang@gmail.com', 'website' => '-',                                 'ks_id' => null,],
            ['npsn' => '20517142', 'nama' => 'SD NEGERI 3 PANDANLANDUNG', 'logo' => null, 'alamat' => 'JL. GUNUNGJATI NO 210',                 'desa' => 'PANDANLANDUNG', 'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'pandanlandungtiga@gmail.com',           'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516951', 'nama' => 'SD NEGERI 3 SIDORAHAYU', 'logo' => null, 'alamat' => 'JL. RAYA SIDORAHAYU',                      'desa' => 'SIDORAHAYU',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdn3sidorahayu@gmail.com',              'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516846', 'nama' => 'SD NEGERI 3 SITIREJO', 'logo' => null, 'alamat' => 'DUSUN TEMU',                                 'desa' => 'SITIREJO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnsitirejotiga@gmail.com',             'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20518851', 'nama' => 'SD NEGERI 4 BEDALISODO', 'logo' => null, 'alamat' => 'DUSUN WANGKAL',                            'desa' => 'DALISODO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnbedalisodo04_wagir@yahoo.co.id',     'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516847', 'nama' => 'SD NEGERI 4 SITIREJO', 'logo' => null, 'alamat' => 'JL. RAYA SITIREJO 110',                      'desa' => 'SITIREJO',      'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdsitirejoempat@gmail.com',             'website' => '-',                                   'ks_id' => null,],
            ['npsn' => '20516973', 'nama' => 'SD NEGERI 4 SUMBERSUKO', 'logo' => null, 'alamat' => 'JL. RAYA PRECET NO. 6',                    'desa' => 'SUMBERSUKO',    'kecamatan' => 'WAGIR', 'kabupaten' => 'MALANG', 'kode_pos' => '65158', 'telp' => '-',                'email' => 'sdnsumbersuko04@yahoo.com',             'website' => '-',                                   'ks_id' => null,]
        ];

        foreach ($sekolahs as $sekolah) {
            Sekolah::updateOrCreate(
                [
                    'npsn' => $sekolah['npsn'],
                ],
                [
                    'nama' => $sekolah['nama'],
                    'logo' => $sekolah['logo'],
                    'alamat' => $sekolah['alamat'],
                    'desa' => $sekolah['desa'],
                    'kecamatan' => $sekolah['kecamatan'],
                    'kabupaten' => $sekolah['kabupaten'],
                    'kode_pos' => $sekolah['kode_pos'],
                    'telp' => $sekolah['telp'],
                    'email' => $sekolah['email'],
                    'website' => $sekolah['website'],
                    'ks_id' => $sekolah['ks_id'],
                ]
            );
        }
    }
}
