<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 5px; }
    </style>
</head>
<body>
    <h2>Laporan Keuangan</h2>
    <table>
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Nominal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ ucfirst($d->jenis) }}</td>
                    <td>{{ $d->kategori }}</td>
                    <td>Rp{{ number_format($d->nominal, 0, ',', '.') }}</td>
                    <td>{{ $d->tanggal }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
