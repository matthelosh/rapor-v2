<!doctype html>
<html>
    <head>
        <title>Rapor PAS</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body class="font-serif bg-slate-300 print:bg-white p-4 print:p-0" onload="cetak()">
        <script>
            // function cetak() {
            //     window.print();
            //     setTimeout(() => {
            //         window.close()
            //     }, 500)
            // }
        </script>

        <div class="page akademik w-[60%] py-4 px-8 mx-auto print:w-full bg-white">
            <div class="meta w-full">
                <h3 class="text-center font-bold text-xl uppercase mb-4">Laporan Hasil Belajar</h3>
                <div class="flex justify-between gap-4">
                <table>
                    <tbody>
                        <tr>
                            <td class="font-bold">Nama</td>
                            <td class="font-bold">:</td>
                            <td>{{ $siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">NISN</td>
                            <td class="font-bold">:</td>
                            <td>{{ $siswa->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Sekolah</td>
                            <td class="font-bold">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-bold">Alamat</td>
                            <td class="font-bold">:</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td class="font-bold">Kelas</td>
                            <td class="font-bold">:</td>
                            <td>{{ $siswa->nama }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Fase</td>
                            <td class="font-bold">:</td>
                            <td>{{ $siswa->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Semester</td>
                            <td class="font-bold">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="font-bold">Tahun Pelajaran</td>
                            <td class="font-bold">:</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="nilai">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th class="border border-black px-4 py-2">No.</th>
                            <th class="border border-black px-4 py-2">Mata Pelajaran</th>
                            <th class="border border-black px-4 py-2">Nilai Akhir</th>
                            <th class="border border-black px-4 py-2">Deskripsi Pencapaian Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black px-4 py-2">1</td>
                            <td class="border border-black px-4 py-2">Matematika</td>
                            <td class="border border-black px-4 py-2">85</td>
                            <td class="border border-black px-4 py-2">Memahami konsep dasar matematika dan dapat menerapkannya dalam pemecahan masalah.</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4 py-2">2</td>
                            <td class="border border-black px-4 py-2">Bahasa Indonesia</td>
                            <td class="border border-black px-4 py-2">90</td>
                            <td class="border border-black px-4 py-2">Memiliki kemampuan membaca dan menulis dengan baik serta dapat mengungkapkan ide-ide secara efektif.</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4 py-2">3</td>
                            <td class="border border-black px-4 py-2">Bahasa Inggris</td>
                            <td class="border border-black px-4 py-2">88</td>
                            <td class="border border-black px-4 py-2">Memiliki kemampuan membaca dan menulis dalam bahasa Inggris serta dapat mengungkapkan ide-ide secara efektif.</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4 py-2">4</td>
                            <td class="border border-black px-4 py-2">IPA</td>
                            <td class="border border-black px-4 py-2">92</td>
                            <td class="border border-black px-4 py-2">Memahami konsep dasar IPA dan dapat menerapkannya dalam pemecahan masalah.</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4 py-2">5</td>
                            <td class="border border-black px-4 py-2">IPS</td>
                            <td class="border border-black px-4 py-2">89</td>
                            <td class="border border-black px-4 py-2">Memahami konsep dasar IPS dan dapat menerapkannya dalam pemecahan masalah.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>
