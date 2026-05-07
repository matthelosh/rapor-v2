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
                    // window.close()
                }, 500)
            }
        </script>
        <div class="wrapper">
            <h1>Hasil Analisis Asesmen: {{$asesmen->nama}}</h1>
        </div>
    </body>
</html>
