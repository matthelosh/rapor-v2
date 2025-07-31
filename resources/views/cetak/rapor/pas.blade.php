<!doctype html>
<html>
    <head>
        <title>Rapor PAS</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
        <style>
            @media print {
                body::before {
                    content: "";
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    width: 300px;
                    height: 300px;
                    background: url('/img/tutwuri.png') no-repeat center center;
                    background-size: contain;
                    opacity: 0.1;
                    transform: translate(-50%, -50%);
                    z-index: -1;
                }
            }
        </style>
    </head>
    <body class="font-serif p-4 print:p-0" onload="cetak()">
        <script>
            // function cetak() {
            //     window.print();
            //     setTimeout(() => {
            //         window.close()
            //     }, 500)
            // }
        </script>

        <div class="page akademik w-[60%] py-4 px-8 mx-auto print:w-full break-inside-avoid relative">
            {{-- <img src="/img/tutwuri.png" alt="Watermark" class="absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] w-72  opacity-10 z-0"> --}}
            <div class="meta w-full">
                <h3 class="text-center font-bold text-xl uppercase mb-4">Laporan Hasil Belajar</h3>
                <div class="flex justify-between gap-4">
                <table>
                    <tbody>
                        <tr>
                            <td class="font-bold">Nama</td>
                            <td class="font-bold">:</td>
                            <td>{{ ucwords(strtolower($siswa->nama)) }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">NISN</td>
                            <td class="font-bold">:</td>
                            <td>{{ $siswa->nisn }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Sekolah</td>
                            <td class="font-bold">:</td>
                            <td>{{ucwords(strtolower($siswa->sekolah->nama))}}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Alamat</td>
                            <td class="font-bold">:</td>
                            <td>{{ucwords(strtolower($siswa->sekolah->alamat))}}</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <tbody>
                        <tr>
                            <td class="font-bold">Kelas</td>
                            <td class="font-bold">:</td>
                            <td>{{ $rombel->label }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Fase</td>
                            <td class="font-bold">:</td>
                            <td>{{ $rombel->fase }}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Semester</td>
                            <td class="font-bold">:</td>
                            <td>{{$semester == "1" ? "Ganjil" : "Genap"}}</td>
                        </tr>
                        <tr>
                            <td class="font-bold">Tahun Ajaran</td>
                            <td class="font-bold">:</td>
                            <td>{{$tapel->label}}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="nilai_intra mt-8 w-full">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-black px-1 py-1 text-center w-[30px]">No.</th>
                            <th class="border border-black px-4 py-2">Mata Pelajaran</th>
                            <th class="border border-black px-4 py-2">Nilai Akhir</th>
                            <th class="border border-black px-4 py-2">Deskripsi Pencapaian Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($nilais as $key => $nilai)
                            <tr>
                                <td class="border border-black px-1 py-2 text-center w-[30px]">{{ $loop->index + 1 }}</td>
                                <td class="border border-black px-4 py-2">{{ ($nilai['mapel']['label']) }}</td>
                                <td class="border border-black px-4 py-2 text-center">{{ ($nilai['na']) }}</td>
                                <td class="border border-black px-4 py-2 text-justify">
                                    <ol class="list-disc pl-4">
                                        <li>
                                            {{ $nilai['maxu']['tp']['teks'] }}
                                        </li>
                                        @if ($nilai['minu'] != null && $nilai['minu']['skor'] < $nilai['maxu']['skor'])
                                            <li>
                                                {{ $nilai['minu']['tp']['teks'] }}
                                            </li>
                                        @endif
                                    </ol>
                                    {{-- <p>
                                    {{ $nilai['maxu']['tp']['teks'] }}
                                    </p>
                                    <p>
                                    {{ $nilai['minu']['tp']['teks'] }}
                                    </p> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page ko_ekstrakurikuler w-[60%] py-4 px-8 mx-auto print:w-full  mt-4 print:mt-0 break-inside-avoid relative">
            {{-- <img src="/img/tutwuri.png" alt="Watermark" class="absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] w-72  opacity-10 z-0"> --}}
            <div class="nilai_kokurikuler mt-4 w-full">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-black px-2 font-bold">Kokurikuler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td class="border border-black px-4 py-2"></td>
                    </tbody>
                </table>
            </div>

            <div class="nilai_kokurikuler mt-4 w-full">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-black px-2 font-bold w-[30px]">No</th>
                            <th class="border border-black px-2 font-bold w-[30px]">Ekstrakurikuler</th>
                            <th class="border border-black px-2 font-bold w-[30px]">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <td class="border border-black px-4 py-2">1</td>
                        <td class="border border-black px-4 py-2">Pramuka</td>
                        <td class="border border-black px-4 py-2">Baik</td> --}}
                        @foreach($ekskuls as $key => $ekskul)
                            <tr>
                                <td class="border border-black px-4 py-2 text-center">{{ $loop->index + 1 }}</td>
                                <td class="border border-black px-4 py-2">{{ $ekskul['ekskul']['nama'] }}</td>
                                <td class="border border-black px-4 py-2">{{ $ekskul['deskripsi'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        {{-- </div>
        <div class="page akademik w-[60%] py-4 px-8 mx-auto print:w-full bg-white mt-4 print:mt-0 break-inside-avoid"> --}}
            <div class="absen_note mt-4 w-full">
                <div class="grid grid-cols-12">
                    <div class="col-span-5">
                        <table>
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-black px-2 font-bold center" colspan="2">Ketidakhadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-black px-4 py-2">Sakit</td>
                                    <td class="border border-black px-4 py-2">{{$absensi['sakit']}} Hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-black px-4 py-2">Izin</td>
                                    <td class="border border-black px-4 py-2">{{$absensi['ijin']}} Hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-black px-4 py-2">Tanpa Keterangan</td>
                                    <td class="border border-black px-4 py-2">{{$absensi['alpa']}} Hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- <div class="col-span-1"></div> --}}
                    <div class="col-span-7">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-black px-2 font-bold center" >Catatan Wali Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-black p-1">
                                        {{$catatan}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="note_ortu mt-4 w-full">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-black px-2 font-bold center" >Tanggapan Orang Tua/Wali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black p-1 p-8"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="ttd mt-4 w-full">
                <div class="grid grid-cols-3 mt-4">
                    <div></div>
                    <div></div>
                    <p class="text-center">Wagir, {{ date('d F Y') }}</p>
                </div>
                <div class="grid grid-cols-3">
                    <div class="col-span-1 text-center">
                        <p>Orang Tua/Wali</p>
                        <br>
                        <br>
                        <br>
                        <p class="font-bold">Nama Orang Tua/Wali</p>
                    </div>

                    <div class="col-span-1 text-center">
                        <p>Kepala Sekolah</p>
                        <br>
                        <br>
                        <br>
                        <p class="font-bold">Nama Kepala Sekolah</p>
                    </div>
                    <div class="col-span-1 text-center">
                        <p>Wali Kelas</p>
                        <br>
                        <br>
                        <br>
                        <p class="font-bold">Nama Wali Kelas</p>
                    </div>
                </div>
        </div>
    </body>
</html>
