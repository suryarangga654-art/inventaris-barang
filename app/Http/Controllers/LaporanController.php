<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangPeminjam;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
{
    // 1. Ambil input tanggal
    $dari = $request->input('dari', date('Y-01-01'));
    $sampai = $request->input('sampai', date('Y-12-31'));

    // 2. Ambil data dengan nama kolom yang sesuai hasil query database kamu
    $masuk = \App\Models\BarangMasuk::with('barang')
        ->whereDate('tanggal_barang_masuk', '>=', $dari)
        ->whereDate('tanggal_barang_masuk', '<=', $sampai)->get();

    $keluar = \App\Models\BarangKeluar::with('barang')
        ->whereDate('tanggal_barang_keluar', '>=', $dari)
        ->whereDate('tanggal_barang_keluar', '<=', $sampai)->get();

    $pinjam = \App\Models\BarangPeminjam::with('barang')
        ->whereDate('tanggal_peminjaman', '>=', $dari)
        ->whereDate('tanggal_peminjaman', '<=', $sampai)->get();

    // 3. Gabungkan data
    $rincian = collect();

    foreach ($masuk as $m) {
        $rincian->push((object)[
            'id' => $m->id,
            'tanggal' => $m->tanggal_barang_masuk,
            'name' => $m->barang->name_barang ?? 'Unknown', // Ini untuk Performa Kategori
            'nama_barang' => $m->barang->name_barang ?? 'Unknown', // Ini untuk Tabel
            'total' => $m->jumlah, // Ini untuk Performa Kategori
            'jumlah' => $m->jumlah, // Ini untuk Tabel
            'status' => 'Masuk'
        ]);
    }

    foreach ($keluar as $k) {
        $rincian->push((object)[
            'id' => $k->id,
            'tanggal' => $k->tanggal_barang_keluar,
            'name' => $k->barang->name_barang ?? 'Unknown',
            'nama_barang' => $k->barang->name_barang ?? 'Unknown',
            'total' => $k->jumlah,
            'jumlah' => $k->jumlah,
            'status' => 'Keluar'
        ]);
    }

    foreach ($pinjam as $p) {
        $rincian->push((object)[
            'id' => $p->id,
            'tanggal' => $p->tanggal_peminjaman,
            'name' => $p->barang->name_barang ?? 'Unknown',
            'nama_barang' => $p->barang->name_barang ?? 'Unknown',
            'total' => $p->jumlah_pinjam,
            'jumlah' => $p->jumlah_pinjam,
            'status' => 'Pinjam'
        ]);
    }

    // 4. Urutkan & Hitung Total Revenue untuk Progress Bar
    $rincian = $rincian->sortByDesc('tanggal');
    $totalPeminjam = (object) ['total_revenue' => $rincian->sum('total') ?: 1];

    return view('laporan.index', compact('rincian', 'dari', 'sampai', 'totalPeminjam'));
}
public function exportExcel()
{
    return Excel::download(new BarangExport, 'laporan-inventaris.xlsx');
}

public function exportPdf(Request $request)
{
    $dari = $request->input('dari', date('Y-01-01'));
    $sampai = $request->input('sampai', date('Y-12-31'));

    // Ambil data yang sama dengan fungsi index
    $masuk = \App\Models\BarangMasuk::with('barang')->get(); // Sesuaikan querynya
    $keluar = \App\Models\BarangKeluar::with('barang')->get();
    $pinjam = \App\Models\BarangPeminjam::with('barang')->get();

    $rincian = collect(); // ... (gunakan logika push data yang sama seperti fungsi index) ...

    $pdf = Pdf::loadView('laporan.pdf', compact('rincian', 'dari', 'sampai'));
    return $pdf->download('laporan-inventaris.pdf');
}
}