<!doctype html>
<html>
    <head>
        <title>Data Siswa Per Rombel Tiap Sekolah</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body>

        @foreach($sekolahs as $sekolah)
            <div class="page break-inside-avoid break-after-always my-4">
                <h3 class="text-center font-bold text-xl uppercase">Data Siswa {{$sekolah->nama}}</h3>
                <table style="width: 800px;margin: 20px auto;">
                    <thead>
                        <tr>
                            <th class="border border-black p-2" rowspan="2">No</th><th class="border border-black p-2" rowspan="2">Rombel</th><th class="border border-black p-2" colspan="6">Jml Siswa Berdasarkan Agama</th><th class="border border-black p-2" rowspan="2">JML</th>
                        </tr>
                        <tr>
                            <th class="border border-black p-2">Islam</th><th class="border border-black p-2">Kristen</th><th class="border border-black p-2">Katolik</th><th class="border border-black p-2">Hindu</th><th class="border border-black p-2">Budha</th><th class="border border-black p-2">Konghuchu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($no=0)
                        @foreach($sekolah->rombels as $rombel)
                            <tr>
                                <td class="border border-black p-2 text-center">{{$no+1}}</td>
                                <td class="border border-black p-2">{{$rombel->label}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->filter(fn ($siswa) => $siswa->agama == 'Islam')->count()}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->filter(fn ($siswa) => $siswa->agama == 'Kristen')->count()}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->filter(fn ($siswa) => $siswa->agama == 'katolik')->count()}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->filter(fn ($siswa) => $siswa->agama == 'Hindu')->count()}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->filter(fn ($siswa) => $siswa->agama == 'Budha')->count()}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->filter(fn ($siswa) => $siswa->agama == 'Konghuchu')->count()}}</td>
                                <td class="border border-black p-2">{{$rombel->siswas->count()}}</td>
                            </tr>
                            @php($no++)
                        @endforeach
                        <tr>
                            <td colspan="8" class="border border-black p-2">JUMLAH</td>
                           <td class="border border-black p-2">{{$sekolah->siswas->count()}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        @endforeach
    </body>
</html>
