<!doctype html>
<html>
    <head>
        <title>Data Rombel - {{ $rombel->label }}</title>
        @if (app()->environment('local'))
            <link rel="stylesheet" href="{{ config('app.url') }}:5173/resources/css/app.css">
        @else
            <link rel="stylesheet" href="{{ config('app.url') }}/build/assets/app.css">
        @endif
        <style>
            body {
                font-family: 'Nunito', Arial, sans-serif;
                padding: 20px;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
            }
            .header h1 {
                margin: 0;
                font-size: 1.5rem;
                text-transform: uppercase;
            }
            .header p {
                margin: 5px 0;
                font-size: 0.9rem;
            }
            .info-table {
                width: 100%;
                margin-bottom: 20px;
                border-collapse: collapse;
            }
            .info-table td {
                padding: 6px 10px;
                border: 1px solid #ddd;
                vertical-align: top;
            }
            .info-table td:first-child {
                width: 180px;
                font-weight: 600;
                background: #f5f5f5;
            }
            .siswa-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            .siswa-table th,
            .siswa-table td {
                border: 1px solid #333;
                padding: 8px 10px;
                text-align: left;
                font-size: 0.85rem;
            }
            .siswa-table th {
                background: #e0e0e0;
                text-align: center;
                font-weight: 700;
            }
            
            .footer {
                margin-top: 30px;
                text-align: right;
                font-size: 0.8rem;
                color: #555;
            }
            @media print {
                body {
                    padding: 0;
                }
                .no-print {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <div class="no-print" style="margin-bottom: 15px;">
            <button onclick="window.print()" style="padding: 8px 16px; cursor: pointer;">Cetak / Print</button>
        </div>

        <div class="header">
            <h1>{{ $rombel->sekolah->nama ?? '' }}</h1>
            <p>Data Rombel / Kelas - Tahun Pelajaran {{ $tapel->label ?? '-' }}</p>
        </div>

        <!-- {{$rombel->siswas}} -->
        <table class="info-table">
            <tr>
                <td>Rombel</td>
                <td>{{ $rombel->label }}</td>
                <td>Kode Rombel</td>
                <td>{{ $rombel->kode }}</td>
            </tr>
            <tr>
                <td>Tingkat</td>
                <td>{{ $rombel->tingkat }}</td>
                <td>Pararel</td>
                <td>{{ ucfirst($rombel->pararel) }}</td>
            </tr>
            <tr>
                <td>Wali Kelas</td>
                <td>{{ $rombel->wali_kelas->nama ?? '-' }}</td>
                <td>Jumlah Siswa</td>
                <td>{{ $rombel->siswas->count() }}</td>
            </tr>
            <tr>
                <td>Jumlah Laki-laki</td>
                <td>{{ $rombel->siswas->filter(fn($s) => $s->jk == 'Laki-laki')->count() }}</td>
                <td>Jumlah Perempuan</td>
                <td>{{ $rombel->siswas->filter(fn($s) => $s->jk == 'Perempuan')->count() }}</td>
            </tr>
        </table>

        <table class="siswa-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 25px; text-align:center;">No</th>
                    <th rowspan="2">NIS</th>
                    <th rowspan="2" style="width: 100px;">NISN</th>
                    <th rowspan="2" >Nama Lengkap</th>
                    <th rowspan="2" style="width: 25px;">JK</th>
                    <th rowspan="2">Tempat, Tgl Lahir</th>
                    <th rowspan="2">Agama</th>
                    <th colspan="3">Orang Tua</th>
                </tr>
                <tr>
                    <th>Ayah</th>
                    <th>Ibu</th>
                    <th>Wali</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rombel->siswas as $index => $siswa)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $siswa->nis ?? '-' }}</td>
                    <td>{{ $siswa->nisn }}</td>
                    <td>{{ ucwords(strtolower($siswa->nama)) }}</td>
                    <td>{{ $siswa->jk == 'Laki-laki' ? 'L' : 'P' }}</td>
                    <td>{{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y')}}</td>
                    <td>{{ $siswa->agama }}</td>
                    <td>{{ $siswa->ortus[0]['nama'] ?? '-' }}</td>
                    <td>{{ $siswa->ortus[1]['nama'] ?? '-' }}</td>
                    <td>{{ $siswa->ortus[2]['nama'] ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada siswa pada rombel ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}
        </div>

        <script>
            setTimeout(() => {
                window.print();
            }, 500);
        </script>
    </body>
</html>
