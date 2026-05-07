<!doctype html>
<html>
    <head>
        <title>Transkrip Nilai</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body class="font-serif bg-slate-300 print:bg-white p-4 print:p-0" onload="cetak()">

        <div class="page py-20">
            <h1 class="text-center uupercase text-2xl font-black">
                LAPORAN
            </h1>
            <h1 class="text-center uupercase text-xl font-black">
                HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK
            </h1>

            <img src="/img/tutwuri.png" alt="Tutwuri" class="h-40 mx-auto my-8">
            <h1 class="text-center uppercase text-xl font-black">
                PEMERINTAH KABUPATEN {{ $siswa->sekolah->kabupaten }} <br>
                Kecamatan {{ $siswa->sekolah->kecamatan }}

            </h1> 
            <h1 class="text-center uppercase text-4xl font-black">
                
                {{ $siswa->sekolah->nama }}

            </h1>
            <h1 class="text-center uppercase text-xl font-black">
                NPSN: {{ $siswa->sekolah->npsn }}

            </h1> 


            <div class="siswa p-4 border border-black w-2/4 mx-auto mt-40 mb-28">
                <h3 class="text-center uppercase text-lg font-bold">{{ $siswa->nama }}</h3>
                <h4 class="text-center">NIS/NISN: {{ $siswa->nis ?? '-' }} / {{ $siswa->nisn }}</h4>
            </div>

            <div class="alamat">
                <p class="text-center font-black text-lg">Alamat: {{ $siswa->sekolah->alamat }} {{ $siswa->sekolah->desa }} {{ $siswa->sekolah->kode_pos }}</p>
                <p class="text-center font-black text-lg">Email:{{ $siswa->sekolah->email }}</p>
                <p class="text-center font-black text-lg">Website:{{ $siswa->sekolah->website }}</p>

            </div>
        </div>
        <script>
            function cetak() {
                window.print();
                setTimeout(() => {
                    // window.close()
                }, 500)
            }
        </script>

    </body>
</html>
