<!doctype html>
<html>
    <head>
        <title>Biodata {{$siswa->nisn}}</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body class="font-serif bg-slate-300 print:bg-white p-4 print:p-0" onload="cetak()">
        <script>
            function cetak() {
            //     window.print();
            //     setTimeout(() => {
            //         window.close()
            //     }, 500)
            }
        </script>
        <div class="page bg-white shadow-lg print:shadow-none p-6 w-[70%] print:w-[100%] mx-auto h-screen justify-center">
                <h3 class="text-center text-2xl font-bold uppercase">Identitas Peserta Didik</h3>

                <table class="mt-8">
                    <tbody>
                        <tr>
                            <td class="pr-2">1.</td>
                            <td>Nama Peserta Didik (Lengkap)</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->nama}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">2.</td>
                            <td>NIPD / NISN</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->nis ?? '-'}} / {{$siswa->nisn}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">3.</td>
                            <td>Tempat, Tanggal Lahir</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->tempat_lahir}} / {{\Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y')}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">4.</td>
                            <td>Jenis Kelamin</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->jk}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">5.</td>
                            <td>Agama</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->agama}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">6.</td>
                            <td>Status Dalam Keluarga</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->agama}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">7.</td>
                            <td>Pendidikan Sebelumnya</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->agama}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">8.</td>
                            <td>Alamat Peserta Didik</td>
                            <td class="mx-2">:</td>
                            <td>{{$siswa->alamat}}, RT. {{$siswa->rt}} RW. {{$siswa->rw}}, {{$siswa->desa}}, {{$siswa->kecamatan}}, {{$siswa->kabupaten}}</td>
                        </tr>
                        <tr>
                            <td class="pr-2">9.</td>
                            <td>Nama Orang Tua</td>
                            <td class="mx-2"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>a. Ayah</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>b. Ibu</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2">10.</td>
                            <td>Pekerjaan Orang Tua</td>
                            <td class="mx-2"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>a. Ayah</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>b. Ibu</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2">11.</td>
                            <td>Alamat Orang Tua</td>
                            <td class="mx-2"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>a. Jalan</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>b. Kelurahan/Desa</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>c. Kecamatan</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>d. Kabupaten/Kota</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>e. Provinsi</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2">12.</td>
                            <td>Wali</td>
                            <td class="mx-2"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>a. Nama</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>b. Pekerjaan</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pr-2"></td>
                            <td>c. Alamat</td>
                            <td class="mx-2">:</td>
                            <td></td>
                        </tr>
                    </tbody
                </table>
        </div>
    </body>
</html>
