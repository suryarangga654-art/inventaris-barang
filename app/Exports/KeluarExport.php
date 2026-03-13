<?php

namespace App\Exports;

use App\Models\BarangKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KeluarExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        // Mengambil data dengan relasi barang agar nama barang muncul
        return BarangKeluar::with('barang')->get();
    }

    // Header untuk kolom Excel
    public function headings(): array
    {
        return [
            'No',
            'Nama Barang',
            'Merek',
            'Jumlah Keluar',
            'Tanggal Keluar',
            'Keterangan',
        ];
    }

    // Mapping data agar yang muncul nama barang, bukan ID-nya
    public function map($keluar): array
    {
        static $no = 1;
        return [
            $no++,
            $keluar->barang->name_barang ?? 'Barang Dihapus',
            $keluar->merek,
            $keluar->jumlah,
            $keluar->tanggal_barang_keluar,
            $keluar->keterangan,
        ];
    }
}