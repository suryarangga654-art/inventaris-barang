<?php

namespace App\Exports;

use App\Models\Barang; // Import Model Barang
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Tambahkan ini untuk judul kolom

class LaporanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua data barang dari database
        return Barang::all(['id', 'name_barang', 'stock', 'created_at']);
    }

    /**
     * Menambahkan baris judul di bagian atas Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Stok',
            'Tanggal Dibuat',
        ];
    }
}