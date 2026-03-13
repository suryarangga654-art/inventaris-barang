<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Barang</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        .title { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #fff3e0; } /* Warna orange muda */
        .status-badge { font-weight: bold; }
    </style>
</head>
<body>
    <div class="title">
        <h2>LAPORAN PEMINJAMAN BARANG</h2>
        <p>Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Merek</th>
                <th>Qty</th>
                <th>Pinjam</th>
                <th>Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjam as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->nama_peminjam }}</td>
                <td>{{ $item->barang->name_barang ?? 'Data Dihapus' }}</td>
                <td>{{ $item->merek }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->tanggal_peminjaman }}</td>
                <td>{{ $item->tanggal_pengembalian }}</td>
                <td class="status-badge">{{ strtoupper($item->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>