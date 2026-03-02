<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Tambahkan ini

class BarangExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil kolom tertentu saja agar lebih rapi
        return Barang::select('id', 'name_barang', 'stock', 'created_at')->get();
    }

    /**
     * Menambahkan Judul Kolom di Excel
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