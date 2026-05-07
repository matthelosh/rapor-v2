<!DOCTYPE html>
<html>

<head>
    <title>Agenda April 2025</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Agenda Bulan April 2025</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Judul</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $event['tanggal'] }}</td>
                <td>{{ $event['nama'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>