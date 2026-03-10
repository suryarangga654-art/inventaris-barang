<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
// GANTI BAGIAN INI: Pastikan sesuai dengan nama file di folder app/Models
use App\Models\BarangPeminjam; 

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari = $request->get('dari', date('Y-m-01'));
        $sampai = $request->get('sampai', date('Y-m-d'));

        $totalMasuk = BarangMasuk::whereBetween('tanggal_barang_masuk', [$dari, $sampai])->sum('jumlah');
        $totalKeluar = BarangKeluar::whereBetween('tanggal_barang_keluar', [$dari, $sampai])->sum('jumlah');

        // GANTI BAGIAN INI: Sesuaikan nama model dan nama kolom tanggalnya
        // Jika modelnya Peminjaman dan kolomnya tanggal_pinjam:
        $totalPeminjam = BarangPeminjam::whereBetween('tanggal_peminjaman', [$dari, $sampai])->count();

        $rincian = BarangKeluar::with('barang')
                    ->whereBetween('tanggal_barang_keluar', [$dari, $sampai])
                    ->latest()
                    ->get();

        return view('laporan.index', compact('totalMasuk', 'totalKeluar', 'totalPeminjam', 'rincian', 'dari', 'sampai'));
    }
}