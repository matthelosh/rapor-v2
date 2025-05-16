<!doctype html>
<html lang="id">
    <head>
        <title>Verifikasi Transkrip Nilai</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
    </head>
    <body>
        <div
            class="wrapper w-screen h-screen flex p-8 justify-center {{ $is_verified ? 'bg-slate-50' : 'bg-red-50' }}"
            @class(['bg-red-100' => !$is_verified,'bg-sky-100' => $is_verified])
        >
            <div class="content p-4">
                <h1 class="text-center font-bold font-['Arial'] text-2xl {{ $is_verified ? 'text-sky-800' : 'text-red-900'}}">{{$message}}</h1>
                @if ($is_verified)
                    <div class="p-4 bg-white w-[80%] mx-auto my-8 rounded shadow">
                    <h1 class="text-center text-lg font-bold uppercase">Detail Siswa</h1>
                    <table class="w-full ">
                        <tbody>
                            <tr>
                                <td>Nama</td><td>:</td><td>{{$transkrip->siswa->nama}}</td>
                            </tr>
                            <tr>
                                <td>NISN</td><td>:</td><td>{{$transkrip->siswa->nisn}}</td>
                            </tr>
                            <tr>
                                <td>Satuan Pendidikan</td><td>:</td><td>{{$transkrip->siswa->sekolah->nama}}</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                @endif
            </div>
        </div>
    </body>
</html>
