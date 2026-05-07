<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .text-center {
            text-align: center;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrapper" style="background: url( ' {{ URL::asset('img/bg-sertifikat-1.svg') }} ' )">
        <h2 class="text-center">SERTIFIKAT</h2>
        <h4 class="text-center">Diberikan Kepada:</h4>

        <h1 class="text-center underline">{{$data->penerima->nama}}</h1>
        <p class="text-center">NIP. {{ $data->penerima->nip }}</p>
        <h3 class="text-center">{{ $data->penerima->sekolahs[0]->nama }}</h3>
        <p class="text-center">{{ $data->deskripsi }}</p>
    </div>
</body>

</html>