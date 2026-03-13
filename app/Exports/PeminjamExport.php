<?php

namespace App\Exports;

use App\Models\BarangPeminjam;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil data beserta relasi barang
        return BarangPeminjam::with('barang')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Peminjam',
            'Barang',
            'Merek',
            'Jumlah',
            'Tgl Pinjam',
            'Tgl Kembali',
            'Status',
        ];
    }

    public function map($peminjam): array
    {
        static $no = 1;
        return [
            $no++,
            $peminjam->nama_peminjam,
            $peminjam->barang->name_barang ?? 'Data Dihapus',
            $peminjam->merek,
            $peminjam->jumlah,
            $peminjam->tanggal_peminjaman,
            $peminjam->tanggal_pengembalian,
            ucfirst($peminjam->status), // Membuat huruf depan jadi kapital (Dipinjam/Dikembalikan)
        ];
    }
}