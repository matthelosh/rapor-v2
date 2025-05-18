<!doctype html>
<html lang="id">
    <head>
        <title>Transkrip Nilai</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body class="font-serif bg-slate-300 print:bg-white p-4 print:p-0" onload="cetak()">
        <script>
            function cetak() {
                window.print();
                setTimeout(() => {
                    window.close()
                }, 500)
            }
        </script>
        <div class="wrapper w-full md:w-[900px]  print:w-full mx-auto p-4 print:p-0 bg-white shadow print:shadow-none print:bg-white relative">
            <div class="kop grid grid-cols-5 border-black border-double border-b-4">
                <div class="col-span-1 flex items-center justify-center">
                    <img src="{{asset('img/malangkab.png') }}" alt="Logo" class="h-32" />
                </div>
                <div class="col-span-3">
                    <h1 class="text-center  font-black text-xl uppercase">Pemerintah Kabupaten Malang</h1>
                    <h2 class="text-center leading-5  font-black text-md uppercase">Dinas Pendidikan</h2>
                    <h2 class="text-center leading-5  font-black text-md uppercase">Korwil Kecamatan {{$sekolah->kecamatan}}</h2>
                    <h1 class="text-center leading-8  font-black text-2xl uppercase">{{$sekolah->nama}}</h1>
                    <p class="text-center  leading-4 text-sm">{{$sekolah->alamat}}, {{$sekolah->desa}}, Kode Pos {{$sekolah->kode_pos}}</p>
                    <p class="text-center  leading-4 text-sm">Email: {{$sekolah->email}}, Website: {{$sekolah->website}}</p>

                </div>
                <div class="col-span-1">

                </div>
            </div>
            <h1 class="text-center font-black text-lg uppercase mt-6 leading-4">Transkrip Nilai</h1>
            <p class="text-center leading-4">No: ...........................</p>

            <table class="my-8">
                <tbody>
                    <tr>
                        <td>Satuan Pendidikan</td><td class="pr-2">:</td><td>{{$sekolah->nama}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Pokok Sekolah Nasional</td><td class="pr-2">:</td><td>{{$sekolah->npsn}}</td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td><td class="pr-2">:</td><td>{{$siswa->nama}}</td>
                    </tr>
                    <tr>
                        <td>Tempat, Tanggal Lahir</td>
                        <td class="pr-2">:</td>
                        @php
                            $tglLahir=\Carbon\Carbon::parse($siswa->tanggal_lahir)->locale('id')->format('d F Y')
                        @endphp
                        <td>{{$siswa->tempat_lahir}}, {{$tglLahir}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Induk Siswa Nasional</td>
                        <td class="pr-2">:</td>
                        <td>{{$siswa->nisn}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Ijazah</td>
                        <td class="pr-2">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tanggal Kelulusan</td><td class="pr-2">:</td><td></td>
                    </tr>
                </tbody>
            </table>

            <table class="isi my-8 w-full">
                <thead>
                    <tr>
                        <th class="border border-black uppercase p-2">No.</th>
                        <th class="border border-black uppercase p-2">Mata Pelajaran</th>
                        <th class="border border-black uppercase p-2">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=0; @endphp
                    @foreach($nilais as $k=>$v)
                        <tr>
                            <td class="border border-black p-2 text-center">{{ $no+1 }}</td>
                            <td class="border border-black p-2">{{ $k }}</td>
                            <td class="border border-black p-2 text-center">{{$v}}</td>
                        </tr>
                        @php $no++; @endphp
                    @endforeach
                </tbody>
            </table>

            <div class="grid grid-cols-6">
                <div class="col-span-2"></div>
                <div class="col-span-2"></div>
                <div class="col-span-2">
                    <p>Malang, ....., .......... Tahun</p>
                    <p>Kepala,</p>

                    <p class="font-bold uppercase underline mt-16 leading-4">{{$sekolah->ks->nama}}, {{$sekolah->ks->gelar_belakang}}</p>
                    <p class="leading-4">NIP. @if($sekolah->ks->status == 'pns') {{ $sekolah->ks->nip }} @endif</p>
                </div>
            </div>

            <img class="qrcode absolute bottom-0" src="data:image/png;base64,{{ base64_encode(
                QrCode::format('png')
                    ->merge(public_path('img/logo_pkg.png'), 0.3, true)
                    ->size(100)
                    ->generate(config('app.url').'/verifikasi/transkrip/'. $siswa->nisn)
                ) }}"
            />
        </div>
    </body>
</html>
