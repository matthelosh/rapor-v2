<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Piagam Ranking Kelas {{$siswa->nama}}</title>
    @if (app()->environment('local'))
        <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
    @else
        <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Passions+Conflict&display=swap" rel="stylesheet">
<style>
    body {
        background: url("{{asset('img/bg_rangking_a.png')}}") no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 50px 20px;
    }
    .nama {
        font-family: "Passions Conflict", cursive;
        font-weight: 400;
        font-style: normal;
    }
    </style>
</head>
<body>
    <div class="page">
        <div class="kop grid grid-cols-6 border-b-4 border-double border-black items-center p-4">
            <img src="{{ asset('img/malangkab.png') }}" alt="Logo Malangkab" class="h-24 col-span-1" />
            <div class="text-center col-span-4">
                {{-- <h3 class="uppercase leading-4 font-bold">Pemerintah Kabupaten {{$siswa->sekolah->kabupaten}}</h3>
                <h3 class="uppercase leading-4 font-bold">Dinas Pendidikan</h3>
                <h3 class="uppercase leading-4 font-bold">Korwil Kecamatan {{$siswa->sekolah->kecamatan}}</h3> --}}
                <h3 class="uppercase leading-6 font-bold text-4xl font-serif">{{$siswa->sekolah->nama}}</h3>
                <p>{{$siswa->sekolah->alamat}}, {{$siswa->sekolah->desa}}, Kode Pos {{$siswa->sekolah->kode_pos}}</p>
                {{-- <p>Email: {{$siswa->sekolah->email}}, Website: {{$siswa->sekolah->website}}</p> --}}
            </div>
            <div class="text-center col-span-1"></div>
        </div>
        <div class="konten p-4">
            <h1 class="text-center uppercase text-6xl font-black">Piagam Penghargaan</h1>
            <h5 class="text-center text-xl my-4">Diberikan kepada:</h5>
            <h1 class="text-center text-8xl font-bold my-6 nama">{{ ucwords(strtolower($siswa->nama)) }}</h1>
            <p class="text-center text-xl my-4">Atas Prestasinya sebagai:</p>
            <h3 class="text-center text-2xl mt-4 mb-2 bg-sky-500 mx-auto w-[40%] uppercase p-3 rounded-full text-white">Peringkat {{$rank}} di Kelas {{$rombel->label}}</h3>
            <h3 class="text-center text-xl font-bold">Dengan nilai: {{$nilai}}</h3>
            <p class="text-center text-xl uppercase font-bold my-4">Pada {{$semester->deskripsi}} {{$tapel->deskripsi}}</p>
        </div>
        <div class="ttd grid grid-cols-2 items-center p-4 mt-2">
            <div class="text-center col-span-1">
                <p class="mt-4">Kepala Sekolah</p>

                <p class="font-bold underline leading-4 mt-14"><span class="uppercase">{{ $siswa->sekolah->ks->nama }}</span>, {{ $siswa->sekolah->ks->gelar_belakang }}</p>
                <p class="text-center">NIP. {{ $siswa->sekolah->ks->status !== 'gtt' ? $siswa->sekolah->ks->nip : '-' }}</p>
            </div>
            <div class="text-center col-span-1">
                <p>{{$siswa->sekolah->kecamatan}}, </p>
                <p>Wali Kelas {{$rombel->label}}</p>
                <p class="font-bold underline leading-4 mt-14"><span class="uppercase">{{$rombel->wali_kelas->nama}}</span>,{{$rombel->wali_kelas->gelar_belakang}}</p>
                <p class="text-center">NIP. {{ $rombel->wali_kelas->status !== 'gtt' ? $rombel->wali_kelas->nip : '-' }}</p>
            </div>

        </div>
    </div>

</body>
</html>