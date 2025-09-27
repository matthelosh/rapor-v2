<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kartu Pelajar | {{$sekolah->nama}}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap');
        body {
            font-family: 'Nunito', sans-serif;
        }
        @media print {
            .card {
                width: 85.6mm!important;
                height: 54mm !important;
                background: url('/img/kartu_pelajar/depan.png');
                background-position: center;
                background-size: cover;
                position: relative;
                border: 2px dashed gray;
                border-radius: 20px;

                h1 {
                    font-size: .8rem;
                    font-weight: 800;
                }
                h2 {
                    font-size: .7rem;
                    font-weight: 400;
                }
                table td {
                    vertical-align: top;
                    font-size: .5rem;
                }
                .foto {
                    width: 110px !important;
                    height: 95px !important;
                }
            }
        }
        /* @media screen { */
            .container {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                padding: 10px;
                gap: 10px;
            }
            .foto {
                position: absolute;
                top: 29.5%;
                left: 5.5%;
                width: 220px;
                height: 189px;
                background: white;
                /* padding: 20px; */
                box-sizing: border-box;
                overflow: hidden;
                clip-path: polygon(
                    25% 0%,   /* atas */
                    75% 0%, /* kanan atas */
                    100% 50%, /* kanan bawah */
                    75% 100%, /* bawah */
                    25% 100%,   /* kiri bawah */
                    0% 50%    /* kiri atas */
                );

                img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }
            }

            .card {
                width: 600px;   /* tampil lebih kecil proporsional */
                height: 400px;   /* biar ikut rasio */
                border: 1px solid #ccc;
                background: url('/img/kartu_pelajar/depan.png');
                background-size: cover;
                border-radius: 10px;
                position: relative;

                .content {
                    position: absolute;
                    top: 30%;
                    left: 46%;
                    margin-right: 10px;

                    h1 {
                        font-family: Arial, Helvetica, sans-serif;
                        font-weight: 800;
                        letter-spacing: .2rem;
                        line-height: 0rem;
                        margin-bottom: 0;
                    }
                    h2 {
                        font-weight: 400;
                    }
                    table td {
                        vertical-align: top;
                    }
                }
            }
        /* } */
    </style>
</head>
<body>
    <div class="container">
        @foreach($sekolah->siswas as $siswa)
            <div class="card">
                <div class="foto">
                    <img src="{{$siswa->foto}}" alt="Foto Siswa" onerror="this.error = false; this.src='/img/kartu_pelajar/siswa.png'">
                </div>
                <div class="content">
                    <h1>KARTU PELAJAR</h1>
                    <h2>SEKOLAH DASAR</h2>
                    <table>
                        <tbody>
                            <tr>
                                <td>NISN</td>
                                <td>:</td>
                                <td>{{$siswa->nisn}}</td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>:</td>
                                <td>{{ucwords(strtolower($siswa->nama))}}</td>
                            </tr>
                            <tr>
                                <td>Alamat</td>
                                <td>:</td>
                                <td>{{$siswa->alamat}}, {{$siswa->desa}} </td>
                            </tr>
                            <tr>
                                <td>Lembaga</td>
                                <td>:</td>
                                <td>{{$sekolah->nama}}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        @endforeach
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(() => {
                window.print();
                window.close();
            }, 1000);
        })
    </script>
</body>
</html>
