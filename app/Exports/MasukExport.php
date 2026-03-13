<?php

namespace App\Exports;

use App\Models\BarangMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasukExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Load relasi barang agar bisa mengambil nama barang
        return BarangMasuk::with('barang')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Merek',
            'Jumlah Masuk',
            'Tanggal Masuk',
            'Keterangan',
        ];
    }

    public function map($masuk): array
    {
        static $no = 1;
        return [
            $no++,
            $masuk->barang->name_barang ?? 'Barang Dihapus',
            $masuk->merek,
            $masuk->jumlah,
            $masuk->tanggal_barang_masuk,
            $masuk->keterangan,
        ];
    }
}