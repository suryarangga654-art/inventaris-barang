<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Barang</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Daftar Inventaris Barang</h2>
    <p>Tanggal: {{ date('d-m-Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->name_barang }}</td>
                <td>{{ $item->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>