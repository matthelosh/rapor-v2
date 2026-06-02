<!doctype html>
<html>
    <head>
        <title>Rapor PAS</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="http://localhost:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body class="font-serif p-4 print:p-0">

        <div class="page akademik w-[60%] py-4 px-8 mx-auto print:w-full break-inside-avoid relative text-[0.8em]">
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
                                    Ananda <b>{{ ucwords(strtolower($siswa->nama)) }}</b>
                                    @if(isset($nilai['maxu']['tp']['teks']))
                                        Menunjukkan penguasaan dalam:
                                        {{ $nilai['maxu']['tp']['teks'] }}
                                    @endif
                                    <br />
                                    @if (isset($nilai['minu']['tp']['teks']) && $nilai['minu'] != null && isset($nilai['maxu']['skor']) && $nilai['minu']['skor'] < $nilai['maxu']['skor'])
                                        Namun masih perlu bimbingan dalam:
                                        {{ $nilai['minu']['tp']['teks'] }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="page ko_ekstrakurikuler w-[60%] py-4 px-8 mx-auto print:w-full  mt-4 print:mt-0 break-inside-avoid relative">
            <div class="nilai_kokurikuler mt-4 w-full">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-black px-2 font-bold">Kokurikuler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black px-4 py-2">{{$kokurikuler}}</td>
                        </tr>
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
            <div class="absen_note mt-4 w-full">
                <div class="grid grid-cols-12">
                    <div class="col-span-5">
                        <table>
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-black px-2 font-bold text-center" colspan="2">Ketidakhadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-black px-4 py-2">Sakit</td>
                                    <td class="border border-black px-4 py-2">{{$absensi['sakit'] ?? '-'}} Hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-black px-4 py-2">Izin</td>
                                    <td class="border border-black px-4 py-2">{{$absensi['ijin'] ?? '-'}} Hari</td>
                                </tr>
                                <tr>
                                    <td class="border border-black px-4 py-2">Tanpa Keterangan</td>
                                    <td class="border border-black px-4 py-2">{{$absensi['alpa'] ?? '-'}} Hari</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-span-7">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-200">
                                    <th class="border border-black px-2 font-bold text-center" >Catatan Wali Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-black p-1">
                                        @if(is_array($catatan))
                                            {{ implode(', ', $catatan) }}
                                        @else
                                            {{ $catatan }}
                                        @endif
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
                            <th class="border border-black px-2 font-bold text-center" >Tanggapan Orang Tua/Wali</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black p-8"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @php
                $kelases = ["1" => "I (SATU)", "2" => "II (DUA)", "3" => "III (TIGA)", "4" => "IV (EMPAT)", "5" => "V (LIMA)", "6" => "VI (ENAM)"];
            @endphp
            <div class="w-full mt-4">
                @if($semester == '2')
                <div class="sem-2">
                    <p class="font-bold">
                        Keputusan:
                    </p>
                    <p class="text-justify">Berdasarkan hasil belajar yang telah dicapai,
                    @if($rombel->tingkat == '6')
                        
                            Ananda {{ucwords(strtolower($siswa->nama))}}, dinyatakan <span class="is_not_lulus hidden font-bold">Tidak Lulus</span><span class="is_lulus font-bold"> Lulus</span> dan <span class="is_not_lulus font-bold hidden">Tidak</span> <span class="font-bold">Dapat</span> melanjutkan ke jenjang pendidikan selanjutnya.
                        </p>
                    @else
                        Ananda {{ucwords(strtolower($siswa->nama))}}, dinyatakan <span class="is_not_lulus hidden font-bold">Tidak Naik</span><span class="is_lulus font-bold"> Naik</span> ke kelas {{$kelases[$rombel->tingkat + 1]}}.
                        </p>
                    @endif
                </div>
                @else
                <div class="sem-1"></div>
                @endif
            </div>

            <div class="ttd mt-8 w-full ">
                <div class="grid grid-cols-3 mt-4">
                    <div></div>
                    <div></div>
                    <p class="text-center">Wagir, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
                </div>
                <div class="grid grid-cols-7">
                    <div class="col-span-3 text-center">
                        <p>Orang Tua/Wali</p>
                        <br>
                        <br>
                        <br>
                        <p class="font-bold">..........................................</p>
                    </div>

                    <div class="col-span-1 text-center relative">
                    </div>
                    <div class="col-span-3 text-center relative">
                        <p>Wali Kelas</p>
                        <!-- <img src="https://is3.cloudhost.id/alsya/public/images/ttd/{{ $rombel->wali_kelas->nip }}.png" class="absolute max-h-[55px] left-[50%] -translate-x-[50%] rotate-4 z-0" onerror="this.onerror = null;this.style.display = 'none'; this.src='{{ asset('img/no_ttd.png') }}'" />  -->
                        <br>
                        <br>
                        <br>
                        <p class="font-bold underline"><span class="uppercase">{{$rombel->wali_kelas->nama}}</span>, {{$rombel->wali_kelas->gelar_belakang}}</p>
                        <p class="leading-4">NIP. {{$rombel->wali_kelas->status == 'gtt' ? '-' : $rombel->wali_kelas->nip}}</p>
                    </div>
                </div>
                <div class="grid grid-cols-5 mt-4">
                    <div class="col-span-1 text-center">
                    </div>

                    <div class="col-span-3 text-center relative">
                        <p>Mengetahui,</p>
                        <p>Kepala Sekolah</p>
                        <br>
                        <br>
                        <br>
                        <p class="font-bold underline uppercase">{{$siswa->sekolah->ks->nama}}, {{$siswa->sekolah->ks->gelar_belakang}}</p>
                        <p class="leading-4">NIP. {{$siswa->sekolah->ks->nip}}</p>
                    </div>
                    <div class="col-span-1 text-center relative">
                    </div>
                </div>
        </div>
        <div class="print-watermark"></div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Trigger print after a short delay to ensure DOM is ready
                setTimeout(() => {
                    window.print();
                }, 500);
            })
        </script>
        <style>
            .print-watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                width: 400px;
                height: 400px;
                background: url('/img/tutwuri.png') no-repeat center center;
                background-size: contain;
                opacity: 0.08;
                transform: translate(-50%, -50%);
                z-index: -1;
                pointer-events: none;
            }
        </style>
    </body>
</html>
