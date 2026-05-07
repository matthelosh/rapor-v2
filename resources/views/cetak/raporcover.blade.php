<!doctype html>
<html>
    <head>
        <title>Cover Rapor {{$siswa->nisn}}</title>
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
        <div class="page bg-white shadow-lg print:shadow-none p-6 w-[70%] print:w-[100%] mx-auto flex flex-col h-screen justify-center">
            <div>
                <h3 class="text-center text-2xl font-bold uppercase">Laporan Hasil Capaian Kompetensi</h3>
                <h3 class="text-center text-2xl font-bold uppercase">Peserta Didik</h3>
                <h3 class="text-center text-2xl font-bold uppercase">Sekolah Dasar (SD)</h3>
                <h3 class="text-center text-xl font-bold uppercase my-6">Kurikulum Merdeka</h3>
            </div>
            <img src="{{asset('/img/tutwuri.png')}}" alt="Tutwuri" class="mx-auto h-48 mt-16 mb-12" />
            <div class="flex-grow"></div>
            <div class="flex-grow">
            <p class="text-center">Nama Peserta Didik</p>
            <p class="text-center border-4 border-double border-black w-[60%] mx-auto p-2 font-bold text-lg uppercase">{{$siswa->nama}}</p>
            <p class="text-center mt-4">NIS / NISN</p>
            <p class="text-center border-4 border-double border-black w-[60%] mx-auto p-2 font-bold text-lg uppercase">{{$siswa->nis ?? '-'}} / {{$siswa->nisn}}</p>
            </div>
            <div>
            <h3 class="text-center text-xl font-bold">{{$siswa->sekolah->nama}}</h3>
            <p class="text-center">{{$siswa->sekolah->alamat}}, {{$siswa->sekolah->desa}}</p>
            </div>
        </div>
    </body>
</html>
