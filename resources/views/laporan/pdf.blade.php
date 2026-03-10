<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    </style>
</head>
<body>
    <h2>Laporan Inventaris ({{ $dari }} - {{ $sampai }})</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Status</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rincian as $item)
            <tr>
                <td>#{{ $item->id }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->jumlah }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>