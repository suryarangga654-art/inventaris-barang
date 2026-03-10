<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangPeminjam;

class BarangExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $rincian = collect();
        
        // Ambil semua data (bisa tambahkan filter tanggal jika perlu)
        foreach (BarangMasuk::with('barang')->get() as $m) {
            $rincian->push(['ID' => $m->id, 'Tgl' => $m->tanggal_barang_masuk, 'Barang' => $m->barang->name_barang, 'Status' => 'Masuk', 'Qty' => $m->jumlah]);
        }
        foreach (BarangKeluar::with('barang')->get() as $k) {
            $rincian->push(['ID' => $k->id, 'Tgl' => $k->tanggal_barang_keluar, 'Barang' => $k->barang->name_barang, 'Status' => 'Keluar', 'Qty' => $k->jumlah]);
        }
        foreach (BarangPeminjam::with('barang')->get() as $p) {
            $rincian->push(['ID' => $p->id, 'Tgl' => $p->tanggal_peminjaman, 'Barang' => $p->barang->name_barang, 'Status' => 'Pinjam', 'Qty' => $p->jumlah_pinjam]);
        }

        return $rincian;
    }

    public function headings(): array {
        return ["Order ID", "Tanggal", "Nama Barang", "Status", "Total"];
    }
}